@extends('buyer.layout')

@section('title', 'Search Results')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --luxury-black: #0a0a0a;
        --luxury-grey: #f7f7f7;
        --text-muted: #757575;
        --accent-blue: #2563eb;
        --font-serif: 'Playfair Display', serif;
        --font-sans: 'Inter', sans-serif;
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
        max-width: 1400px;
        border: 1px solid #bbf7d0;
        font-size: 14px;
        font-weight: 500;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        font-family: var(--font-sans);
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

    /* --- Hero Section --- */
    .hero {
        padding: 120px 20px;
        text-align: center;
        background: radial-gradient(circle at center, #ffffff 0%, #fcfcfc 100%);
        border-bottom: 1px solid #f0f0f0;
        margin-bottom: 80px;
    }

    .hero-label {
        text-transform: uppercase;
        letter-spacing: 5px;
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        display: block;
        margin-bottom: 15px;
        font-family: var(--font-sans);
    }

    .hero h1 {
        font-family: var(--font-serif);
        font-size: 56px;
        font-weight: 400;
        color: var(--luxury-black);
        margin-bottom: 15px;
        letter-spacing: -1px;
    }

    .hero p {
        font-family: var(--font-sans);
        color: var(--text-muted);
        font-size: 16px;
        font-weight: 300;
        max-width: 500px;
        margin: 0 auto;
    }

    /* --- Section Header Layout (The Fix) --- */
    .featured-products {
        max-width: 1400px;
        margin: 0 auto 100px;
        padding: 0 40px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end; /* Aligns title and actions to the same baseline */
        margin-bottom: 50px;
        border-bottom: 1px solid #eee;
        padding-bottom: 20px;
    }

    .section-title {
        font-family: var(--font-serif);
        font-size: 32px;
        color: var(--luxury-black);
        margin: 0;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 30px; /* Precise gap between Sort and "View All" link */
    }

    .view-all-link {
        font-size: 11px;
        text-transform: uppercase;
        color: var(--luxury-black);
        font-weight: 700;
        letter-spacing: 1.5px;
        text-decoration: none;
        border-bottom: 1px solid #000;
        padding-bottom: 5px;
        transition: opacity 0.3s;
    }
    .view-all-link:hover { opacity: 0.6; }

    /* --- Product Grid --- */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 60px 40px;
    }

    .product-card {
        background: transparent;
        display: flex;
        flex-direction: column;
    }

    .image-container {
        position: relative;
        background: var(--luxury-grey);
        aspect-ratio: 4/5;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 1.2s cubic-bezier(0.2, 1, 0.3, 1);
    }

    .product-card:hover .product-image { transform: scale(1.08); }

    .product-name {
        font-family: var(--font-sans);
        font-size: 15px;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        color: var(--luxury-black);
        text-transform: uppercase;
    }

    .product-price {
        font-family: var(--font-sans);
        color: var(--text-muted);
        font-size: 14px;
        font-weight: 400;
        margin-bottom: 25px;
    }

    /* --- Action Buttons --- */
    .button-group {
        display: flex;
        border-top: 1px solid #eee;
        padding-top: 15px;
    }

    .btn-view, .btn-add {
        background: transparent;
        border: none;
        font-family: var(--font-sans);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        cursor: pointer;
        padding: 10px 0;
        flex: 1;
    }

    .btn-view { color: var(--luxury-black); text-align: left; }
    .btn-add { color: var(--accent-blue); text-align: right; }
    .btn-add:hover { letter-spacing: 2px; }

    /* No Results Styling */
    .no-results-message {
        text-align: center;
        color: #555;
        font-family: var(--font-sans);
        font-size: 18px;
        margin: 80px 0;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    @media (max-width: 768px) {
        .hero h1 { font-size: 38px; }
        .section-header { flex-direction: column; align-items: flex-start; gap: 20px; }
        .header-actions { width: 100%; justify-content: space-between; }
    }
</style>
@endsection

@section('content')

@if(session('success'))
<div style="max-width: 1400px; margin: 0 auto; padding: 0 40px;">
    <div id="success-alert" class="alert-success">
        <span>{{ session('success') }}</span>
        <button type="button" class="close-btn" onclick="document.getElementById('success-alert').remove()">&times;</button>
    </div>
</div>
@endif

<section class="hero">
    <span class="hero-label">Established 2025</span>
    <h1>The Art of Precision</h1>
    <p>Explore a curated collection of timepieces that blend heritage with modern engineering.</p>
</section>

<section class="featured-products">
    <div class="section-header">
        <h2 class="section-title">Searched Selection</h2>
        
        <div class="header-actions">
            @include('buyer.partials.sort')
            
            <a href="{{ route('home') }}" class="view-all-link">View All Collections</a>
        </div>
    </div>

    

    @if(count($allWatches) == 0)
        <p class="no-results-message">
            No matching results found for "<strong>{{ $query ?? '' }}</strong>"
        </p>
    @else
        <div class="product-grid">
            @foreach($allWatches as $watch)
            <article class="product-card">
                <div class="image-container">
                    <img src="{{ asset('storage/' . $watch->image) }}" class="product-image" alt="{{ $watch->name }}">
                </div>

                <div class="product-info">
                    <h3 class="product-name">{{ $watch->name }}</h3>
                    <p class="product-price">Rs. {{ number_format($watch->price) }}</p>

                    <div class="button-group">
                        <form action="{{ route('watchDetails') }}" method="GET" style="flex:1">
                            <input type="hidden" name="id" value="{{ $watch->id }}">
                            <button type="submit" class="btn-view">Details</button>
                        </form>

                        @if($watch->stock > 0)
                        <form action="{{ route('addToCart') }}" method="POST" style="flex:1">
                            @csrf
                            <input type="hidden" name="id" value="{{ $watch->id }}">
                            <button type="submit" class="btn-add">+ Add to Bag</button>
                        </form>
                        @else
                        <button class="btn-add" disabled style="flex:1; cursor: not-allowed; opacity: 0.6;">
                            Out of stock
                        </button>
                        @endif
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    @endif
</section>

@endsection