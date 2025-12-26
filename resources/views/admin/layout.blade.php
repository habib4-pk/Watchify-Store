<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - @yield('title')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- AJAX States CSS -->
    <link rel="stylesheet" href="{{ secure_asset('css/shared/ajax-states.css') }}">
    
    @yield('styles')
</head>
<body style="font-family: 'Inter', sans-serif; background-color: #0d1117;" 
      data-authenticated="true"
      data-login-url="{{ route('login') }}">

    <!-- Mobile Navbar -->
    <nav class="navbar navbar-dark fixed-top d-lg-none" style="background-color: #161b22; border-bottom: 1px solid #30363d;">
        <div class="container-fluid">
            <button class="btn btn-link text-light p-0 me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
                <i class="bi bi-list fs-4"></i>
            </button>
            <a class="navbar-brand fw-bold" href="{{ url('/admin/dashboard') }}">
                <i class="bi bi-stopwatch text-primary me-2"></i>WatchAdmin
            </a>
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 14px;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Offcanvas Sidebar -->
    <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="sidebarOffcanvas" style="background-color: #161b22; border-right: 1px solid #30363d; width: 280px;">
        <div class="offcanvas-header border-bottom" style="border-color: #30363d !important;">
            <h5 class="offcanvas-title fw-bold text-white">
                <i class="bi bi-stopwatch text-primary me-2"></i>WatchAdmin
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            @include('admin.layouts.sidebar')
        </div>
    </div>

    <div class="d-flex">
        <!-- Desktop Sidebar -->
        <aside class="d-none d-lg-flex flex-column flex-shrink-0 position-fixed vh-100" style="width: 260px; background-color: #161b22; border-right: 1px solid #30363d; z-index: 1000;">
            @include('admin.layouts.sidebar')
        </aside>

        <!-- Main Content -->
        <div class="flex-grow-1" style="margin-left: 0;">
            <div class="d-none d-lg-block" style="margin-left: 260px;">
                @include('admin.layouts.navbar')
                
                <main class="p-4" style="min-height: calc(100vh - 140px);">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </main>

                @include('admin.layouts.footer')
            </div>
            
            <!-- Mobile Content -->
            <div class="d-lg-none" style="padding-top: 56px;">
                <main class="p-3" style="min-height: calc(100vh - 120px);">
                    <div class="container-fluid px-2">
                        @yield('content')
                    </div>
                </main>

                @include('admin.layouts.footer')
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Core AJAX Module (must load first) -->
    <script src="{{ secure_asset('js/ajax-core.js') }}"></script>
    
    <!-- Admin AJAX Module -->
    <script src="{{ secure_asset('js/admin-ajax.js') }}"></script>
    
    @yield('scripts')
</body>
</html>