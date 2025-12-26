<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | Watchify</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ secure_asset('css/buyer/layout.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/shared/alerts.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/shared/ajax-states.css') }}">
    
    <style>
        /* Mobile and Tablet: Normal zoom (default) */
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Laptop screens ONLY - zoom out for more content */
        @media (min-width: 992px) and (max-width: 1600px) {
            html {
                font-size: 13px; /* Smaller base font for maximum content */
            }
            body {
                zoom: 0.85; /* Zoom out ONLY on laptops */
            }
        }
        
        /* Large desktop screens */
        @media (min-width: 1601px) {
            html {
                font-size: 14px;
            }
            body {
                zoom: 0.9; /* Slight zoom out on large screens */
            }
        }
    </style>
    
    @yield('styles')
</head>
<body data-authenticated="{{ Auth::check() ? 'true' : 'false' }}" 
      data-login-url="{{ route('login') }}"
      data-placeholder-image="{{ secure_asset('images/placeholder-watch.jpg') }}">

    @include('buyer.layouts.navbar')

    {{-- Global Alert Container --}}
    <div class="global-alert-container" id="globalAlertContainer">
        
        {{-- Success Messages --}}
        @if(session('success'))
            <div class="global-alert global-alert-success" id="success-alert">
                <div class="global-alert-content">
                    <span class="global-alert-icon">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="global-alert-close" onclick="closeAlert('success-alert')">&times;</button>
            </div>
        @endif

        {{-- Error Messages --}}
        @if(session('error'))
            <div class="global-alert global-alert-error" id="error-alert">
                <div class="global-alert-content">
                    <span class="global-alert-icon">✕</span>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" class="global-alert-close" onclick="closeAlert('error-alert')">&times;</button>
            </div>
        @endif

        {{-- Warning Messages --}}
        @if(session('warning'))
            <div class="global-alert global-alert-warning" id="warning-alert">
                <div class="global-alert-content">
                    <span class="global-alert-icon">⚠</span>
                    <span>{{ session('warning') }}</span>
                </div>
                <button type="button" class="global-alert-close" onclick="closeAlert('warning-alert')">&times;</button>
            </div>
        @endif

        {{-- Info Messages --}}
        @if(session('info'))
            <div class="global-alert global-alert-info" id="info-alert">
                <div class="global-alert-content">
                    <span class="global-alert-icon">ℹ</span>
                    <span>{{ session('info') }}</span>
                </div>
                <button type="button" class="global-alert-close" onclick="closeAlert('info-alert')">&times;</button>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="global-alert global-alert-error" id="validation-alert">
                <div class="global-alert-content">
                    <strong>⚠ Please fix the following errors:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="global-alert-close" onclick="closeAlert('validation-alert')">&times;</button>
            </div>
        @endif

    </div>

    <main>
        @yield('content')
    </main>

    @include('buyer.layouts.footer')

    {{-- Core AJAX Module (must load first) --}}
    <script src="{{ secure_asset('js/ajax-core.js') }}"></script>
    
    {{-- Global Alert JavaScript --}}
    <script src="{{ secure_asset('js/buyer/global-alerts.js') }}"></script>
    
    {{-- Cart AJAX (loads on all pages for add-to-cart buttons) --}}
    <script src="{{ secure_asset('js/cart-ajax.js') }}"></script>
    
    {{-- Auth AJAX (handles logout forms on all pages) --}}
    <script src="{{ secure_asset('js/auth-ajax.js') }}"></script>
    
    {{-- Search AJAX (live search dropdown) --}}
    <script src="{{ secure_asset('js/search-ajax.js') }}"></script>

    @yield('scripts')

</body>
</html>