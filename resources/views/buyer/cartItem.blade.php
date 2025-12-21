@extends('buyer.layout')

@section('title', 'Your Shopping Bag')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --luxury-black: #0a0a0a;
        --border-color: #f0f0f0;
        --text-muted: #757575;
        --accent-green: #15803d;
    }

    /* --- Manual Dismiss Success Alert --- */
    .alert-success { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        padding: 15px 20px; 
        background: #dcfce7; 
        color: #15803d; 
        border-radius: 8px; 
        margin-bottom: 25px; 
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

    .cart-wrapper {
        max-width: 1000px;
        margin: 60px auto 100px;
        font-family: 'Inter', sans-serif;
    }

    .cart-header {
        text-align: left;
        border-bottom: 1px solid var(--luxury-black);
        padding-bottom: 20px;
        margin-bottom: 40px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .cart-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 38px;
        font-weight: 400;
        margin: 0;
    }

    .item-count {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--text-muted);
    }

    /* --- Cart Item Row --- */
    .cart-item {
        display: grid;
        grid-template-columns: 150px 1fr 150px 150px;
        align-items: center;
        padding: 30px 0;
        border-bottom: 1px solid var(--border-color);
        gap: 30px;
    }

    .item-image img {
        width: 100%;
        aspect-ratio: 1/1;
        object-fit: cover;
        background: #f9f9f9;
        border-radius: 2px;
    }

    .item-details h3 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .item-details p {
        font-size: 14px;
        color: var(--text-muted);
    }

    /* --- Quantity Controls --- */
    .quantity-control {
        display: flex;
        align-items: center;
        gap: 15px;
        border: 1px solid #e2e2e2;
        padding: 8px 12px;
        width: fit-content;
    }

    .qty-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 18px;
        color: var(--luxury-black);
        padding: 0 5px;
        transition: opacity 0.2s;
    }

    .qty-btn:hover { opacity: 0.5; }

    .qty-number {
        font-size: 14px;
        font-weight: 600;
        min-width: 20px;
        text-align: center;
    }

    .item-subtotal {
        font-weight: 700;
        font-size: 16px;
        text-align: right;
    }

    .remove-btn {
        background: none;
        border: none;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #b91c1c;
        cursor: pointer;
        margin-top: 10px;
        font-weight: 700;
        padding: 0;
    }

    /* --- Summary Section --- */
    .cart-footer {
        margin-top: 50px;
        display: flex;
        justify-content: flex-end;
    }

    .summary-box {
        width: 100%;
        max-width: 350px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 15px;
    }

    .total-row {
        border-top: 1px solid var(--luxury-black);
        padding-top: 20px;
        margin-top: 20px;
        font-size: 20px;
        font-weight: 700;
    }

    .checkout-luxury {
        width: 100%;
        background: var(--luxury-black);
        color: white;
        border: 1px solid var(--luxury-black);
        padding: 18px;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        margin-top: 30px;
        transition: all 0.3s ease;
    }

    .checkout-luxury:hover {
        background: white;
        color: var(--luxury-black);
    }

    .empty-cart {
        text-align: center;
        padding: 100px 0;
    }

    .empty-cart h2 {
        font-family: 'Playfair Display', serif;
        font-weight: 400;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .cart-item {
            grid-template-columns: 100px 1fr;
        }
        .item-subtotal { text-align: left; grid-column: 2; }
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

@if(session('error'))
    <div style="max-width: 500px; margin: 0 auto 25px; padding: 0 15px;">
        <div id="error-alert" class="alert-error" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background: #fee2e2; color: #b91c1c; border-radius: 8px; border: 1px solid #fca5a5; font-size: 14px; font-weight: 500; box-shadow: 0 2px 8px rgba(0,0,0,0.05); font-family: 'Inter', sans-serif;">
            <span>{{ session('error') }}</span>
            <button type="button" class="close-btn" onclick="document.getElementById('error-alert').remove()" style="background: none; border: none; color: #b91c1c; font-size: 24px; font-weight: bold; cursor: pointer; line-height: 1; padding: 0; margin-left: 20px; transition: transform 0.2s ease;">&times;</button>
        </div>
    </div>
@endif


<div class="cart-wrapper">
    @if(count($cart) > 0)
    <div class="cart-header">
        <h1>Shopping Bag</h1>
        <span class="item-count">{{ count($cart) }} Items Selected</span>
    </div>

    @foreach($cart as $item)
    <div class="cart-item">
        <div class="item-image">
            <img src="{{ asset('storage/' . $item->watch->image) }}" alt="{{ $item->watch->name }}">
        </div>

        <div class="item-details">
            <h3>{{ $item->watch->name }}</h3>
            <p>Unit Price: Rs. {{ number_format($item->watch->price, 2) }}</p>
            
            <form action="{{ route('remove') }}" method="POST">
                @csrf
                <input type="hidden" name="remove" value="{{ $item->id }}">
                <button type="submit" class="remove-btn">Remove Item</button>
            </form>
        </div>

        <div class="quantity-control">
            <form action="{{ route('decrease') }}" method="POST">
                @csrf
                <input type="hidden" name="decrease" value="{{ $item->id }}">
                <button type="submit" class="qty-btn">−</button>
            </form>

            <span class="qty-number">{{ $item->quantity }}</span>

            <form action="{{ route('increase') }}" method="POST">
                @csrf
                <input type="hidden" name="increase" value="{{ $item->id }}">
                <button type="submit" class="qty-btn">+</button>
            </form>
        </div>

        <div class="item-subtotal">
            Rs. {{ number_format($item->subtotal, 2) }}
        </div>
    </div>
    @endforeach

    <div class="cart-footer">
        <div class="summary-box">
            <div class="summary-row">
                <span>Shipping</span>
                <span>Calculated at next step</span>
            </div>
            <div class="summary-row">
                <span>Taxes</span>
                <span>Included</span>
            </div>
            <div class="summary-row total-row">
                <span>Total</span>
                <span>Rs. {{ number_format($total, 2) }}</span>
            </div>

            <form action="{{ route('checkout') }}" method="GET">
                <button type="submit" class="checkout-luxury">Proceed to Checkout</button>
            </form>
        </div>
    </div>

    @else
    <div class="empty-cart">
        <h2>Your bag is empty</h2>
        <p style="color: #888; margin-bottom: 30px;">Time stands still for no one. Start your collection today.</p>
        <a href="{{ route('home') }}" style="text-decoration: none; border-bottom: 1px solid #000; color: #000; font-weight: 700; text-transform: uppercase; font-size: 12px; letter-spacing: 1px;">Return to Shop</a>
    </div>
    @endif
</div>
@endsection