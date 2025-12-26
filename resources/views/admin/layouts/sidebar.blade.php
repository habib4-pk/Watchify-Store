<!-- Sidebar Brand -->
<div class="p-4 border-bottom" style="border-color: #30363d !important;">
    <a href="{{ url('/admin/dashboard') }}" class="text-decoration-none">
        <h4 class="fw-bold text-white mb-0">
            <i class="bi bi-stopwatch text-primary me-2"></i>WatchAdmin
        </h4>
        <small class="text-secondary">E-commerce Dashboard</small>
    </a>
</div>

<!-- Navigation Menu -->
<nav class="p-3">
    <p class="text-uppercase text-secondary small fw-semibold mb-2 px-3">Main Menu</p>
    
    <ul class="nav flex-column gap-1">
        <li class="nav-item">
            <a href="{{ url('/admin/dashboard') }}" 
               class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 {{ request()->is('admin/dashboard') ? 'active bg-primary text-white' : 'text-secondary' }}"
               style="{{ request()->is('admin/dashboard') ? '' : 'transition: all 0.2s;' }}">
                <i class="bi bi-speedometer2 fs-5"></i>
                <span class="fw-medium">Dashboard</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ url('/admin/watches') }}" 
               class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 {{ request()->is('admin/watches*') ? 'active bg-primary text-white' : 'text-secondary' }}"
               style="{{ request()->is('admin/watches*') ? '' : 'transition: all 0.2s;' }}">
                <i class="bi bi-smartwatch fs-5"></i>
                <span class="fw-medium">Watches</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('allOrders') }}" 
               class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 {{ request()->is('admin/orders*') ? 'active bg-primary text-white' : 'text-secondary' }}"
               style="{{ request()->is('admin/orders*') ? '' : 'transition: all 0.2s;' }}">
                <i class="bi bi-bag-check fs-5"></i>
                <span class="fw-medium">Orders</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('allUsers') }}" 
               class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 {{ request()->is('admin/users*') ? 'active bg-primary text-white' : 'text-secondary' }}"
               style="{{ request()->is('admin/users*') ? '' : 'transition: all 0.2s;' }}">
                <i class="bi bi-people fs-5"></i>
                <span class="fw-medium">Users</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ url('/admin/banners') }}" 
               class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 {{ request()->is('admin/banners*') ? 'active bg-primary text-white' : 'text-secondary' }}"
               style="{{ request()->is('admin/banners*') ? '' : 'transition: all 0.2s;' }}">
                <i class="bi bi-images fs-5"></i>
                <span class="fw-medium">Hero Banners</span>
            </a>
        </li>
    </ul>
    
    <hr class="my-4" style="border-color: #30363d;">
    
    <p class="text-uppercase text-secondary small fw-semibold mb-2 px-3">Quick Links</p>
    
    <ul class="nav flex-column gap-1">
        <li class="nav-item">
            <a href="{{ url('/home') }}" target="_blank" class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-secondary">
                <i class="bi bi-globe fs-5"></i>
                <span class="fw-medium">View Store</span>
                <i class="bi bi-box-arrow-up-right ms-auto small"></i>
            </a>
        </li>
    </ul>
</nav>

<!-- Sidebar Footer -->
<div class="mt-auto p-3 border-top" style="border-color: #30363d !important;">
    <div class="d-flex align-items-center gap-3 p-2 rounded-3" style="background-color: #21262d;">
        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
        </div>
        <div class="flex-grow-1">
            <p class="text-white mb-0 fw-medium small">{{ Auth::user()->name ?? 'Admin' }}</p>
            <small class="text-secondary">Administrator</small>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-link text-secondary p-0" title="Logout">
                <i class="bi bi-box-arrow-right fs-5"></i>
            </button>
        </form>
    </div>
</div>