@extends('buyer.layout')

@section('title', 'Secure Checkout')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/cart-checkout.css') }}">
@endsection

@section('content')

@if(session('success'))
<div class="alert-container">
    <div id="success-alert" class="alert-success">
        <span>{{ session('success') }}</span>
        <button type="button" class="alert-close" onclick="document.getElementById('success-alert').remove()">&times;</button>
    </div>
</div>
@endif

@if(session('error'))
<div class="alert-container">
    <div id="error-alert" class="alert-error">
        <span>{{ session('error') }}</span>
        <button type="button" class="alert-close" onclick="document.getElementById('error-alert').remove()">&times;</button>
    </div>
</div>
@endif

<div class="checkout-wrapper">
    <div class="form-section">
        <h1 class="checkout-title">Checkout</h1>
        
        <form action="{{ route('placeOrder') }}" method="POST">
            @csrf
            <h3 class="section-heading">Shipping Information</h3>
            
            <div class="input-grid">
                <div class="field-group full-width">
                    <label for="customer_name">Full Name</label>
                    <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required placeholder="e.g. Umer Nisar">
                </div>

                <div class="field-group full-width">
                    <label for="street_address">Street Address</label>
                    <input type="text" id="street_address" name="street_address" value="{{ old('street_address') }}" required placeholder="House #, Street name, Area">
                </div>

                <div class="field-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" value="{{ old('city') }}" required placeholder="e.g. Wah Cantt">
                </div>

                <div class="field-group">
                    <label for="postal_code">Postal Code</label>
                    <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required placeholder="e.g. 47040">
                </div>

                <div class="field-group full-width">
                    <label for="phone_number">Phone Number</label>
                    <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required placeholder="03XX-XXXXXXX">
                </div>
            </div>

            <button type="submit" class="btn-place-order">Complete Purchase</button>
        </form>
    </div>

    <div class="summary-sidebar">
        <h3 class="sidebar-title">Order Summary</h3>
        
        <ul class="order-items-list">
            @foreach ($cart as $item)
            <li class="summary-item">
                <img src="{{ asset('storage/' . $item->watch->image) }}" alt="{{ $item->watch->name }}">
                <div class="summary-item-info">
                    <p>{{ $item->watch->name }}</p>
                    <span class="item-qty">Qty: {{ $item->quantity }}</span>
                </div>
                <div class="item-price">
                    Rs. {{ number_format($item->watch->price * $item->quantity, 2) }}
                </div>
            </li>
            @endforeach
        </ul>

        <div class="summary-total-box">
            <div class="summary-detail-row">
                <span>Subtotal</span>
                <span>Rs. {{ number_format($total, 2) }}</span>
            </div>
            <div class="summary-detail-row">
                <span>Shipping</span>
                <span class="complimentary-text">Complimentary</span>
            </div>
            <div class="total-row">
                <span>Total</span>
                <span>Rs. {{ number_format($total, 2) }}</span>
            </div>
        </div>
        
        <p class="secure-text">Secure SSL Encrypted Checkout</p>
    </div>
</div>
@endsection