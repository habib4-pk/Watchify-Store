@extends('buyer.layout')

@section('title', $watch->name)

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/watch-detail.css') }}">
<link rel="stylesheet" href="{{ asset('css/alert.css') }}">
<link rel="stylesheet" href="{{ asset('css/reviews.css') }}">
<style>
    /* Validation Styles */
    .review-form select.error,
    .review-form textarea.error {
        border-color: #dc3545;
        background-color: #fff5f5;
    }
    
    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: block;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .required-star {
        color: #dc3545;
    }
    
    .btn-loading {
        position: relative;
        pointer-events: none;
        opacity: 0.7;
    }
    
    .btn-loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin-left: -8px;
        margin-top: -8px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spinner 0.6s linear infinite;
    }
    
    @keyframes spinner {
        to { transform: rotate(360deg); }
    }
    
    .character-count {
        font-size: 0.8rem;
        color: #6c757d;
        text-align: right;
        margin-top: 0.25rem;
    }
    
    .character-count.warning {
        color: #ffc107;
    }
    
    .character-count.error {
        color: #dc3545;
    }
</style>
@endsection

@section('content')

<div class="detail-wrapper">

    <div class="image-gallery">
        <img src="{{ asset('storage/' . $watch->image) }}" 
             alt="{{ $watch->name }}"
             onerror="this.src='{{ asset('images/placeholder-watch.jpg') }}'">
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
            <form action="{{ route('addToCart') }}" 
                  method="POST" 
                  id="addToCartForm"
                  onsubmit="return validateAddToCart(this, event)">
                @csrf
                <input type="hidden" name="id" value="{{ $watch->id }}" required>
                <button type="submit" class="btn-buy" id="addToCartBtn" data-original-text="Add to Shopping Bag">
                    Add to Shopping Bag
                </button>
            </form>
        @else
            <button class="btn-buy disabled-btn" disabled title="Out of stock">
                Out of Stock
            </button>
        @endif

        <p class="warranty-text">
            🔒 Complimentary Shipping & 2-Year International Warranty
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
        <span class="avg-text">{{ number_format($avg, 1) }} out of 5 ({{ count($reviews) }} {{ count($reviews) == 1 ? 'review' : 'reviews' }})</span>
    </div>

    <div class="reviews-list">
        @forelse($reviews as $review)
            <div class="single-review">
                <div class="review-header">
                    <strong>{{ $review->user->name ?? 'Anonymous' }}</strong>
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
                <small class="review-date">{{ $review->created_at->diffForHumans() }}</small>
                
                @auth
                    @if(Auth::id() == $review->user_id)
                        <form action="{{ route('reviewDelete') }}" 
                              method="POST" 
                              style="display:inline-block;"
                              onsubmit="return confirmDelete(event)">
                            @csrf
                            <input type="hidden" name="review_id" value="{{ $review->id }}" required>
                            <button type="submit" class="btn-delete">Delete</button>
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
        <form action="{{ route('reviewStore') }}" 
              method="POST" 
              id="reviewForm"
              novalidate>
            @csrf
            <input type="hidden" name="watch_id" value="{{ $watch->id }}" required>
            
            <div class="form-group">
                <label for="rating">Rating <span class="required-star">*</span></label>
                <select name="rating" 
                        id="rating" 
                        class="@error('rating') error @enderror"
                        required>
                    <option value="">Select Rating</option>
                    <option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 - Excellent)</option>
                    <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ (4 - Good)</option>
                    <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>⭐⭐⭐ (3 - Average)</option>
                    <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>⭐⭐ (2 - Poor)</option>
                    <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>⭐ (1 - Bad)</option>
                </select>
                @error('rating')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="comment">Comment <span class="required-star">*</span></label>
                <textarea name="comment" 
                          id="comment"
                          rows="4" 
                          class="@error('comment') error @enderror"
                          placeholder="Share your experience with this watch (minimum 10 characters)..."
                          required
                          minlength="10"
                          maxlength="1000">{{ old('comment') }}</textarea>
                <div class="character-count" id="charCount">0 / 1000 characters</div>
                @error('comment')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <button type="submit" id="reviewSubmitBtn">Submit Review</button>
        </form>
    </div>
    @else
        <div class="login-prompt" style="text-align: center; padding: 30px; background: #f8f9fa; border-radius: 8px; margin-top: 20px;">
            <p style="margin-bottom: 15px;">Please <a href="{{ route('login') }}" style="color: #007bff; text-decoration: underline;">login</a> to write a review.</p>
        </div>
    @endauth
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Add to Cart Validation
    window.validateAddToCart = function(form, event) {
        const button = form.querySelector('#addToCartBtn');
        const idInput = form.querySelector('input[name="id"]');
        
        // Validate ID
        if (!idInput || !idInput.value) {
            event.preventDefault();
            alert('Invalid watch selection. Please refresh the page.');
            return false;
        }
        
        const watchId = parseInt(idInput.value);
        if (isNaN(watchId) || watchId <= 0) {
            event.preventDefault();
            alert('Invalid watch ID. Please refresh the page.');
            return false;
        }
        
        // Check authentication
        const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
        if (!isAuthenticated) {
            event.preventDefault();
            if (confirm('Please login to add items to your cart. Would you like to login now?')) {
                window.location.href = '{{ route("login") }}';
            }
            return false;
        }
        
        // Check if already processing
        if (button.disabled || button.classList.contains('btn-loading')) {
            event.preventDefault();
            return false;
        }
        
        // Add loading state
        button.disabled = true;
        button.classList.add('btn-loading');
        button.textContent = 'Adding...';
        
        // Re-enable after timeout
        setTimeout(function() {
            button.disabled = false;
            button.classList.remove('btn-loading');
            button.textContent = button.getAttribute('data-original-text');
        }, 5000);
        
        return true;
    };
    
    // Review Form Validation
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        const ratingSelect = document.getElementById('rating');
        const commentTextarea = document.getElementById('comment');
        const charCount = document.getElementById('charCount');
        const submitBtn = document.getElementById('reviewSubmitBtn');
        
        // Character counter
        if (commentTextarea && charCount) {
            commentTextarea.addEventListener('input', function() {
                const length = this.value.length;
                charCount.textContent = `${length} / 1000 characters`;
                
                if (length < 10) {
                    charCount.className = 'character-count error';
                } else if (length > 900) {
                    charCount.className = 'character-count warning';
                } else {
                    charCount.className = 'character-count';
                }
            });
            
            // Initialize counter
            const initialLength = commentTextarea.value.length;
            charCount.textContent = `${initialLength} / 1000 characters`;
        }
        
        // Form submission validation
        reviewForm.addEventListener('submit', function(e) {
            let isValid = true;
            let errors = [];
            
            // Validate rating
            if (!ratingSelect.value) {
                isValid = false;
                errors.push('Please select a rating');
                ratingSelect.classList.add('error');
            } else {
                ratingSelect.classList.remove('error');
            }
            
            // Validate comment
            const comment = commentTextarea.value.trim();
            if (comment.length < 10) {
                isValid = false;
                errors.push('Comment must be at least 10 characters');
                commentTextarea.classList.add('error');
            } else if (comment.length > 1000) {
                isValid = false;
                errors.push('Comment must not exceed 1000 characters');
                commentTextarea.classList.add('error');
            } else {
                commentTextarea.classList.remove('error');
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fix the following errors:\n' + errors.join('\n'));
                return false;
            }
            
            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';
            
            // Re-enable after timeout
            setTimeout(function() {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Review';
            }, 5000);
        });
    }
    
    // Delete confirmation
    window.confirmDelete = function(event) {
        return confirm('Are you sure you want to delete this review? This action cannot be undone.');
    };
    
    // Image error handling
    const productImage = document.querySelector('.image-gallery img');
    if (productImage) {
        productImage.addEventListener('error', function() {
            this.src = '{{ asset("images/placeholder-watch.jpg") }}';
            this.alt = 'Image not available';
        });
    }
});
</script>
@endsection