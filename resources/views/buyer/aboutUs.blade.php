@extends('buyer.layout')

@section('title', 'Our Heritage')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --luxury-black: #0a0a0a;
        --text-muted: #757575;
        --bg-soft: #fcfcfc;
        --font-serif: 'Playfair Display', serif;
        --font-sans: 'Inter', sans-serif;
    }

    /* --- Hero Section --- */
    .about-hero {
        padding: 120px 20px;
        text-align: center;
        background: radial-gradient(circle at center, #ffffff 0%, #fcfcfc 100%);
        border-bottom: 1px solid #f0f0f0;
        margin-bottom: 80px;
    }

    .about-hero span {
        text-transform: uppercase;
        letter-spacing: 5px;
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        display: block;
        margin-bottom: 15px;
    }

    .about-hero h1 {
        font-family: var(--font-serif);
        font-size: 56px;
        font-weight: 400;
        color: var(--luxury-black);
        letter-spacing: -1px;
    }

    /* --- Story Section --- */
    .story-section {
        max-width: 1000px;
        margin: 0 auto 100px;
        padding: 0 40px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 80px;
        align-items: center;
    }

    .story-text h2 {
        font-family: var(--font-serif);
        font-size: 32px;
        margin-bottom: 25px;
        font-weight: 400;
        line-height: 1.2;
    }

    .story-text p {
        font-family: var(--font-sans);
        color: var(--text-muted);
        font-size: 16px;
        line-height: 1.9;
        margin-bottom: 20px;
        font-weight: 300;
    }

    .story-image {
        position: relative;
        background: var(--bg-soft);
        aspect-ratio: 4/5;
        overflow: hidden;
    }

    .story-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: grayscale(10%);
    }

    /* --- Values Section --- */
    .values-grid {
        background: var(--bg-soft);
        padding: 100px 40px;
        margin-bottom: 100px;
    }

    .values-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 60px;
        text-align: center;
    }

    .value-item h4 {
        font-family: var(--font-sans);
        text-transform: uppercase;
        letter-spacing: 3px;
        font-size: 12px;
        margin-bottom: 20px;
        font-weight: 700;
        color: var(--luxury-black);
    }

    .value-item p {
        font-family: var(--font-sans);
        font-size: 14px;
        color: var(--text-muted);
        line-height: 1.7;
        font-weight: 300;
    }

    /* --- Call to Action --- */
    .about-cta {
        text-align: center;
        padding-bottom: 120px;
    }

    .btn-explore {
        display: inline-block;
        padding: 18px 45px;
        background: var(--luxury-black);
        color: #fff;
        text-decoration: none;
        font-family: var(--font-sans);
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2.5px;
        transition: all 0.4s ease;
        border: 1px solid var(--luxury-black);
    }

    .btn-explore:hover {
        background: transparent;
        color: var(--luxury-black);
    }

    @media (max-width: 768px) {
        .story-section, .values-container {
            grid-template-columns: 1fr;
            gap: 40px;
        }
        .about-hero h1 { font-size: 38px; }
    }
</style>
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
        <div style="width: 100%; height: 100%; background: #eee; display: flex; align-items: center; justify-content: center; color: #aaa; font-size: 11px; text-transform: uppercase; letter-spacing: 3px;">Mechanical Heritage</div>
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
    <h2 style="font-family: var(--font-serif); font-size: 34px; margin-bottom: 35px; font-weight: 400;">Begin Your Collection</h2>
    <a href="{{ route('home') }}" class="btn-explore">Explore The Gallery</a>
</section>

@endsection