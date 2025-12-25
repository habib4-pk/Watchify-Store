<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Watchify</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/buyer/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/shared/alerts.css') }}">
    
    @yield('styles')
</head>
<body data-authenticated="{{ Auth::check() ? 'true' : 'false' }}" 
      data-login-url="{{ route('login') }}"
      data-placeholder-image="{{ asset('images/placeholder-watch.jpg') }}">

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

    {{-- Global Alert JavaScript (externalized) --}}
    <script src="{{ asset('js/buyer/global-alerts.js') }}"></script>

    @yield('scripts')

</body>
</html>