/**
 * checkout-ajax.js - AJAX handlers for checkout form
 * Submit order without page reload
 */

(function () {
    'use strict';

    const API = {
        placeOrder: '/api/checkout/place-order'
    };

    // ========================================================================
    // INITIALIZE ON DOM READY
    // ========================================================================
    document.addEventListener('DOMContentLoaded', initCheckoutAjax);

    function initCheckoutAjax() {
        initCheckoutForm();
        initFormValidation();
    }

    // ========================================================================
    // CHECKOUT FORM
    // ========================================================================
    function initCheckoutForm() {
        const checkoutForms = document.querySelectorAll('form[action*="checkout"], form[action*="placeOrder"]');
        checkoutForms.forEach(form => {
            if (form.querySelector('input[name="customer_name"]')) {
                form.addEventListener('submit', handleCheckoutSubmit);
            }
        });
    }

    function initFormValidation() {
        // Real-time validation on blur
        const inputs = document.querySelectorAll('.checkout-form input, .checkout-form textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', () => validateField(input));
            input.addEventListener('input', () => {
                // Remove error state when user starts typing
                input.classList.remove('ajax-input-error');
                const errorEl = input.parentElement.querySelector('.ajax-error-message');
                if (errorEl) errorEl.remove();
            });
        });
    }

    function validateField(input) {
        const name = input.name;
        const value = input.value.trim();
        let error = null;

        switch (name) {
            case 'customer_name':
                if (!value) error = 'Customer name is required';
                else if (value.length < 3) error = 'Name must be at least 3 characters';
                else if (!/^[a-zA-Z\s]+$/.test(value)) error = 'Name can only contain letters and spaces';
                break;
            case 'street_address':
                if (!value) error = 'Street address is required';
                else if (value.length < 10) error = 'Address must be at least 10 characters';
                break;
            case 'city':
                if (!value) error = 'City is required';
                else if (!/^[a-zA-Z\s]+$/.test(value)) error = 'City can only contain letters and spaces';
                break;
            case 'postal_code':
                if (!value) error = 'Postal code is required';
                else if (!/^[a-zA-Z0-9\s-]+$/.test(value)) error = 'Invalid postal code format';
                break;
            case 'phone_number':
                if (!value) error = 'Phone number is required';
                else if (value.length < 10) error = 'Phone must be at least 10 digits';
                else if (!/^[0-9+\-\s()]+$/.test(value)) error = 'Invalid phone number format';
                break;
        }

        if (error) {
            showFieldError(input, error);
            return false;
        }
        return true;
    }

    async function handleCheckoutSubmit(e) {
        e.preventDefault();

        const form = e.target;
        const button = form.querySelector('button[type="submit"]');

        // Clear previous errors
        clearFormErrors(form);

        // Validate all fields
        const inputs = form.querySelectorAll('input[required]');
        let hasErrors = false;
        inputs.forEach(input => {
            if (!validateField(input)) {
                hasErrors = true;
            }
        });

        if (hasErrors) {
            WatchifyAjax.showToast('Please fix the form errors', 'warning');
            form.classList.add('ajax-shake');
            setTimeout(() => form.classList.remove('ajax-shake'), 500);
            return;
        }

        WatchifyAjax.setLoading(button, true);

        try {
            const formData = {
                customer_name: form.querySelector('input[name="customer_name"]')?.value,
                street_address: form.querySelector('input[name="street_address"]')?.value ||
                    form.querySelector('textarea[name="street_address"]')?.value,
                city: form.querySelector('input[name="city"]')?.value,
                postal_code: form.querySelector('input[name="postal_code"]')?.value,
                phone_number: form.querySelector('input[name="phone_number"]')?.value
            };

            const response = await WatchifyAjax.fetch(API.placeOrder, {
                body: formData
            });

            if (response.success) {
                WatchifyAjax.showToast(response.message || 'Order placed successfully!', 'success');

                // Show success animation
                showOrderSuccess(response.orderId);

                // Update cart badge to 0
                WatchifyAjax.updateCartBadge(0);

                // Redirect after delay
                setTimeout(() => {
                    window.location.href = response.redirect || '/orders';
                }, 2000);
            } else {
                WatchifyAjax.showToast(response.message || 'Could not place order', 'error');

                // Show field-specific errors
                if (response.errors) {
                    Object.keys(response.errors).forEach(field => {
                        const input = form.querySelector(`input[name="${field}"], textarea[name="${field}"]`);
                        if (input) {
                            showFieldError(input, response.errors[field][0]);
                        }
                    });
                }
            }
        } catch (error) {
            WatchifyAjax.showToast(error.message || 'Failed to place order', 'error');
        } finally {
            WatchifyAjax.setLoading(button, false);
        }
    }

    // ========================================================================
    // HELPER FUNCTIONS
    // ========================================================================
    function clearFormErrors(form) {
        form.querySelectorAll('.ajax-input-error').forEach(el => {
            el.classList.remove('ajax-input-error');
        });
        form.querySelectorAll('.ajax-error-message').forEach(el => {
            el.remove();
        });
    }

    function showFieldError(input, message) {
        input.classList.add('ajax-input-error');

        // Remove existing error message
        const existingError = input.parentElement.querySelector('.ajax-error-message');
        if (existingError) existingError.remove();

        // Add new error message
        const errorEl = document.createElement('div');
        errorEl.className = 'ajax-error-message';
        errorEl.textContent = message;
        input.parentElement.appendChild(errorEl);
    }

    function showOrderSuccess(orderId) {
        // Create success overlay
        const overlay = document.createElement('div');
        overlay.className = 'order-success-overlay';
        overlay.innerHTML = `
            <div class="order-success-content">
                <div class="success-checkmark">
                    <svg viewBox="0 0 52 52" class="checkmark-svg">
                        <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
                        <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                    </svg>
                </div>
                <h2>Order Confirmed!</h2>
                <p>Order #${orderId || ''}</p>
                <p class="redirect-text">Redirecting to your orders...</p>
            </div>
        `;
        document.body.appendChild(overlay);

        // Animate in
        requestAnimationFrame(() => {
            overlay.classList.add('show');
        });
    }

})();
