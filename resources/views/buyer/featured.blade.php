@extends('buyer.layout')

@section('title', 'Featured Products')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/buyer/products.css') }}">
<link rel="stylesheet" href="{{ asset('css/buyer/product-cards.css') }}">
@endsection

@section('content')

@include('buyer.partials.hero-section')

<section class="featured-products">
    <div class="section-header">
        <h2 class="section-title">Featured Selection</h2>
        
        <div class="header-actions">
            @include('buyer.partials.sort')
            
            <a href="{{ route('home') }}" class="view-all-link">View All Collections</a>
        </div>
    </div>

    @if($allWatches->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">⌚</div>
            <h3>No Featured Watches Available</h3>
            <p>Check back soon for our curated selection of premium timepieces.</p>
            <a href="{{ route('home') }}" class="btn-add" style="display: inline-block; padding: 12px 24px; text-decoration: none;">
                Browse All Watches
            </a>
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