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

    .checkout-wrapper {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 60px;
        max-width: 1200px;
        margin: 60px auto 100px;
        font-family: 'Inter', sans-serif;
    }

    .checkout-title {
        font-family: 'Playfair Display', serif;
        font-size: 32px;
        margin-bottom: 40px;
        border-bottom: 1px solid var(--luxury-black);
        padding-bottom: 15px;
    }

    /* --- Left Side: Shipping Form --- */
    .form-section h3 {
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 25px;
        font-weight: 700;
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
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--luxury-black);
    }

    .field-group input {
        padding: 14px;
        border: 1px solid #ddd;
        font-size: 14px;
        border-radius: 2px;
        transition: border-color 0.3s;
    }

    .field-group input:focus {
        outline: none;
        border-color: var(--luxury-black);
    }

    /* --- Right Side: Order Summary --- */
    .summary-sidebar {
        background: var(--bg-off-white);
        padding: 35px;
        border-radius: 4px;
        height: fit-content;
        position: sticky;
        top: 120px;
    }

    .summary-sidebar h3 {
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 25px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 15px;
    }

    .order-items-list {
        list-style: none;
        padding: 0;
        margin-bottom: 25px;
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

    .summary-item-info { flex: 1; }
    
    .summary-item-info p {
        font-size: 13px;
        font-weight: 600;
        margin: 0;
    }

    .summary-item-info span {
        font-size: 12px;
        color: var(--text-muted);
    }

    .summary-total-box {
        border-top: 1px solid #ddd;
        padding-top: 20px;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        font-size: 18px;
        font-weight: 700;
        margin-top: 10px;
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
        font-size: 13px;
        margin-top: 30px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-place-order:hover {
        background: #fff;
        color: var(--luxury-black);
    }

    @media (max-width: 992px) {
        .checkout-wrapper {
            grid-template-columns: 1fr;
        }
        .summary-sidebar {
            position: static;
            order: -1; /* Summary appears first on mobile */
        }
    }
</style>
@endsection

@section('content')
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
        
        <ul class="order-items-list">
            @foreach ($cart as $item)
            <li class="summary-item">
                <img src="{{ asset('storage/' . $item->watch->image) }}" alt="{{ $item->watch->name }}">
                <div class="summary-item-info">
                    <p>{{ $item->watch->name }}</p>
                    <span>Qty: {{ $item->quantity }}</span>
                </div>
                <div style="font-size: 13px; font-weight: 600;">
                    Rs. {{ number_format($item->watch->price * $item->quantity, 2) }}
                </div>
            </li>
            @endforeach
        </ul>

        <div class="summary-total-box">
            <div style="display: flex; justify-content: space-between; color: var(--text-muted); font-size: 14px; margin-bottom: 10px;">
                <span>Subtotal</span>
                <span>Rs. {{ number_format($total, 2) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; color: var(--text-muted); font-size: 14px; margin-bottom: 10px;">
                <span>Shipping</span>
                <span style="color: #15803d; font-weight: 600;">Complimentary</span>
            </div>
            <div class="total-row">
                <span>Total</span>
                <span>Rs. {{ number_format($total, 2) }}</span>
            </div>
        </div>
        
        <p style="font-size: 11px; color: #999; margin-top: 20px; text-align: center;">
            <i class="bi bi-shield-lock"></i> Secure SSL Encrypted Checkout
        </p>
    </div>
</div>
@endsection