<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">

<style>
    /* Navbar Main Container */
    .watchify-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 8%;
        height: 90px;
        background: #ffffff;
        border-bottom: 1px solid #f2f2f2;
        position: sticky;
        top: 0;
        z-index: 1000;
        font-family: 'Inter', sans-serif;
    }

    /* Logo Styling */
    .nav-brand {
        font-size: 22px;
        font-weight: 900;
        color: #000;
        text-decoration: none;
        letter-spacing: 4px;
        text-transform: uppercase;
    }

    .nav-brand span {
        color: #a1a1a1;
        font-weight: 300;
    }

    /* Navigation Menu */
    .nav-menu {
        display: flex;
        gap: 35px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .nav-menu li a {
        text-decoration: none;
        color: #1a1a1a;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        position: relative;
        padding: 8px 0;
        transition: color 0.3s ease;
    }

    /* Elegant Underline Hover Interaction */
    .nav-menu li a::after {
        content: '';
        position: absolute;
        width: 0;
        height: 1px;
        bottom: 0;
        left: 0;
        background-color: #000;
        transition: width 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .nav-menu li a:hover::after {
        width: 100%;
    }

    /* User Actions & Auth Area */
    .nav-actions {
        display: flex;
        gap: 25px;
        align-items: center;
    }

    /* Consistent styling for "Welcome [Name]" */
    .user-greeting {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #888;
        margin: 0;
    }

    .login-btn {
        text-decoration: none;
        color: #1a1a1a;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        transition: color 0.3s ease;
    }

    .login-btn:hover {
        color: #000;
    }

    /* Call to Action Button */
    .signup-btn {
        padding: 12px 28px;
        font-size: 11px;
        font-weight: 800;
        background: #000;
        color: #fff;
        border: 1px solid #000;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 2px;
        transition: all 0.3s ease;
    }

    .signup-btn:hover {
        background: #fff;
        color: #000;
    }

    /* Responsive adjustments */
    @media (max-width: 1100px) {
        .watchify-nav { padding: 0 4%; }
        .nav-menu { gap: 20px; }
        .user-greeting { display: none; } /* Simplify on smaller screens */
    }
</style>

<nav class="watchify-nav">
    <a href="{{ url('/') }}" class="nav-brand">
        Watchify<span>Store</span>
    </a>
    
    <ul class="nav-menu">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('home') }}">Collections</a></li>
        <li><a href="{{ route('cartItems') }}">Cart</a></li> 

        @if(Auth::user())
            <li><a href="{{ route('myOrders') }}">My Orders</a></li> 
        @endif 
        
        <li><a href="{{ route('aboutUs') }}">About Us</a></li>
    </ul>

    <div class="nav-actions">
        @if(Auth::user())
            <span class="user-greeting">Welcome, {{ Auth::user()->name }}</span>
            <a href="{{ route('logout') }}" class="login-btn">Logout</a>
        @else
            <a href="{{ url('/login') }}" class="login-btn">Login</a>
            <a href="{{ url('/register') }}" class="signup-btn">Join Now</a>
        @endif
    </div>
</nav>