<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #f4f7fe; display: flex; min-height: 100vh; }
        
        .admin-layout { display: flex; width: 100%; }
        
        /* Sidebar Styling */
        .sidebar-container { width: 260px; background: #1e293b; color: white; min-height: 100vh; position: fixed; }
        
        /* Main Body Styling */
        .main-wrapper { flex: 1; margin-left: 260px; display: flex; flex-direction: column; min-width: 0; }
        .content-area { padding: 30px; flex: 1; }

        @media (max-width: 768px) {
            .sidebar-container { width: 70px; }
            .main-wrapper { margin-left: 70px; }
            .sidebar-text { display: none; }
        }
    </style>
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
</body>
</html>