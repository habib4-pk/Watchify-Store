<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>
    
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    
  
    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">
    
    @yield('styles')
</head>
<body>
    <div class="admin-layout">
        <aside class="sidebar-container">
            @include('admin.layouts.sidebar')
        </aside>

        <div class="main-wrapper">
            @include('admin.layouts.navbar')

            <main class="content-area">
                @yield('content')
            </main>

            @include('admin.layouts.footer')
        </div>
    </div>

    <script src="{{ asset('js/admin-main.js') }}"></script>
    @yield('scripts')
</body>
</html>