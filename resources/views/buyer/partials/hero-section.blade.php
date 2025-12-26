{{-- 
    Hero Section Partial
    Premium dynamic hero banner slideshow
    Used by: home.blade.php
--}}

@if(isset($heroBanners) && count($heroBanners) > 0)
    <section class="hero-slider" id="heroSlider">
        <!-- Slides -->
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
                            <a href="{{ $banner->button_link }}" class="hero-btn">
                                {{ $banner->button_text }}
                                <i class="bi bi-arrow-right ms-2"></i>
                            </a>
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
            
            <!-- Progress Bar -->
            <div class="hero-progress">
                <div class="hero-progress-bar" id="heroProgressBar"></div>
            </div>
        @endif
    </section>
    
    <style>
        .hero-slider {
            position: relative;
            width: 100%;
            height: 80vh;
            min-height: 500px;
            max-height: 700px;
            overflow: hidden;
            background: #0a0a0a;
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
            transform: scale(1.05);
            transition: opacity 0.8s ease, visibility 0.8s, transform 6s ease-out;
        }
        
        .hero-slide.active {
            opacity: 1;
            visibility: visible;
            transform: scale(1);
            z-index: 1;
        }
        
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0.3) 50%, rgba(0,0,0,0.5) 100%);
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            height: 100%;
            padding: 0 12%;
            color: #fff;
            max-width: 800px;
        }
        
        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 500;
            margin-bottom: 20px;
            line-height: 1.1;
            text-shadow: 0 2px 30px rgba(0,0,0,0.3);
            opacity: 0;
            transform: translateY(30px);
            animation: heroFadeUp 0.8s ease forwards 0.2s;
        }
        
        .hero-subtitle {
            font-size: clamp(1rem, 2vw, 1.4rem);
            opacity: 0;
            margin-bottom: 30px;
            max-width: 500px;
            line-height: 1.6;
            font-weight: 300;
            text-shadow: 0 1px 10px rgba(0,0,0,0.3);
            transform: translateY(30px);
            animation: heroFadeUp 0.8s ease forwards 0.4s;
        }
        
        .hero-btn {
            display: inline-flex;
            align-items: center;
            padding: 16px 40px;
            background: linear-gradient(135deg, #c9a050 0%, #e8c36a 50%, #c9a050 100%);
            background-size: 200% 100%;
            color: #1a1a1a;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 2px;
            text-transform: uppercase;
            border-radius: 50px;
            transition: all 0.4s ease;
            box-shadow: 0 4px 20px rgba(201, 160, 80, 0.3);
            opacity: 0;
            transform: translateY(30px);
            animation: heroFadeUp 0.8s ease forwards 0.6s;
        }
        
        .hero-btn:hover {
            background-position: 100% 0;
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(201, 160, 80, 0.5);
            color: #1a1a1a;
        }
        
        @keyframes heroFadeUp {
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
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            width: 56px;
            height: 56px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #fff;
            transition: all 0.3s ease;
        }
        
        .hero-arrow:hover {
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.4);
            transform: translateY(-50%) scale(1.1);
        }
        
        .hero-arrow.prev { left: 30px; }
        .hero-arrow.next { right: 30px; }
        
        /* Dots */
        .hero-dots {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            display: flex;
            gap: 12px;
        }
        
        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.5);
            background: transparent;
            cursor: pointer;
            transition: all 0.4s ease;
            position: relative;
        }
        
        .dot:hover {
            border-color: #fff;
            transform: scale(1.2);
        }
        
        .dot.active {
            background: #c9a050;
            border-color: #c9a050;
            box-shadow: 0 0 15px rgba(201, 160, 80, 0.5);
        }
        
        /* Progress Bar */
        .hero-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: rgba(255,255,255,0.1);
            z-index: 10;
        }
        
        .hero-progress-bar {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #c9a050, #e8c36a);
            transition: width 0.1s linear;
        }
        
        @media (max-width: 992px) {
            .hero-slider {
                height: 60vh;
                min-height: 400px;
            }
            .hero-content {
                padding: 0 8%;
            }
            .hero-arrow {
                width: 46px;
                height: 46px;
                font-size: 18px;
            }
            .hero-arrow.prev { left: 15px; }
            .hero-arrow.next { right: 15px; }
        }
        
        @media (max-width: 576px) {
            .hero-slider {
                height: 50vh;
                min-height: 350px;
            }
            .hero-content {
                padding: 0 6%;
                align-items: center;
                text-align: center;
            }
            .hero-arrow {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
            .hero-arrow.prev { left: 10px; }
            .hero-arrow.next { right: 10px; }
            .hero-dots {
                bottom: 25px;
            }
            .dot {
                width: 10px;
                height: 10px;
            }
        }
    </style>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('heroSlider');
        if (!slider) return;
        
        const slides = slider.querySelectorAll('.hero-slide');
        const dots = slider.querySelectorAll('.dot');
        const progressBar = document.getElementById('heroProgressBar');
        
        if (slides.length <= 1) {
            if (progressBar) progressBar.parentElement.style.display = 'none';
            return;
        }
        
        let currentIndex = 0;
        let intervalId = null;
        let isPaused = false;
        let progress = 0;
        const INTERVAL = 4000; // 4 seconds per slide
        const TICK = 50; // Update progress every 50ms
        
        function goToSlide(index) {
            if (index < 0) index = slides.length - 1;
            if (index >= slides.length) index = 0;
            
            slides.forEach(s => {
                s.classList.remove('active');
                // Reset animations
                const title = s.querySelector('.hero-title');
                const subtitle = s.querySelector('.hero-subtitle');
                const btn = s.querySelector('.hero-btn');
                if (title) title.style.animation = 'none';
                if (subtitle) subtitle.style.animation = 'none';
                if (btn) btn.style.animation = 'none';
            });
            dots.forEach(d => d.classList.remove('active'));
            
            const activeSlide = slides[index];
            activeSlide.classList.add('active');
            
            // Trigger animations
            setTimeout(() => {
                const title = activeSlide.querySelector('.hero-title');
                const subtitle = activeSlide.querySelector('.hero-subtitle');
                const btn = activeSlide.querySelector('.hero-btn');
                if (title) title.style.animation = 'heroFadeUp 0.8s ease forwards 0.2s';
                if (subtitle) subtitle.style.animation = 'heroFadeUp 0.8s ease forwards 0.4s';
                if (btn) btn.style.animation = 'heroFadeUp 0.8s ease forwards 0.6s';
            }, 50);
            
            if (dots[index]) dots[index].classList.add('active');
            
            currentIndex = index;
            progress = 0;
        }
        
        function changeSlide(delta) {
            goToSlide(currentIndex + delta);
        }
        
        function updateProgress() {
            if (!isPaused) {
                progress += (TICK / INTERVAL) * 100;
                if (progressBar) progressBar.style.width = progress + '%';
                
                if (progress >= 100) {
                    goToSlide(currentIndex + 1);
                }
            }
        }
        
        function startAutoAdvance() {
            if (intervalId) clearInterval(intervalId);
            intervalId = setInterval(updateProgress, TICK);
        }
        
        // Expose globally
        window.heroGoToSlide = goToSlide;
        window.heroChangeSlide = changeSlide;
        
        // Events
        slider.addEventListener('mouseenter', function() { isPaused = true; });
        slider.addEventListener('mouseleave', function() { isPaused = false; });
        
        // Touch swipe
        let touchStartX = 0;
        slider.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        
        slider.addEventListener('touchend', function(e) {
            const diff = touchStartX - e.changedTouches[0].screenX;
            if (Math.abs(diff) > 50) {
                changeSlide(diff > 0 ? 1 : -1);
            }
        }, { passive: true });
        
        // Start
        startAutoAdvance();
    });
    </script>
@else
    {{-- Fallback static hero if no banners --}}
    <section class="hero" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); padding: 120px 10%; text-align: center;">
        <span class="hero-label" style="color: #c9a050; text-transform: uppercase; letter-spacing: 4px; font-size: 12px;">Established 2025</span>
        <h1 style="color: #fff; font-family: 'Playfair Display', serif; font-size: clamp(2rem, 5vw, 3.5rem); margin: 20px 0;">The Art of Precision</h1>
        <p style="color: rgba(255,255,255,0.7); max-width: 500px; margin: 0 auto;">Explore a curated collection of timepieces that blend heritage with modern engineering.</p>
    </section>
@endif
