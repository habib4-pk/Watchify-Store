<style>
    .sidebar-brand { padding: 25px; font-size: 20px; font-weight: bold; border-bottom: 1px solid #334155; color: #38bdf8; }
    .sidebar-nav { padding: 20px 0; }
    .nav-item { display: flex; align-items: center; padding: 12px 25px; color: #94a3b8; text-decoration: none; transition: 0.3s; }
    .nav-item:hover, .nav-item.active { background: #334155; color: white; border-left: 4px solid #38bdf8; }
    .nav-item i { margin-right: 15px; width: 20px; }
</style>

<div class="sidebar-brand">Watches Admin</div>
<nav class="sidebar-nav">
    <a href="{{ url('/admin/dashboard') }}" class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
        <span class="sidebar-text">Dashboard</span>
    </a>
    <a href="{{ url('/admin/watches') }}" class="nav-item {{ request()->is('admin/watches*') ? 'active' : '' }}">
        <span class="sidebar-text">Watches</span>
    </a>
    <a href="{{ route('allOrders') }}" class="nav-item {{ request()->is('admin/orders*') ? 'active' : '' }}">
        <span class="sidebar-text">Orders</span>
    </a>
    <a href="{{ route('allUsers') }}" class="nav-item {{ request()->is('admin/users*') ? 'active' : '' }}">
        <span class="sidebar-text">Users</span>
    </a>
</nav>