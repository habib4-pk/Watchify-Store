@extends('buyer.layout')

@section('title', 'My Orders')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">

<style>
    :root {
        --luxury-black: #0a0a0a;
        --text-muted: #757575;
        --border-light: #f2f2f2;
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
        max-width: 1100px;
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

    /* --- Page Layout --- */
    .orders-container {
        max-width: 1100px;
        margin: 60px auto 100px;
        padding: 0 20px;
        font-family: 'Inter', sans-serif;
    }

    .page-title {
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 5px;
        font-size: 24px;
        color: var(--luxury-black);
        margin-bottom: 50px;
        border-bottom: 1px solid var(--border-light);
        padding-bottom: 20px;
    }

    /* --- Order Card Styling --- */
    .order-card {
        border: 1px solid var(--border-light);
        margin-bottom: 40px;
        background: #fff;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .order-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.03);
    }

    .order-header {
        background: #fafafa;
        padding: 20px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--border-light);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #888;
    }

    .status-badge {
        background: var(--luxury-black);
        color: #fff;
        padding: 6px 14px;
        font-size: 10px;
        letter-spacing: 2px;
    }

    /* --- Item row Layout --- */
    .order-content { padding: 30px; }

    .item-row {
        display: flex;
        align-items: center;
        gap: 30px;
        padding: 20px 0;
    }

    .item-row:not(:last-child) { border-bottom: 1px solid #f9f9f9; }

    .watch-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        background-color: #f5f5f5;
        border-radius: 2px;
    }

    .item-details { flex-grow: 1; }

    .item-name {
        font-size: 15px;
        font-weight: 700;
        margin: 0 0 5px 0;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--luxury-black);
    }

    .item-meta {
        font-size: 13px;
        color: var(--text-muted);
        margin: 0;
    }

    /* --- Summary Footer --- */
    .order-footer {
        padding: 20px 30px;
        border-top: 1px solid var(--border-light);
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }

    .total-paid {
        font-size: 16px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--luxury-black);
    }

    .empty-history {
        text-align: center;
        padding: 120px 0;
    }

    .empty-history p {
        text-transform: uppercase;
        letter-spacing: 3px;
        color: #aaa;
        font-size: 14px;
        margin-bottom: 30px;
    }

    .btn-shop {
        display: inline-block;
        padding: 15px 35px;
        background: var(--luxury-black);
        color: #fff;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        transition: 0.3s;
    }

    .btn-shop:hover { background: #333; }
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

<div class="orders-container">
    <h1 class="page-title">Order History</h1>

    @if(count($orders) > 0)
        @foreach($orders as $order)
        <div class="order-card">
            <div class="order-header">
                <div class="header-left">
                    <span>Order Reference: #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                    <span style="margin: 0 15px;">|</span>
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
                    <span style="font-weight: 400; color: #888; margin-right: 15px; font-size: 13px;">Total Paid:</span>
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