{{-- 
    Product Card Partial
    Reusable product card component for watch listings
    @param $watch - The watch model object
    Used by: home.blade.php, featured.blade.php, searchedResult.blade.php
--}}

<article class="product-card" data-watch-id="{{ $watch->id }}">
    <div class="image-container">
        <img src="{{ $watch->image }}" 
             class="product-image" 
             alt="{{ $watch->name }}"
             onerror="this.src='{{ secure_asset('images/placeholder-watch.jpg') }}'">
        
        {{-- Stock Badge --}}
        @include('buyer.partials.stock-badge', ['stock' => $watch->stock])
    </div>

    <div class="product-info">
        <h3 class="product-name">{{ $watch->name }}</h3>
        <p class="product-price">Rs. {{ number_format($watch->price) }}</p>

        <div class="button-group">
            {{-- View Details --}}
            <form action="{{ route('shop.product') }}" 
                  method="GET" 
                  class="form-inline"
                  onsubmit="return validateDetailsForm(this)">
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
