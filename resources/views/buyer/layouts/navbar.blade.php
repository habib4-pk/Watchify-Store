<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/navbar.css') }}">

<input type="checkbox" id="nav-toggle">

<nav class="watchify-nav">
    <a href="{{ url('/home') }}" class="nav-brand">
        W<span>.</span>
    </a>

    <label for="nav-toggle" class="nav-hamburger">
        <span></span>
        <span></span>
        <span></span>
    </label>

    <ul class="nav-menu">
        <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="{{ route('featured') }}"><i class="fas fa-star"></i> Featured</a></li>

        @if(Auth::check())
            <li><a href="{{ route('myOrders') }}"><i class="fas fa-box"></i> My Orders</a></li>
        @endif

        <li><a href="{{ route('aboutUs') }}"><i class="fas fa-info-circle"></i> About</a></li>

        <!-- Mobile Cart Link -->
        @if(Auth::check())
            <li class="mobile-only">
                <a href="{{ route('cartItems') }}" class="cart-link-mobile">
                    <i class="fas fa-shopping-cart"></i> 
                    Cart
               
                </a>
            </li>
        @endif

        <li class="mobile-only">
            <form action="{{ route('search') }}" method="GET" class="search-container">
                <input
                    type="text"
                    name="query"
                    placeholder="Search watches..."
                    class="search-input">
                <button type="submit" class="search-submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </li>

        @if(Auth::check())
            <li class="mobile-only mobile-user-section">
                <div class="mobile-user-info">
                    <div class="user-avatar-mobile">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="user-greeting">{{ Auth::user()->name }}</span>
                </div>
            </li>
            <li class="mobile-only">
                <form action="{{ route('logout') }}" method="POST" class="mobile-logout-form">
                    @csrf
                    <button type="submit" class="mobile-auth-link logout-mobile">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        @else
            <li class="mobile-only"><a href="{{ url('/login') }}" class="mobile-auth-link"><i class="fas fa-sign-in-alt"></i> Login</a></li>
            <li class="mobile-only"><a href="{{ url('/register') }}" class="mobile-auth-link signup"><i class="fas fa-user-plus"></i> Join Now</a></li>
        @endif
    </ul>

    <div class="nav-actions">
        <form action="{{ route('search') }}" method="GET" class="search-container">
            <input
                type="text"
                name="query"
                placeholder="Search watches..."
                class="search-input">
            <button type="submit" class="search-submit">
                <i class="fas fa-search"></i>
            </button>
        </form>

        @if(Auth::check())
            <a href="{{ route('cartItems') }}" class="cart-link" title="View Cart">
                <i class="fas fa-shopping-cart"></i>
                
            </a>

            <div class="user-dropdown">
                <button class="user-toggle" type="button" onclick="toggleUserDropdown(event)">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="user-dropdown-menu">
                    <div class="dropdown-header">
                        <span class="user-email">{{ Auth::user()->email }}</span>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('myOrders') }}" class="dropdown-item">
                        <i class="fas fa-box"></i> My Orders
                    </a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="dropdown-item logout-item">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ url('/login') }}" class="login-btn">Login</a>
            <a href="{{ url('/register') }}" class="signup-btn">Join Now</a>
        @endif
    </div>
</nav>

<label for="nav-toggle" class="nav-overlay"></label>
