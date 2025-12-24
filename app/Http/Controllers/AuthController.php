<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\RegistrationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('adminDashboard')->with('success', 'Welcome Admin! You have logged in successfully.');
            }

            return redirect()->route('home')->with('success', 'Login successful. Welcome back!');
        }

        return redirect()->back()->with('error', 'Invalid email or password.');
    }

    public function register(Request $request)
    {
        $alreadyExist = User::where('email', $request->email)->first();
        if ($alreadyExist) {
            return redirect()->back()->with('error', 'Email already registered. Please use another email.');
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();

        $mailData = [
            'title' => 'Welcome to WatchifyStore',
            'name' => $request->name,
        ];

        Mail::to($request->email)->send(new RegistrationMail($mailData));

        return redirect()->route('loginForm')->with('success', 'Registration successful! Please login.');
    }

    public function logout(Request $request)
    {
        $role = Auth::user()->role;

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($role === 'admin') {
            return redirect()->route('login')->with('success', 'Admin logged out successfully.');
        }

        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }
}
