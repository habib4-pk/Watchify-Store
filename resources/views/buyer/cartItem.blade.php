@extends('buyer.layout')

@section('title', 'Your Shopping Bag')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ secure_asset('css/buyer/cart.css') }}">

@endsection

@section('content')

<div class="cart-wrapper">
    @if(count($cart) > 0)
    <div class="cart-header">
        <h1>Shopping Bag</h1>
        <span class="item-count">{{ count($cart) }} Items Selected</span>
    </div>

    @foreach($cart as $item)
    <div class="cart-item">
        <div class="item-image">
            <img src="{{ $item->watch->image }}" alt="{{ $item->watch->name }}">
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
            <form action="{{ route('decrease') }}" method="POST" class="qty-form">
                @csrf
                <input type="hidden" name="decrease" value="{{ $item->id }}">
                <button type="submit" class="qty-btn">−</button>
            </form>

            <span class="qty-number">{{ $item->quantity }}</span>

            <form action="{{ route('increase') }}" method="POST" class="qty-form">
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
                <span>Excluded</span>
            </div>
            <div class="summary-row total-row">
                <span>Total</span>
                <span>Rs. {{ number_format($total, 2) }}</span>
            </div>

            <form action="{{ route('checkout') }}" method="GET">
                <button type="submit" class="checkout-btn">Proceed to Checkout</button>
            </form>
        </div>
    </div>

    @else
    <div class="empty-cart">
        <h2>Your bag is empty</h2>
        <p>Time stands still for no one. Start your collection today.</p>
        <a href="{{ route('home') }}" class="return-shop-link">Return to Shop</a>
    </div>
    @endif
</div>
@endsection