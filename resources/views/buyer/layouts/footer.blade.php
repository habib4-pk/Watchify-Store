<link rel="stylesheet" href="{{ secure_asset('css/footer.css') }}">

<footer class="watch-footer">
    <div class="footer-wrapper">
        <div class="footer-brand">
            <h2>Watchify<span>Store</span></h2>
            <p>Curating exceptional timepieces for those who value precision, heritage, and the art of horology.</p>
        </div>

        <div class="footer-col">
            <h4 class="footer-title">Navigation</h4>
            <ul class="footer-list">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('featured') }}">Featured</a></li>
                <li><a href="{{ route('cartItems') }}">Shopping Bag</a></li>
                <li><a href="{{ route('aboutUs') }}">About Us</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4 class="footer-title">Account</h4>
            <ul class="footer-list">
                <li><a href="{{ route('myOrders') }}">My Orders</a></li>
                <li><a href="{{ route('cartItems') }}">View Cart</a></li>
                <li><a href="{{ route('home') }}">Shop Now</a></li>
                <li><a href="{{ route('aboutUs') }}">Contact Us</a></li>
            </ul>
        </div>

        <div class="footer-col footer-contact">
            <h4 class="footer-title">Get in Touch</h4>
            <p>Have questions regarding our collection? Our concierge is here to help.</p>
            <a href="mailto:umernisar053@gmail.com" class="footer-email-link">umernisar0532@gmail.com</a>
        </div>
    </div>

    <div class="footer-base">
        <div class="copyright">
            &copy; {{ date('Y') }} Watchify Store. Crafted for Elegance.
        </div>
        <div class="social-links">
            <a href="https://www.instagram.com/umer667781/" target="_blank">Instagram</a>
            <a href="https://github.com/UmerDevHub" target="_blank">Github</a>
            <a href="https://twitter.com" target="_blank">X (Twitter)</a>
        </div>
    </div>
</footer>