<link rel="stylesheet" href="{{ asset('css/footer.css') }}">

<footer class="watch-footer">
    <div class="footer-wrapper">
        <div class="footer-brand">
            <h2>Watchify<span>Store</span></h2>
            <p>Curating exceptional timepieces for those who value precision, heritage, and the art of horology.</p>
        </div>

        <div class="footer-col">
            <h4 class="footer-title">Navigation</h4>
            <ul class="footer-list">
                <li><a href="{{ url('/') }}">Collections</a></li>
                <li><a href="#">Limited Editions</a></li>
                <li><a href="{{ route('cartItems') }}">Shopping Bag</a></li>
                <li><a href="#">Our Story</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4 class="footer-title">Assistance</h4>
            <ul class="footer-list">
                <li><a href="#">Shipping & Taxes</a></li>
                <li><a href="#">Returns & Exchanges</a></li>
                <li><a href="#">Warranty Info</a></li>
                <li><a href="#">Track Order</a></li>
            </ul>
        </div>

        <div class="footer-col footer-contact">
            <h4 class="footer-title">Get in Touch</h4>
            <p>Have questions regarding our collection? Our concierge is here to help.</p>
            <a href="mailto:support@watchify.com" class="footer-email-link">support@watchify.com</a>
        </div>
    </div>

    <div class="footer-base">
        <div class="copyright">
            &copy; {{ date('Y') }} Watchify Store. Crafted for Elegance.
        </div>
        <div class="social-links">
            <a href="#">Instagram</a>
            <a href="#">Pinterest</a>
            <a href="#">X (Twitter)</a>
        </div>
    </div>
</footer>