<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            if (Auth::check() && Auth::user()->role === 'admin') {
                return $next($request);
            }

            return redirect()->route('login')->with('error', 'You must be an admin to access this page.');
        } catch (Exception $e) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'An error occurred during authorization.');
        }
    }
}