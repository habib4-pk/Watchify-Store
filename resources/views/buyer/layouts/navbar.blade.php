<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/navbar.css') }}">




<input type="checkbox" id="nav-toggle">

<nav class="watchify-nav">
    <a href="{{ url('/home') }}" class="nav-brand">
        W<span>.</span>
    </a>

   \
    <label for="nav-toggle" class="nav-hamburger">
        <span></span>
        <span></span>
        <span></span>
    </label>

    <ul class="nav-menu">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('featured') }}">Featured</a></li>

        @if(Auth::user())
            <li><a href="{{ route('myOrders') }}">My Orders</a></li>
        @endif

        <li><a href="{{ route('aboutUs') }}">About</a></li>

        <li class="mobile-only">
            <form action="{{route('search')}}" method="GET" class="search-container">
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

        @if(Auth::user())
            <li class="mobile-only"><span class="user-greeting">{{ Auth::user()->name }}</span></li>
            <li class="mobile-only">
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="mobile-auth-link" style="background: none; border: none; cursor: pointer; padding: 0; font: inherit; color: inherit;">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>
                </form>
            </li>
        @else
            <li class="mobile-only"><a href="{{ url('/login') }}" class="mobile-auth-link">Login</a></li>
            <li class="mobile-only"><a href="{{ url('/register') }}" class="mobile-auth-link signup">Join Now</a></li>
        @endif
    </ul>

    <div class="nav-actions">
        @if(Auth::user())
            <a href="{{ route('cartItems') }}" class="cart-link">
                Cart
            </a>
        @endif

        <form action="{{route('search')}}" method="GET" class="search-container">
            <input
                type="text"
                name="query"
                placeholder="Search watches..."
                class="search-input">
            <button type="submit" class="search-submit">
                <i class="fas fa-search"></i>
            </button>
        </form>

        @if(Auth::user())
            <span class="user-greeting">{{ Auth::user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <input type="submit" value="Logout" class="login-btn" style="background:none; border:none; cursor:pointer; padding:0;">
                <i class="fas fa-sign-out-alt"></i>
            </form>
        @else
            <a href="{{ url('/login') }}" class="login-btn">Login</a>
            <a href="{{ url('/register') }}" class="signup-btn">Join Now</a>
        @endif
    </div>
</nav>

<label for="nav-toggle" class="nav-overlay"></label>
