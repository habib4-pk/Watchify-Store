@extends('buyer.layout')

@section('title', 'My Orders')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
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

<div class="orders-container">
    <h1 class="page-title">Order History</h1>

    @if(count($orders) > 0)
        @foreach($orders as $order)
        <div class="order-card">
            <div class="order-header">
                <div class="header-left">
                    <span>Order Reference: #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                    <span class="separator">|</span>
                    <span>Placed: {{ $order->created_at->format('M d, Y') }}</span>
                </div>
                <div class="status-badge">{{ $order->status }}</div>
            </div>

            <div class="order-content">
                @foreach($order->orderItems as $items)
                <div class="item-row">
                    <img src="{{ asset('storage/' . $items->watch->image) }}" class="watch-img" alt="{{ $items->watch->name }}">
                    
                    <div class="item-details">
                        <p class="item-name">{{ $items->watch->name }}</p>
                        <p class="item-meta">Quantity: {{ $items->quantity }}</p>
                        <p class="item-meta">Unit Price: Rs. {{ number_format($items->price) }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="order-footer">
                <div class="total-paid">
                    <span class="total-label">Total Paid:</span>
                    Rs. {{ number_format($order->total_amount) }}
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="empty-history">
            <p>Your collection history is currently empty.</p>
            <a href="{{ route('home') }}" class="btn-shop">Begin Your Collection</a>
        </div>
    @endif
</div>
@endsection