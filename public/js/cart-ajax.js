/**
 * cart-ajax.js - AJAX handlers for cart operations
 * No page reloads for add/increase/decrease/remove
 */

(function () {
    'use strict';

    // Use WEB routes for proper session/auth support
    const ROUTES = {
        add: '/cart/add',
        increase: '/cart/increase',
        decrease: '/cart/decrease',
        remove: '/cart/remove',
        count: '/api/cart/count'
    };

    // ========================================================================
    // INITIALIZE ON DOM READY
    // ========================================================================
    document.addEventListener('DOMContentLoaded', initCartAjax);

    function initCartAjax() {
        // Intercept all cart forms
        initCartForms();
        // Intercept add-to-cart buttons on product pages
        initAddToCartButtons();
        // Fetch initial cart count
        fetchCartCount();
    }

    // ========================================================================
    // CART PAGE FORMS
    // ========================================================================
    function initCartForms() {
        // Increase quantity forms
        document.querySelectorAll('form[action*="increase"], form[action*="inc"]').forEach(form => {
            if (form.closest('.cart-item, .cart-wrapper')) {
                form.addEventListener('submit', handleIncrease);
            }
        });

        // Decrease quantity forms
        document.querySelectorAll('form[action*="decrease"], form[action*="dec"]').forEach(form => {
            if (form.closest('.cart-item, .cart-wrapper')) {
                form.addEventListener('submit', handleDecrease);
            }
        });

        // Remove item forms
        document.querySelectorAll('form[action*="remove"], form[action*="rem"]').forEach(form => {
            if (form.closest('.cart-item, .cart-wrapper')) {
                form.addEventListener('submit', handleRemove);
            }
        });
    }

    // ========================================================================
    // ADD TO CART (Product pages, home, featured)
    // ========================================================================
    function initAddToCartButtons() {
        // Handle forms with add-to-cart actions
        document.querySelectorAll('form[action*="cart"][method="POST"]').forEach(form => {
            // Skip forms already handled (increase/decrease/remove)
            if (form.querySelector('input[name="increase"]') ||
                form.querySelector('input[name="decrease"]') ||
                form.querySelector('input[name="remove"]')) {
                return;
            }

            // This is an add-to-cart form
            if (form.querySelector('input[name="id"]')) {
                form.addEventListener('submit', handleAddToCart);
            }
        });
    }

    // ========================================================================
    // HANDLERS
    // ========================================================================
    async function handleAddToCart(e) {
        e.preventDefault();

        const form = e.target;
        const button = form.querySelector('button[type="submit"]');
        const watchId = form.querySelector('input[name="id"]')?.value;

        if (!watchId) return;

        // Check if user is logged in
        if (document.body.dataset.authenticated !== 'true') {
            WatchifyAjax.showToast('Please login to add items to cart', 'warning');
            setTimeout(() => {
                window.location.href = document.body.dataset.loginUrl || '/login';
            }, 1500);
            return;
        }

        WatchifyAjax.setLoading(button, true);

        try {
            const response = await WatchifyAjax.fetch(ROUTES.add, {
                body: { id: watchId }
            });

            if (response.success) {
                WatchifyAjax.showToast(response.message || 'Added to cart!', 'success');

                // Update cart badge
                if (response.cartCount !== undefined) {
                    WatchifyAjax.updateCartBadge(response.cartCount);
                } else {
                    fetchCartCount();
                }

                // Add pulse animation to button
                button.classList.add('ajax-pulse');
                setTimeout(() => button.classList.remove('ajax-pulse'), 600);
            } else {
                WatchifyAjax.showToast(response.message || 'Could not add to cart', 'error');
            }
        } catch (error) {
            WatchifyAjax.showToast(error.message || 'Failed to add to cart', 'error');
        } finally {
            WatchifyAjax.setLoading(button, false);
        }
    }

    async function handleIncrease(e) {
        e.preventDefault();

        const form = e.target;
        const button = form.querySelector('button[type="submit"]');
        const cartItemId = form.querySelector('input[name="increase"]')?.value;
        const cartItem = form.closest('.cart-item');

        if (!cartItemId) return;

        WatchifyAjax.setLoading(button, true);

        try {
            const response = await WatchifyAjax.fetch(ROUTES.increase, {
                body: { increase: cartItemId }
            });

            if (response.success) {
                WatchifyAjax.showToast(response.message || 'Quantity increased', 'success');

                // Update quantity display
                const qtyNumber = cartItem?.querySelector('.qty-number');
                if (qtyNumber && response.quantity !== undefined) {
                    qtyNumber.textContent = response.quantity;
                    qtyNumber.classList.add('qty-bump');
                    setTimeout(() => qtyNumber.classList.remove('qty-bump'), 300);
                }

                // Update subtotal
                if (response.subtotal !== undefined && cartItem) {
                    const subtotalEl = cartItem.querySelector('.item-subtotal');
                    if (subtotalEl) {
                        WatchifyAjax.animateNumber(subtotalEl, response.subtotal);
                    }
                }

                // Update total
                updateCartTotal(response.total);

                // Update cart badge
                WatchifyAjax.updateCartBadge(response.cartCount);
            } else {
                WatchifyAjax.showToast(response.message || 'Could not increase quantity', 'error');
                button.classList.add('ajax-shake');
                setTimeout(() => button.classList.remove('ajax-shake'), 500);
            }
        } catch (error) {
            WatchifyAjax.showToast(error.message || 'Failed to increase quantity', 'error');
        } finally {
            WatchifyAjax.setLoading(button, false);
        }
    }

    async function handleDecrease(e) {
        e.preventDefault();

        const form = e.target;
        const button = form.querySelector('button[type="submit"]');
        const cartItemId = form.querySelector('input[name="decrease"]')?.value;
        const cartItem = form.closest('.cart-item');

        if (!cartItemId) return;

        WatchifyAjax.setLoading(button, true);

        try {
            const response = await WatchifyAjax.fetch(ROUTES.decrease, {
                body: { decrease: cartItemId }
            });

            if (response.success) {
                WatchifyAjax.showToast(response.message || 'Quantity updated', 'success');

                // Check if item was removed (quantity was 1)
                if (response.removed && cartItem) {
                    WatchifyAjax.fadeOut(cartItem, () => {
                        checkEmptyCart();
                    });
                } else {
                    // Update quantity display
                    const qtyNumber = cartItem?.querySelector('.qty-number');
                    if (qtyNumber && response.quantity !== undefined) {
                        qtyNumber.textContent = response.quantity;
                        qtyNumber.classList.add('qty-bump');
                        setTimeout(() => qtyNumber.classList.remove('qty-bump'), 300);
                    }

                    // Update subtotal
                    if (response.subtotal !== undefined && cartItem) {
                        const subtotalEl = cartItem.querySelector('.item-subtotal');
                        if (subtotalEl) {
                            WatchifyAjax.animateNumber(subtotalEl, response.subtotal);
                        }
                    }
                }

                // Update total
                updateCartTotal(response.total);

                // Update item count
                updateItemCount(response.itemCount);

                // Update cart badge
                WatchifyAjax.updateCartBadge(response.cartCount);
            } else {
                WatchifyAjax.showToast(response.message || 'Could not decrease quantity', 'error');
            }
        } catch (error) {
            WatchifyAjax.showToast(error.message || 'Failed to decrease quantity', 'error');
        } finally {
            WatchifyAjax.setLoading(button, false);
        }
    }

    async function handleRemove(e) {
        e.preventDefault();

        const form = e.target;
        const button = form.querySelector('button[type="submit"]');
        const cartItemId = form.querySelector('input[name="remove"]')?.value;
        const cartItem = form.closest('.cart-item');

        if (!cartItemId) return;

        WatchifyAjax.setLoading(button, true);

        try {
            const response = await WatchifyAjax.fetch(ROUTES.remove, {
                body: { remove: cartItemId }
            });

            if (response.success) {
                WatchifyAjax.showToast(response.message || 'Item removed', 'success');

                // Animate item removal
                if (cartItem) {
                    WatchifyAjax.fadeOut(cartItem, () => {
                        checkEmptyCart();
                    });
                }

                // Update total
                updateCartTotal(response.total);

                // Update item count
                updateItemCount(response.itemCount);

                // Update cart badge
                WatchifyAjax.updateCartBadge(response.cartCount);
            } else {
                WatchifyAjax.showToast(response.message || 'Could not remove item', 'error');
            }
        } catch (error) {
            WatchifyAjax.showToast(error.message || 'Failed to remove item', 'error');
        } finally {
            WatchifyAjax.setLoading(button, false);
        }
    }

    // ========================================================================
    // HELPER FUNCTIONS
    // ========================================================================
    async function fetchCartCount() {
        try {
            const response = await WatchifyAjax.fetch(ROUTES.count, { method: 'GET' });
            if (response.success) {
                WatchifyAjax.updateCartBadge(response.count);
            }
        } catch (error) {
            // Silent fail for cart count
        }
    }

    function updateCartTotal(total) {
        if (total === undefined) return;

        const totalEl = document.querySelector('.total-row span:last-child, .cart-total');
        if (totalEl) {
            WatchifyAjax.animateNumber(totalEl, total);
        }
    }

    function updateItemCount(count) {
        if (count === undefined) return;

        const countEl = document.querySelector('.item-count');
        if (countEl) {
            countEl.textContent = `${count} Item${count !== 1 ? 's' : ''} Selected`;
        }
    }

    function checkEmptyCart() {
        const cartItems = document.querySelectorAll('.cart-item');
        if (cartItems.length === 0) {
            // Show empty cart state
            const cartWrapper = document.querySelector('.cart-wrapper');
            if (cartWrapper) {
                cartWrapper.innerHTML = `
                    <div class="empty-cart ajax-fade-in">
                        <h2>Your bag is empty</h2>
                        <p>Time stands still for no one. Start your collection today.</p>
                        <a href="/home" class="return-shop-link">Return to Shop</a>
                    </div>
                `;
            }
        }
    }

})();
