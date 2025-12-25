@extends('buyer.layout')

@section('title', 'Search Results')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/buyer/products.css') }}">
<link rel="stylesheet" href="{{ asset('css/buyer/product-cards.css') }}">
@endsection

@section('content')

@include('buyer.partials.hero-section')

<section class="featured-products">
    <div class="section-header">
        <h2 class="section-title">Search Results</h2>
        
        <div class="header-actions">
            @include('buyer.partials.sort')
            
            <a href="{{ route('home') }}" class="view-all-link">View All Collections</a>
        </div>
    </div>

    {{-- Search Summary --}}
    @if(isset($queryStr) && $queryStr)
    <div class="search-summary">
        <h3>
            Search results for: <span class="search-query">"{{ $queryStr }}"</span>
        </h3>
        <p class="results-count">
            {{ count($allWatches) }} {{ count($allWatches) == 1 ? 'result' : 'results' }} found
        </p>
    </div>
    @endif

    @if(count($allWatches) == 0)
        <div class="no-results">
            <div class="no-results-icon">🔍</div>
            <h3>No Results Found</h3>
            <p>We couldn't find any watches matching "<strong>{{ $queryStr ?? '' }}</strong>"</p>
            
            <div class="search-suggestions">
                <h4>Search Tips:</h4>
                <ul>
                    <li>✓ Check your spelling</li>
                    <li>✓ Try using different keywords</li>
                    <li>✓ Use more general search terms</li>
                    <li>✓ Try searching for watch brands or styles</li>
                </ul>
            </div>
            
            <a href="{{ route('home') }}" class="btn-add" style="display: inline-block; margin-top: 20px; padding: 12px 24px; text-decoration: none;">
                Browse All Watches
            </a>
        </div>
    @else
        <div class="product-grid" data-search-query="{{ $queryStr ?? '' }}">
            @foreach($allWatches as $watch)
                @include('buyer.partials.product-card', ['watch' => $watch])
            @endforeach
        </div>
    @endif
</section>

@endsection

@section('scripts')
<script src="{{ asset('js/buyer/product-validation.js') }}"></script>
<script>
    // Set search query for highlighting
    document.body.dataset.searchQuery = '{{ $queryStr ?? "" }}';
</script>
@endsection