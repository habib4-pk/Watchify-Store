@extends('buyer.layout')

@section('title', 'Our Heritage')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/about-us.css') }}">
@endsection

@section('content')

<section class="about-hero">
    <span>Established 2025</span>
    <h1>The Art of Horology</h1>
</section>

<section class="story-section">
    <div class="story-text">
        <h2>A Legacy of Precision</h2>
        <p>Founded by Umer Nisar, Watchify Store was born from a singular passion: to bridge the gap between historical craftsmanship and contemporary engineering. Our journey began with a simple belief that time is the ultimate luxury.</p>
        <p>We curate timepieces that do more than just record seconds; they capture history. Every gear, spring, and dial in our collection represents a silent companion that marks life's most significant milestones.</p>
    </div>
    <div class="story-image">
        <div class="story-placeholder">Mechanical Heritage</div>
    </div>
</section>

<section class="values-grid">
    <div class="values-container">
        <div class="value-item">
            <h4>Purity of Design</h4>
            <p>We select timepieces that demonstrate aesthetic balance, ensuring each watch is a masterpiece of both form and function.</p>
        </div>
        <div class="value-item">
            <h4>Authentic Provenance</h4>
            <p>Integrity is our core. Every watch comes with guaranteed authenticity and a comprehensive international warranty for absolute peace of mind.</p>
        </div>
        <div class="value-item">
            <h4>Curation</h4>
            <p>Our gallery is not just a shop, but a curated selection of limited editions and rare complications for the discerning collector.</p>
        </div>
    </div>
</section>

<section class="about-cta">
    <h2>Begin Your Collection</h2>
    <a href="{{ route('home') }}" class="btn-explore">Explore The Gallery</a>
</section>

@endsection