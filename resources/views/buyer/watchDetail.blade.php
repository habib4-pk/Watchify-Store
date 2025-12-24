@extends('buyer.layout')

@section('title', $watch->name)

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/watch-detail.css') }}">
<link rel="stylesheet" href="{{ asset('css/alert.css') }}">



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
            @if($watch->stock > 0)
                @if($watch->stock <= 5)
                    Only {{ $watch->stock }} left in stock - Order soon!
                @else
                    Available for Immediate Delivery
                @endif
            @else
                Currently Unavailable
            @endif
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

        <p class="warranty-text">
            Complimentary Shipping & 2-Year International Warranty
        </p>
    </div>
</div>
@endsection