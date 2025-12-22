@extends('buyer.layout')

@section('title', 'Collections')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/products.css') }}">
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

<section class="hero">
    <span class="hero-label">Established 2025</span>
    <h1>The Art of Precision</h1>
    <p>Explore a curated collection of timepieces that blend heritage with modern engineering.</p>
</section>

<section class="featured-products">
    <div class="section-header">
        <h2 class="section-title">New Arrivals</h2>
        
        <div class="header-actions">
            @include('buyer.partials.sort')
        </div>
    </div>

    @if(count($allWatches) == 0)
        <div class="no-results">
            <p>No matching timepieces found.</p>
        </div>
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
                        <form action="{{ route('watchDetails') }}" method="GET" class="form-inline">
                            <input type="hidden" name="id" value="{{ $watch->id }}">
                            <button type="submit" class="btn-view">Details</button>
                        </form>

                        @if($watch->stock > 0)
                        <form action="{{ route('addToCart') }}" method="POST" class="form-inline">
                            @csrf
                            <input type="hidden" name="id" value="{{ $watch->id }}">
                            <button type="submit" class="btn-add">+ Add to Bag</button>
                        </form>
                        @else
                        <button class="btn-add btn-disabled" disabled>
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