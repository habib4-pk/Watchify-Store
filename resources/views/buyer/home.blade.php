@extends('buyer.layout')

@section('title', 'Collections')

@section('styles')
<link rel="stylesheet" href="{{ secure_asset('css/products.css') }}">
<style>
    .btn-disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background: #6c757d !important;
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
    
    .stock-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 4px 8px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 4px;
        z-index: 1;
    }
    
    .stock-badge.in-stock {
        background: #d4edda;
        color: #155724;
    }
    
    .stock-badge.low-stock {
        background: #fff3cd;
        color: #856404;
    }
    
    .stock-badge.out-of-stock {
        background: #f8d7da;
        color: #721c24;
    }
    
    .image-container {
        position: relative;
    }
    
    .no-results {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }
    
    .no-results p {
        font-size: 18px;
        margin: 20px 0;
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
        
        <div class="header-actions">
            @include('buyer.partials.sort')
        </div>
    </div>

    @if(count($allWatches) == 0)
        <div class="no-results">
            <div style="font-size: 64px; margin-bottom: 20px; opacity: 0.5;">⌚</div>
            <h3>No Watches Available</h3>
            <p>Check back soon for our latest collection.</p>
        </div>
    @else
        <div class="product-grid">
            @foreach($allWatches as $watch)
            <article class="product-card" data-watch-id="{{ $watch->id }}">
                <div class="image-container">
                    <img src="{{ asset('storage/' . $watch->image) }}" 
                         class="product-image" 
                         alt="{{ $watch->name }}"
                         onerror="this.src='{{ asset('images/placeholder-watch.jpg') }}'">
                    
                    {{-- Stock Badge --}}
                    @if($watch->stock > 0 && $watch->stock <= 5)
                        <span class="stock-badge low-stock">Only {{ $watch->stock }} left</span>
                    @elseif($watch->stock > 0)
                        <span class="stock-badge in-stock">In Stock</span>
                    @else
                        <span class="stock-badge out-of-stock">Out of Stock</span>
                    @endif
                </div>

                <div class="product-info">
                    <h3 class="product-name">{{ $watch->name }}</h3>
                    <p class="product-price">Rs. {{ number_format($watch->price) }}</p>

                    <div class="button-group">
                        {{-- View Details --}}
                        <form action="{{ route('watchDetails') }}" 
                              method="GET" 
                              class="form-inline"
                              onsubmit="return validateDetailsForm(this)">
                            @csrf
                            <input type="hidden" name="id" value="{{ $watch->id }}" required>
                            <button type="submit" class="btn-view">Details</button>
                        </form>

                        {{-- Add to Cart --}}
                        @if($watch->stock > 0)
                            <form action="{{ route('addToCart') }}" 
                                  method="POST" 
                                  class="form-inline add-to-cart-form"
                                  onsubmit="return validateAddToCart(this, event)">
                                @csrf
                                <input type="hidden" name="id" value="{{ $watch->id }}" required>
                                <button type="submit" class="btn-add" data-original-text="+ Add to Bag">
                                    + Add to Bag
                                </button>
                            </form>
                        @else
                            <button class="btn-add btn-disabled" disabled title="Out of stock">
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Validate Details Form
    window.validateDetailsForm = function(form) {
        const idInput = form.querySelector('input[name="id"]');
        
        if (!idInput || !idInput.value) {
            alert('Invalid watch selection. Please try again.');
            return false;
        }
        
        const watchId = parseInt(idInput.value);
        if (isNaN(watchId) || watchId <= 0) {
            alert('Invalid watch ID. Please refresh the page.');
            return false;
        }
        
        return true;
    };
    
    // Validate Add to Cart
    window.validateAddToCart = function(form, event) {
        const button = form.querySelector('button[type="submit"]');
        const idInput = form.querySelector('input[name="id"]');
        
        // Validate ID
        if (!idInput || !idInput.value) {
            event.preventDefault();
            alert('Invalid watch selection. Please try again.');
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
        const originalText = button.getAttribute('data-original-text') || button.textContent;
        button.textContent = 'Adding...';
        
        // Re-enable after timeout
        setTimeout(function() {
            button.disabled = false;
            button.classList.remove('btn-loading');
            button.textContent = originalText;
        }, 5000);
        
        return true;
    };
    
    // Handle image errors
    const productImages = document.querySelectorAll('.product-image');
    productImages.forEach(img => {
        img.addEventListener('error', function() {
            this.src = '{{ asset("images/placeholder-watch.jpg") }}';
            this.alt = 'Image not available';
        });
    });
});
</script>
@endsection