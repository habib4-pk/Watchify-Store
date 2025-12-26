<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\RegistrationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Exception;

/**
 * AuthController
 * Handles authentication: login, register, logout
 * Supports both AJAX (JSON) and traditional (redirect) responses
 */
class AuthController extends Controller
{
    public function loginForm()
    {
        try {
            return view('auth.login');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Login page not found.');
        }
    }

    public function registerForm()
    {
        try {
            return view('auth.register');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Registration page not found.');
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email|max:255',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput($request->only('email'));
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $redirect = $user->role === 'admin' ? route('adminDashboard') : route('shop.index');
            $message = $user->role === 'admin' 
                ? 'Welcome Admin! You have logged in successfully.' 
                : 'Login successful. Welcome back!';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'redirect' => $redirect,
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role
                    ]
                ]);
            }

            return redirect($redirect)->with('success', $message);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.'
            ], 401);
        }

        return redirect()->back()->with('error', 'Invalid email or password.')->withInput($request->only('email'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput($request->except('password'));
        }

        try {
            // Create user
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password; // Model should hash this
            $user->role = 'user';
            $user->save();

            // Auto-login after registration
            Auth::login($user);
            $request->session()->regenerate();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registration successful! Welcome aboard!',
                    'redirect' => route('shop.index'),
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email
                    ]
                ]);
            }

            return redirect()->route('shop.index')->with('success', 'Registration successful! Welcome aboard!');
        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registration failed. Please try again.'
                ], 500);
            }
            return redirect()->back()->with('error', 'Registration failed. Please try again.');
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            $role = $user ? $user->role : 'buyer';

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $redirect = route('shop.index');
            $message = $role === 'admin' ? 'Admin logged out successfully.' : 'Logged out successfully.';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'redirect' => $redirect
                ]);
            }

            return redirect($redirect)->with('success', $message);
        } catch (Exception $e) {
            Auth::logout();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logged out.',
                    'redirect' => route('shop.index')
                ]);
            }
            return redirect()->route('shop.index');
        }
    }
}