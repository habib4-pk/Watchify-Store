<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\RegistrationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

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
        // 1. Strict Validation
        $request->validate([
            'email'    => 'required|email|max:255',
            'password' => 'required|string',
        ]);

        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                $user = Auth::user();

                if ($user->role === 'admin') {
                    return redirect()->route('adminDashboard')->with('success', 'Welcome Admin! You have logged in successfully.');
                }

                return redirect()->route('home')->with('success', 'Login successful. Welcome back!');
            }

            return redirect()->back()->with('error', 'Invalid email or password.')->withInput($request->only('email'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred during login.');
        }
    }

    public function register(Request $request)
    {
        // 1. Strict Validation
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        try {
            // 2. Data Creation with Password Hashing
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password; // Hashing is non-negotiable for security
            $user->role = 'buyer'; // Explicitly setting default role
            $user->save();

            // 3. Email Logic
            try {
                $mailData = [
                    'title' => 'Welcome to WatchifyStore',
                    'name' => $request->name,
                ];
                Mail::to($request->email)->send(new RegistrationMail($mailData));
            } catch (Exception $mailEx) {
                // Return success even if mail fails, but with a note
                return redirect()->route('loginForm')->with('success', 'Registration successful! (Confirmation email failed to send)');
            }

            return redirect()->route('loginForm')->with('success', 'Registration successful! Please login.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Registration failed. Please try again.')->withInput();
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

            if ($role === 'admin') {
                return redirect()->route('loginForm')->with('success', 'Admin logged out successfully.');
            }

            return redirect()->route('home')->with('success', 'Logged out successfully.');
        } catch (Exception $e) {
            Auth::logout();
            return redirect()->route('home');
        }
    }
}