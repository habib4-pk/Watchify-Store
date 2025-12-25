@extends('buyer.layout')

@section('title', 'Login')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ secure_asset('css/auth.css') }}">
<link rel="stylesheet" href="{{ secure_asset('css/alert.css') }}">
@endsection


@section('content')
@if(session('success'))
<div class="alert-container">
    <div id="success-alert" class="alert-success">
        <span>{{ session('success') }}</span>
        <button type="button" class="alert-close"
            onclick="document.getElementById('success-alert').remove()">
            &times;
        </button>
    </div>
</div>
@endif

<div class="auth-wrapper">
    <div class="auth-card">
        <h1>Welcome Back</h1>
        <p>Please enter your details to access your collection.</p>

        @if(session('error'))
        <div id="error-msg" class="error-banner">
            {{ session('error') }}
        </div>
        @endif

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