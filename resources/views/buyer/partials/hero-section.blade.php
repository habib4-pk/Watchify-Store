{{-- 
    Hero Section Partial
    Dynamic hero banner slideshow for buyer pages
    Used by: home.blade.php
--}}

@if(isset($heroBanners) && count($heroBanners) > 0)
    <section class="hero-slider" id="heroSlider">
        <div class="slides-wrapper">
            @foreach($heroBanners as $index => $banner)
                <div class="hero-slide {{ $index === 0 ? 'active' : '' }}" 
                     style="background-image: url('{{ $banner->image_url }}');"
                     data-index="{{ $index }}">
                    <div class="hero-overlay"></div>
                    <div class="hero-content">
                        @if($banner->title)
                            <h1 class="hero-title">{{ $banner->title }}</h1>
                        @endif
                        @if($banner->subtitle)
                            <p class="hero-subtitle">{{ $banner->subtitle }}</p>
                        @endif
                        @if($banner->button_text && $banner->button_link)
                            <a href="{{ $banner->button_link }}" class="hero-btn">{{ $banner->button_text }}</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        @if(count($heroBanners) > 1)
            <!-- Navigation Arrows -->
            <button class="hero-arrow prev" onclick="heroChangeSlide(-1)" aria-label="Previous">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="hero-arrow next" onclick="heroChangeSlide(1)" aria-label="Next">
                <i class="bi bi-chevron-right"></i>
            </button>
            
            <!-- Dots Navigation -->
            <div class="hero-dots">
                @foreach($heroBanners as $index => $banner)
                    <button class="dot {{ $index === 0 ? 'active' : '' }}" 
                            onclick="heroGoToSlide({{ $index }})"
                            aria-label="Go to slide {{ $index + 1 }}"></button>
                @endforeach
            </div>
        @endif
    </section>
    
    <style>
        .hero-slider {
            position: relative;
            width: 100%;
            height: 70vh;
            min-height: 400px;
            max-height: 600px;
            overflow: hidden;
        }
        
        .slides-wrapper {
            width: 100%;
            height: 100%;
        }
        
        .hero-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.5s ease-in-out, visibility 0.5s;
        }
        
        .hero-slide.active {
            opacity: 1;
            visibility: visible;
            z-index: 1;
        }
        
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%);
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
            padding: 0 10%;
            color: #fff;
        }
        
        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 5vw, 4rem);
            font-weight: 400;
            margin-bottom: 15px;
            animation: fadeInUp 0.6s ease-out;
        }
        
        .hero-subtitle {
            font-size: clamp(1rem, 2vw, 1.3rem);
            opacity: 0.9;
            margin-bottom: 25px;
            max-width: 500px;
            animation: fadeInUp 0.6s ease-out 0.1s both;
        }
        
        .hero-btn {
            display: inline-block;
            padding: 14px 35px;
            background: linear-gradient(135deg, #c9a050, #dbb668);
            color: #1a1a1a;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-radius: 4px;
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }
        
        .hero-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(201, 160, 80, 0.4);
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Arrows */
        .hero-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #fff;
            transition: all 0.3s ease;
            backdrop-filter: blur(4px);
        }
        
        .hero-arrow:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-50%) scale(1.1);
        }
        
        .hero-arrow.prev { left: 20px; }
        .hero-arrow.next { right: 20px; }
        
        /* Dots */
        .hero-dots {
            position: absolute;
            bottom: 25px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            display: flex;
            gap: 10px;
        }
        
        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.6);
            background: transparent;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .dot:hover {
            border-color: #fff;
        }
        
        .dot.active {
            background: #fff;
            border-color: #fff;
        }
        
        @media (max-width: 768px) {
            .hero-slider {
                height: 50vh;
                min-height: 300px;
            }
            .hero-content {
                padding: 0 5%;
            }
            .hero-arrow {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
            .hero-arrow.prev { left: 10px; }
            .hero-arrow.next { right: 10px; }
        }
    </style>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('heroSlider');
        if (!slider) return;
        
        const slides = slider.querySelectorAll('.hero-slide');
        const dots = slider.querySelectorAll('.dot');
        
        if (slides.length <= 1) return;
        
        let currentIndex = 0;
        let intervalId = null;
        let isPaused = false;
        const INTERVAL = 3000; // 3 seconds
        
        function goToSlide(index) {
            if (index < 0) index = slides.length - 1;
            if (index >= slides.length) index = 0;
            
            slides.forEach(s => s.classList.remove('active'));
            dots.forEach(d => d.classList.remove('active'));
            
            slides[index].classList.add('active');
            if (dots[index]) dots[index].classList.add('active');
            
            currentIndex = index;
        }
        
        function changeSlide(delta) {
            goToSlide(currentIndex + delta);
        }
        
        function startAutoAdvance() {
            if (intervalId) clearInterval(intervalId);
            intervalId = setInterval(function() {
                if (!isPaused) {
                    goToSlide(currentIndex + 1);
                }
            }, INTERVAL);
        }
        
        // Expose to global for onclick handlers
        window.heroGoToSlide = goToSlide;
        window.heroChangeSlide = changeSlide;
        
        // Mouse events
        slider.addEventListener('mouseenter', function() { isPaused = true; });
        slider.addEventListener('mouseleave', function() { isPaused = false; });
        
        // Start
        startAutoAdvance();
        console.log('Hero slider initialized with', slides.length, 'slides');
    });
    </script>
@else
    {{-- Fallback static hero if no banners --}}
    <section class="hero">
        <span class="hero-label">Established 2025</span>
        <h1>The Art of Precision</h1>
        <p>Explore a curated collection of timepieces that blend heritage with modern engineering.</p>
    </section>
@endif
