@extends('buyer.layout')

@section('title', 'Login')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --luxury-black: #0a0a0a;
        --text-muted: #666;
        --border-light: #eee;
        --bg-soft: #fcfcfc;
    }

    .auth-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 70vh;
        padding: 40px 20px;
        font-family: 'Inter', sans-serif;
    }

    .auth-card {
        width: 100%;
        max-width: 450px;
        background: #fff;
        padding: 50px;
        border: 1px solid var(--border-light);
        box-shadow: 0 10px 40px rgba(0,0,0,0.02);
        text-align: center;
    }

    .auth-card h1 {
        font-family: 'Playfair Display', serif;
        font-size: 32px;
        font-weight: 400;
        margin-bottom: 10px;
        color: var(--luxury-black);
    }

    .auth-card p {
        font-size: 14px;
        color: var(--text-muted);
        margin-bottom: 35px;
    }

    /* --- Form Styling --- */
    .form-group {
        text-align: left;
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 10px;
        color: var(--luxury-black);
    }

    .form-group input {
        width: 100%;
        padding: 15px;
        border: 1px solid #ddd;
        font-size: 14px;
        border-radius: 2px;
        transition: all 0.3s ease;
        background: var(--bg-soft);
        box-sizing: border-box;
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--luxury-black);
        background: #fff;
    }

    /* --- Primary Action Button --- */
    .btn-auth {
        width: 100%;
        padding: 18px;
        background: var(--luxury-black);
        color: #fff;
        border: 1px solid var(--luxury-black);
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2.5px;
        cursor: pointer;
        transition: all 0.4s ease;
        margin-top: 10px;
    }

    .btn-auth:hover {
        background: transparent;
        color: var(--luxury-black);
    }

    /* --- Footer Links --- */
    .auth-footer {
        margin-top: 30px;
        padding-top: 25px;
        border-top: 1px solid var(--border-light);
    }

    .auth-footer p {
        margin-bottom: 0;
        font-size: 13px;
    }

    .auth-link {
        color: var(--luxury-black);
        text-decoration: none;
        font-weight: 700;
        border-bottom: 1px solid var(--luxury-black);
        padding-bottom: 2px;
        transition: opacity 0.3s;
    }

    .auth-link:hover {
        opacity: 0.6;
    }

    /* Validation Errors */
    .error-msg {
        color: #b91c1c;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }
</style>
@endsection

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <h1>Welcome Back</h1>
        <p>Please enter your details to access your collection.</p>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="yourname@email.com">
                @error('email')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required placeholder="••••••••">
                @error('password')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-auth">Sign In</button>
        </form>

        <div class="auth-footer">
            <p>New to Watchify? <a href="{{ url('/register') }}" class="auth-link">Create an Account</a></p>
        </div>
    </div>
</div>
@endsection