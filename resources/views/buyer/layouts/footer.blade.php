<style>
    /* Premium Luxury Footer */
    .watch-footer {
        background: #0a0a0a; /* Deeper black for high contrast */
        color: #ffffff;
        padding: 100px 8% 40px; /* Increased vertical breathing room */
        margin-top: 120px;
        font-family: 'Inter', sans-serif;
        border-top: 1px solid #1a1a1a;
    }

    .footer-wrapper {
        max-width: 1400px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr 1.2fr; /* Asymmetric grid for visual interest */
        gap: 60px;
        padding-bottom: 80px;
    }

    /* Column Header Styling */
    .footer-title {
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2.5px; /* High tracking for luxury feel */
        margin-bottom: 35px;
        color: #fff;
    }

    /* Brand Column Specifics */
    .footer-brand h2 {
        font-size: 22px;
        font-weight: 900;
        letter-spacing: 3px;
        margin-bottom: 20px;
        text-transform: uppercase;
    }

    .footer-brand h2 span {
        font-weight: 300;
        color: #666;
    }

    .footer-brand p {
        color: #888;
        font-size: 14px;
        line-height: 1.8;
        max-width: 300px;
    }

    /* List Styling */
    .footer-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-list li {
        margin-bottom: 15px;
    }

    .footer-list a {
        color: #888;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        letter-spacing: 0.5px;
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        display: inline-block;
    }

    .footer-list a:hover {
        color: #ffffff;
        transform: translateX(5px); /* Smooth subtle slide */
    }

    /* Newsletter/Contact Styling */
    .footer-contact p {
        font-size: 13px;
        color: #888;
        margin-bottom: 20px;
    }

    .footer-email-link {
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        border-bottom: 1px solid #333;
        padding-bottom: 5px;
        transition: border-color 0.3s;
    }

    .footer-email-link:hover {
        border-color: #fff;
    }

    /* Bottom Copyright Bar */
    .footer-base {
        max-width: 1400px;
        margin: 0 auto;
        padding-top: 40px;
        border-top: 1px solid #1a1a1a;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .copyright {
        color: #555;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .social-links {
        display: flex;
        gap: 25px;
    }

    .social-links a {
        color: #555;
        font-size: 12px;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        transition: color 0.3s;
    }

    .social-links a:hover {
        color: #fff;
    }

    /* Responsive Grid */
    @media (max-width: 1024px) {
        .footer-wrapper {
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }
    }

    @media (max-width: 600px) {
        .footer-wrapper {
            grid-template-columns: 1fr;
        }
        .footer-base {
            flex-direction: column;
            gap: 20px;
            text-align: center;
        }
    }
</style>

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