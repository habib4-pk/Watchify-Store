@extends('buyer.layout')

@section('title', 'Collections')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/buyer/products.css') }}">
<link rel="stylesheet" href="{{ asset('css/buyer/product-cards.css') }}">
@endsection

@section('content')

@include('buyer.partials.hero-section')

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
                @include('buyer.partials.product-card', ['watch' => $watch])
            @endforeach
        </div>
    @endif
</section>

@endsection

@section('scripts')
<script src="{{ asset('js/buyer/product-validation.js') }}"></script>
@endsection