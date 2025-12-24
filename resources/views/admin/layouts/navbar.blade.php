<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">

<header class="admin-navbar">
    <div class="nav-title">@yield('title')</div>
    
    <div class="user-section">
        <span class="user-name">Hi, {{ Auth::user()->name ?? 'Admin' }}</span>
        
       
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
    @csrf
    <input type="submit" value="Logout" class="logout-button" />
</form>

        <div class="profile-img">
            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
        </div>
    </div>
</header>