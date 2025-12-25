<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/navbar.css') }}">

<input type="checkbox" id="nav-toggle">

<nav class="watchify-nav">
    <!-- Logo -->
    <a href="{{ url('/home') }}" class="nav-brand">
        W<span>.</span>
    </a>

    <!-- Desktop Navigation Links -->
    <ul class="nav-menu-desktop">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('featured') }}">Featured</a></li>
        @if(Auth::check())
            <li><a href="{{ route('myOrders') }}">My Orders</a></li>
        @endif
        <li><a href="{{ route('aboutUs') }}">About</a></li>
    </ul>

    <!-- Right Side Actions -->
    <div class="nav-actions">
        <!-- Search (Desktop Only) -->
        <form action="{{ route('search') }}" method="GET" class="search-container desktop-only">
            <input type="text" name="query" placeholder="Search watches..." class="search-input">
            <button type="submit" class="search-submit">
                <i class="fas fa-search"></i>
            </button>
        </form>

        <!-- Cart Icon (Always Visible) -->
        @if(Auth::check())
            <a href="{{ route('cartItems') }}" class="cart-link" title="View Cart">
                <i class="fas fa-shopping-cart"></i>
            </a>
        @endif

        <!-- User Info (Desktop Only) -->
        @if(Auth::check())
            <div class="user-info desktop-only">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <span class="user-name">{{ Auth::user()->name }}</span>
            </div>
            
            <form action="{{ route('logout') }}" method="POST" class="desktop-only">
                @csrf
                <button type="submit" class="logout-btn" title="Logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        @else
            <a href="{{ url('/login') }}" class="login-btn desktop-only">Login</a>
            <a href="{{ url('/register') }}" class="signup-btn desktop-only">Join Now</a>
        @endif

        <!-- Hamburger Menu (Mobile Only) -->
        <label for="nav-toggle" class="nav-hamburger">
            <span></span>
            <span></span>
            <span></span>
        </label>
    </div>
</nav>

<!-- Mobile Slide-out Menu -->
<div class="mobile-menu">
    <div class="mobile-menu-header">
        <label for="nav-toggle" class="nav-close-btn">
            <i class="fas fa-times"></i>
        </label>
    </div>

    <ul class="mobile-nav-links">
        <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="{{ route('featured') }}"><i class="fas fa-star"></i> Featured</a></li>
        @if(Auth::check())
            <li><a href="{{ route('myOrders') }}"><i class="fas fa-box"></i> My Orders</a></li>
        @endif
        <li><a href="{{ route('aboutUs') }}"><i class="fas fa-info-circle"></i> About</a></li>
        
        @if(Auth::check())
            <li><a href="{{ route('cartItems') }}"><i class="fas fa-shopping-cart"></i> Cart</a></li>
        @endif
    </ul>

    <form action="{{ route('search') }}" method="GET" class="mobile-search">
        <input type="text" name="query" placeholder="Search watches..." class="search-input">
        <button type="submit" class="search-submit">
            <i class="fas fa-search"></i>
        </button>
    </form>

    @if(Auth::check())
        <div class="mobile-user-section">
            <div class="mobile-user-info">
                <div class="user-avatar-mobile">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <span class="user-greeting">{{ Auth::user()->name }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="mobile-logout-form">
                @csrf
                <button type="submit" class="logout-btn-mobile">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    @else
        <div class="mobile-auth-section">
            <a href="{{ url('/login') }}" class="mobile-auth-link"><i class="fas fa-sign-in-alt"></i> Login</a>
            <a href="{{ url('/register') }}" class="mobile-auth-link signup"><i class="fas fa-user-plus"></i> Join Now</a>
        </div>
    @endif
</div>

<label for="nav-toggle" class="nav-overlay"></label>
