<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // Add no-cache to all web routes to prevent form resubmission popup
        $middleware->web(append: [
            \App\Http\Middleware\NoCacheMiddleware::class,
        ]);

        // REGISTER YOUR ALIAS HERE
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle CSRF token expired (419) - return JSON for AJAX, redirect for normal
        $exceptions->render(function (TokenMismatchException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session expired. Please refresh the page.',
                    'csrf_token' => csrf_token(),
                    'reload' => true
                ], 419);
            }
            
            // For regular form submissions, redirect back with error
            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Session expired. Please try again.');
        });
    })->create();