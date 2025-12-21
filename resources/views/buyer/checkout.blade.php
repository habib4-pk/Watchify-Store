@extends('buyer.layout')

@section('title', 'Secure Checkout')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --luxury-black: #0a0a0a;
        --text-muted: #666;
        --border-light: #eee;
        --bg-off-white: #fafafa;
    }

    /* --- Manual Dismiss Success Alert --- */
    .alert-success { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        padding: 15px 25px; 
        background: #dcfce7; 
        color: #15803d; 
        border-radius: 4px; 
        margin: 20px auto 0;
        max-width: 1200px;
        border: 1px solid #bbf7d0; 
        font-size: 14px;
        font-weight: 500;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        font-family: 'Inter', sans-serif;
    }

    .close-btn { 
        background: none; 
        border: none; 
        color: #15803d; 
        font-size: 24px; 
        font-weight: bold; 
        cursor: pointer; 
        line-height: 1; 
        padding: 0;
        margin-left: 20px;
        transition: transform 0.2s ease;
    }
    .close-btn:hover { transform: scale(1.2); opacity: 0.7; }

    .checkout-wrapper {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 60px;
        max-width: 1200px;
        margin: 40px auto 100px;
        font-family: 'Inter', sans-serif;
    }

    .checkout-title {
        font-family: 'Playfair Display', serif;
        font-size: 32px;
        margin-bottom: 40px;
        border-bottom: 1px solid var(--luxury-black);
        padding-bottom: 15px;
    }

    /* --- Form Section --- */
    .form-section h3 {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 25px;
        font-weight: 700;
        color: var(--luxury-black);
    }

    .input-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .full-width { grid-column: span 2; }

    .field-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .field-group label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--luxury-black);
    }

    .field-group input {
        padding: 14px;
        border: 1px solid #ddd;
        font-size: 14px;
        border-radius: 2px;
        transition: border-color 0.3s;
        font-family: 'Inter', sans-serif;
    }

    .field-group input:focus {
        outline: none;
        border-color: var(--luxury-black);
    }

    /* --- Sidebar Summary --- */
    .summary-sidebar {
        background: var(--bg-off-white);
        padding: 35px;
        border-radius: 4px;
        height: fit-content;
        position: sticky;
        top: 120px;
    }

    .summary-sidebar h3 {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 25px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 15px;
    }

    .summary-item {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        align-items: center;
    }

    .summary-item img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        background: #fff;
        border: 1px solid #eee;
    }

    .summary-item-info p {
        font-size: 13px;
        font-weight: 600;
        margin: 0;
        text-transform: uppercase;
    }

    .summary-total-box {
        border-top: 1px solid #ddd;
        padding-top: 20px;
        margin-top: 10px;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        font-size: 18px;
        font-weight: 700;
        margin-top: 10px;
        color: var(--luxury-black);
    }

    .btn-place-order {
        width: 100%;
        background: var(--luxury-black);
        color: #fff;
        border: 1px solid var(--luxury-black);
        padding: 18px;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 700;
        font-size: 12px;
        margin-top: 30px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-place-order:hover {
        background: #fff;
        color: var(--luxury-black);
    }

    @media (max-width: 992px) {
        .checkout-wrapper { grid-template-columns: 1fr; }
        .summary-sidebar { position: static; order: -1; margin-bottom: 40px; }
    }
</style>
@endsection

@section('content')

@if(session('success'))
    <div style="max-width: 500px; margin: 0 auto 25px; padding: 0 15px;">
        <div id="success-alert" class="alert-success" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background: #dcfce7; color: #15803d; border-radius: 8px; border: 1px solid #bbf7d0; font-size: 14px; font-weight: 500; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <span>{{ session('success') }}</span>
            <button type="button" class="close-btn" onclick="document.getElementById('success-alert').remove()" style="background: none; border: none; color: #15803d; font-size: 24px; font-weight: bold; cursor: pointer; line-height: 1; padding: 0; margin-left: 20px; transition: transform 0.2s ease;">&times;</button>
        </div>
    </div>
@endif


<div class="checkout-wrapper">
    <div class="form-section">
        <h1 class="checkout-title">Checkout</h1>
        
        <form action="{{ route('placeOrder') }}" method="POST">
            @csrf
            <h3>Shipping Information</h3>
            
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
        <h3>Order Summary</h3>
        
        <ul class="order-items-list" style="list-style: none; padding: 0;">
            @foreach ($cart as $item)
            <li class="summary-item">
                <img src="{{ asset('storage/' . $item->watch->image) }}" alt="{{ $item->watch->name }}">
                <div class="summary-item-info">
                    <p>{{ $item->watch->name }}</p>
                    <span style="font-size: 12px; color: var(--text-muted);">Qty: {{ $item->quantity }}</span>
                </div>
                <div style="font-size: 13px; font-weight: 600;">
                    Rs. {{ number_format($item->watch->price * $item->quantity, 2) }}
                </div>
            </li>
            @endforeach
        </ul>

        <div class="summary-total-box">
            <div style="display: flex; justify-content: space-between; color: var(--text-muted); font-size: 13px; margin-bottom: 12px;">
                <span>Subtotal</span>
                <span>Rs. {{ number_format($total, 2) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; color: var(--text-muted); font-size: 13px; margin-bottom: 12px;">
                <span>Shipping</span>
                <span style="color: #15803d; font-weight: 600;">Complimentary</span>
            </div>
            <div class="total-row">
                <span>Total</span>
                <span>Rs. {{ number_format($total, 2) }}</span>
            </div>
        </div>
        
        <p style="font-size: 10px; color: #999; margin-top: 30px; text-align: center; text-transform: uppercase; letter-spacing: 1px;">
            Secure SSL Encrypted Checkout
        </p>
    </div>
</div>
@endsection