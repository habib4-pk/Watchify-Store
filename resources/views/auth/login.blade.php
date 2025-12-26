@extends('buyer.layout')

@section('title', 'Login')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ secure_asset('css/shared/auth.css') }}">
<link rel="stylesheet" href="{{ secure_asset('css/shared/alerts.css') }}">
@endsection


@section('content')

<div class="auth-wrapper">
    <div class="auth-card">
        <h1>Welcome Back</h1>
        <p>Please enter your details to access your collection.</p>

        @if(session('error'))
        <div id="error-msg" class="error-banner">
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('account.login.submit') }}" method="POST">
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
              
            </div>

            <div class="form-group remember-group">
                <label class="remember-label">
                    <input type="checkbox" name="remember" id="remember">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="btn-auth">Sign In</button>
        </form>

        <div class="auth-footer">
            <p>New to Watchify? <a href="{{ url('/register') }}" class="auth-link">Create an Account</a></p>
        </div>
    </div>
</div>

<script>

setTimeout(function() {
    var errorDiv = document.getElementById('error-msg');
    if (errorDiv) {
        errorDiv.style.display = 'none';
    }
}, 5000);
</script>
@endsection