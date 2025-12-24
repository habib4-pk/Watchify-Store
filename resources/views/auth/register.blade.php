@extends('buyer.layout')

@section('title', 'Join Watchify')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
<link rel="stylesheet" href="{{ asset('css/a.css') }}">
@endsection

@section('content')

<link rel="stylesheet" href="{{ asset('css/about-us.css') }}">

<div class="auth-wrapper">
    <div class="auth-card">
        <h1>Join the Gallery</h1>
        <p>Create an account to start your premium watch collection.</p>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="e.g. Umer Nisar">
             
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="yourname@email.com">
               
            </div>

            <div class="form-group">
                <label for="password">Create Password</label>
                <input type="password" name="password" id="password" required placeholder="••••••••" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
              
            </div>

            <div id="message" class="password-requirements">
              
                <p id="length" class="invalid">Minimum <b>8 characters</b></p>
                <p id="char" class="invalid">A <b>special character</b></p>
            </div>

            <button type="submit" class="btn-auth">Create Account</button>
        </form>

        <div class="auth-footer">
            <p>Already a member? <a href="{{ url('/login') }}" class="auth-link">Sign In</a></p>
        </div>
    </div>
</div>

<script src="{{ asset('js/auth.js') }}"></script>
@endsection