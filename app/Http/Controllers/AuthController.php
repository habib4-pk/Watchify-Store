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
       
    }

    public function register(Request $request)
    {
        // Simple validation
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        // Create user
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'user';
        $user->save();

        // Try to send email (optional)
        try {
            $mailData = [
                'title' => 'Welcome to WatchifyStore',
                'name' => $request->name,
            ];
            Mail::to($request->email)->send(new RegistrationMail($mailData));
        } catch (Exception $mailEx) {
            // Email failed, but continue
        }

        return redirect()->route('loginForm')->with('success', 'Registration successful! Please login.');
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