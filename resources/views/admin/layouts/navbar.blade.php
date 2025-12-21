<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<style>
    .admin-navbar { 
        background: #ffffff; 
        padding: 15px 30px; 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        border-bottom: 1px solid #eeeeee;
        font-family: 'Inter', sans-serif;
    }

    .nav-title { 
        font-size: 18px; 
        font-weight: 700; 
        color: #333; 
    }

    .user-section { 
        display: flex; 
        align-items: center; 
        gap: 15px; 
    }

    .user-name { 
        font-size: 14px; 
        color: #666; 
    }

    /* Simple Red Logout Link */
    .logout-link {
        color: #e53e3e;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
    }

    .logout-link:hover {
        text-decoration: underline;
    }

    .profile-img {
        width: 35px; 
        height: 35px; 
        background: #f3f4f6; 
        border-radius: 50%; 
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #4b5563;
    }
</style>

<header class="admin-navbar">
    <div class="nav-title">@yield('title')</div>
    
    <div class="user-section">
        <span class="user-name">Hi, {{ Auth::user()->name ?? 'Admin' }}</span>
        
        <a href="{{ route('logout') }}" class="logout-link">Logout</a>
        
        <div class="profile-img">
            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
        </div>
    </div>
</header>