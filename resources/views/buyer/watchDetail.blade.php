@extends('buyer.layout')

@section('title', $watch->name)

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --luxury-black: #0a0a0a;
        --text-muted: #666;
        --bg-gallery: #fcfcfc;
        --border-light: #f0f0f0;
    }

    .detail-wrapper {
        display: grid;
        grid-template-columns: 1.2fr 1fr; /* Image gets slightly more space */
        gap: 80px;
        max-width: 1300px;
        margin: 80px auto;
        font-family: 'Inter', sans-serif;
        align-items: start;
    }

    /* --- Image Gallery Area --- */
    .image-gallery {
        background: var(--bg-gallery);
        padding: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
    }

    .image-gallery img {
        width: 100%;
        height: auto;
        max-height: 600px;
        object-fit: contain;
        transition: transform 0.5s ease;
    }

    .image-gallery img:hover {
        transform: scale(1.05);
    }

    /* --- Content Area --- */
    .product-details {
        padding-top: 20px;
    }

    .brand-label {
        text-transform: uppercase;
        letter-spacing: 4px;
        font-size: 11px;
        font-weight: 700;
        color: #999;
        margin-bottom: 15px;
        display: block;
    }

    .product-title {
        font-family: 'Playfair Display', serif;
        font-size: 42px;
        font-weight: 400;
        color: var(--luxury-black);
        margin-bottom: 20px;
        line-height: 1.2;
    }

    .price-tag {
        font-size: 24px;
        font-weight: 400;
        color: #333;
        margin-bottom: 35px;
        display: block;
    }

    .description-box {
        border-top: 1px solid var(--border-light);
        padding-top: 30px;
        margin-bottom: 40px;
    }

    .description-box h4 {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 15px;
        color: var(--luxury-black);
    }

    .description-box p {
        font-size: 15px;
        line-height: 1.8;
        color: var(--text-muted);
        font-weight: 300;
    }

    /* --- Stock & Actions --- */
    .stock-status {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .in-stock { color: #15803d; }
    .in-stock .status-dot { background: #15803d; }
    
    .out-stock { color: #b91c1c; }
    .out-stock .status-dot { background: #b91c1c; }

    .btn-buy {
        width: 100%;
        background: var(--luxury-black);
        color: #fff;
        border: 1px solid var(--luxury-black);
        padding: 20px;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-buy:hover {
        background: transparent;
        color: var(--luxury-black);
    }

    .disabled-btn {
        background: #eee;
        color: #999;
        border-color: #eee;
        cursor: not-allowed;
    }

    @media (max-width: 992px) {
        .detail-wrapper {
            grid-template-columns: 1fr;
            gap: 40px;
            margin: 40px 20px;
        }
        .product-title { font-size: 32px; }
    }
</style>
@endsection

@section('content')
<div class="detail-wrapper">
    
    <div class="image-gallery">
        <img src="{{ asset('storage/' . $watch->image) }}" alt="{{ $watch->name }}">
    </div>

    <div class="product-details">
        <span class="brand-label">Luxury Collection 2025</span>
        <h1 class="product-title">{{ $watch->name }}</h1>
        <span class="price-tag">Rs. {{ number_format($watch->price) }}</span>

        <div class="description-box">
            <h4>Description</h4>
            <p>{{ $watch->description }}</p>
        </div>

        <div class="stock-status {{ $watch->stock > 0 ? 'in-stock' : 'out-stock' }}">
            <div class="status-dot"></div>
            {{ $watch->stock > 0 ? 'Available for Immediate Delivery' : 'Currently Unavailable' }}
        </div>

        @if($watch->stock > 0)
            <form action="{{ route('addToCart') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $watch->id }}">
                <button type="submit" class="btn-buy">Add to Shopping Bag</button>
            </form>
        @else
            <button class="btn-buy disabled-btn" disabled>Out of Stock</button>
        @endif

        <p style="margin-top: 25px; font-size: 12px; color: #999; text-align: center; letter-spacing: 0.5px;">
            Complimentary Shipping & 2-Year International Warranty
        </p>
    </div>
</div>
@endsection