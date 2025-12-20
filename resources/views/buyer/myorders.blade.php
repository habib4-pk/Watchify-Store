@extends('buyer.layout')

@section('title', 'My Orders')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">

<style>
    /* --- Page Container --- */
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
        color: #000;
        margin-bottom: 50px;
        border-bottom: 1px solid #f2f2f2;
        padding-bottom: 20px;
    }

    /* --- Luxury Order Card --- */
    .order-card {
        border: 1px solid #f2f2f2;
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
        border-bottom: 1px solid #f2f2f2;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #888;
    }

    .status-badge {
        background: #000;
        color: #fff;
        padding: 6px 14px;
        font-size: 10px;
        letter-spacing: 2px;
    }

    /* --- Item Layout --- */
    .order-content {
        padding: 30px;
    }

    .item-row {
        display: flex;
        align-items: center;
        gap: 30px;
        padding: 20px 0;
    }

    .item-row:not(:last-child) {
        border-bottom: 1px solid #f9f9f9;
    }

    .watch-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        background-color: #f5f5f5;
        border-radius: 2px;
    }

    .item-details {
        flex-grow: 1;
    }

    .item-name {
        font-size: 15px;
        font-weight: 700;
        margin: 0 0 5px 0;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #000;
    }

    .item-meta {
        font-size: 13px;
        color: #777;
        margin: 0;
    }

    /* --- Total Section --- */
    .order-footer {
        padding: 20px 30px;
        border-top: 1px solid #f2f2f2;
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }

    .total-paid {
        font-size: 16px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #000;
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
        background: #000;
        color: #fff;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        transition: 0.3s;
    }

    .btn-shop:hover {
        background: #333;
    }
</style>
@endsection

@section('content')
<div class="orders-container">
    <h1 class="page-title">Order History</h1>

    @if(count($orders) > 0)
        @foreach($orders as $order)
        <div class="order-card">
            <div class="order-header">
                <div class="header-left">
                    <span>Order ID: #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
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
                    <span style="font-weight: 400; color: #888; margin-right: 15px;">Total Paid:</span>
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