<link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">

<!-- Mobile Menu -->
<input type="checkbox" id="mobile-menu">
<label for="mobile-menu" class="menu-btn">
    <span></span>
    <span></span>
    <span></span>
</label>
<label for="mobile-menu" class="menu-overlay"></label>

<nav class="sidebar-nav">

    <div class="sidebar-brand">
        Watches Admin
    </div>

    <a href="{{ url('/admin/dashboard') }}"
       class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
        <span class="sidebar-text">Dashboard</span>
    </a>

    <a href="{{ url('/admin/watches') }}"
       class="nav-item {{ request()->is('admin/watches*') ? 'active' : '' }}">
        <span class="sidebar-text">Watches</span>
    </a>

    <a href="{{ route('allOrders') }}"
       class="nav-item {{ request()->is('admin/orders*') ? 'active' : '' }}">
        <span class="sidebar-text">Orders</span>
    </a>

    <a href="{{ route('allUsers') }}"
       class="nav-item {{ request()->is('admin/users*') ? 'active' : '' }}">
        <span class="sidebar-text">Users</span>
    </a>

</nav>