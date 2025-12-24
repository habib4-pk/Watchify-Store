@extends('buyer.layout')

@section('title', $watch->name)

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/watch-detail.css') }}">
<link rel="stylesheet" href="{{ asset('css/alert.css') }}">
<link rel="stylesheet" href="{{ asset('css/reviews.css') }}">
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

<div class="reviews-wrapper">

    <h2 class="reviews-title">Customer Reviews</h2>

    <div class="avg-rating">
        @php
            $avg = $avgRating ?? 0;
            $fullStars = floor($avg);
        @endphp
        <div class="stars">
            @for($i = 1; $i <= 5; $i++)
                @if($i <= $fullStars)
                    <span class="star filled">★</span>
                @else
                    <span class="star">☆</span>
                @endif
            @endfor
        </div>
        <span class="avg-text">{{ number_format($avg, 1) }} out of 5</span>
    </div>

    <div class="reviews-list">
        @forelse($reviews as $review)
            <div class="single-review">
                <div class="review-header">
                    <strong>{{ $review->user->name }}</strong>
                    <span class="review-stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                    </span>
                </div>
                <p class="review-comment">{{ $review->comment }}</p>
                @auth
                    @if(Auth::id() == $review->user_id)
                        <form action="{{ route('review.delete') }}" method="POST" style="display:inline-block;">
                            @csrf
                            <input type="hidden" name="review_id" value="{{ $review->id }}">
                            <button type="submit" class="btn-delete" onclick="return confirm('Are you sure you want to delete this review?')">Delete</button>
                        </form>
                    @endif
                @endauth
            </div>
        @empty
            <p class="no-reviews">No reviews yet. Be the first to review!</p>
        @endforelse
    </div>

    @auth
    <div class="review-form">
        <h3>Write a Review</h3>
        <form action="{{ route('review.store') }}" method="POST">
            @csrf
            <input type="hidden" name="watch_id" value="{{ $watch->id }}">
            <label>Rating</label>
            <select name="rating" required>
                <option value="">Select</option>
                <option value="5">5 - Excellent</option>
                <option value="4">4 - Good</option>
                <option value="3">3 - Average</option>
                <option value="2">2 - Poor</option>
                <option value="1">1 - Bad</option>
            </select>
            <label>Comment</label>
            <textarea name="comment" rows="4" placeholder="Share your experience..." required></textarea>
            <button type="submit">Submit Review</button>
        </form>
    </div>
    @endauth
</div>

@endsection
