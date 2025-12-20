@extends('buyer.layout')

@section('title', 'Collections')

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

    /* --- Product Grid --- */
    .featured-products {
        max-width: 1400px;
        margin: 0 auto 100px;
        padding: 0 40px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 50px;
    }

    .section-title { 
        font-family: var(--font-serif);
        font-size: 32px; 
        color: var(--luxury-black);
    }
    
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 60px 40px; /* More vertical breathing room */
    }

    /* --- Product Card --- */
    .product-card {
        background: transparent;
        border: none;
        overflow: visible;
        transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
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

    .product-card:hover .product-image {
        transform: scale(1.08);
    }

    /* --- Card Info --- */
    .product-info { 
        text-align: left; 
    }

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

    /* --- Refined Action Buttons --- */
    .button-group { 
        display: flex; 
        gap: 0; 
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
        transition: all 0.3s ease;
        flex: 1;
    }

    .btn-view {
        color: var(--luxury-black);
        text-align: left;
    }

    .btn-add {
        color: var(--accent-blue);
        text-align: right;
    }

    .btn-view:hover { color: #666; }
    .btn-add:hover { letter-spacing: 2px; }

    /* Responsive */
    @media (max-width: 768px) {
        .hero h1 { font-size: 38px; }
        .featured-products { padding: 0 20px; }
        .product-grid { gap: 40px 20px; }
    }
</style>
@endsection

@section('content')

<section class="hero">
    <span class="hero-label">Established 2025</span>
    <h1>The Art of Precision</h1>
    <p>Explore a curated collection of timepieces that blend heritage with modern engineering.</p>
</section>

<section class="featured-products">
    <div class="section-header">
        <h2 class="section-title">New Arrivals</h2>
        <a href="#" style="font-size: 12px; text-transform: uppercase; color: var(--luxury-black); font-weight: 700; letter-spacing: 1px; text-decoration: none; border-bottom: 1px solid #000; padding-bottom: 4px;">View All Collections</a>
    </div>

    <div class="product-grid">
        @foreach($allWatches as $watch)
        <article class="product-card">
            <div class="image-container">
                <img src="{{ asset('storage/' . $watch->image) }}" 
                     class="product-image" 
                     alt="{{ $watch->name }}">
            </div>

            <div class="product-info">
                <h3 class="product-name">{{ $watch->name }}</h3>
                <p class="product-price">Rs. {{ number_format($watch->price) }}</p>

                <div class="button-group">
                    <form action="{{ route('watchDetails') }}" method="GET" style="flex:1">
                        <input type="hidden" name="id" value="{{ $watch->id }}">
                        <button type="submit" class="btn-view">Details</button>
                    </form>

              

                    <form action="{{ route('addToCart') }}" method="POST" style="flex:1">
                        @csrf
                        <input type="hidden" name="id" value="{{ $watch->id }}">
                        <button type="submit" class="btn-add">+ Add to Bag</button>
                    </form>
                    

                </div>
            </div>
        </article>
        @endforeach
    </div>
</section>

@endsection