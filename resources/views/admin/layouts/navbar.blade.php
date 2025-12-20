<style>
    .admin-navbar { background: white; height: 70px; display: flex; align-items: center; justify-content: space-between; padding: 0 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .nav-title { font-size: 18px; font-weight: 600; color: #1e293b; }
    .user-profile { display: flex; align-items: center; gap: 10px; }
    .user-name { font-size: 14px; font-weight: 500; color: #64748b; }
</style>

<header class="admin-navbar">
    <div class="nav-title">@yield('title')</div>
    <div class="user-profile">
        <span class="user-name">Welcome, Admin</span>
        <a href="{{route('logout')}}">Logout</a>
        <div style="width: 35px; height: 35px; background: #cbd5e1; border-radius: 50%;"></div>
    </div>
</header>