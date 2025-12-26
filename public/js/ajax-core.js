/**
 * ajax-core.js - Core AJAX utilities for Watchify SPA-like experience
 * 
 * Provides:
 * - fetchWithCsrf() - AJAX requests with CSRF token
 * - showToast() - Toast notification system
 * - updateCartBadge() - Navbar cart count update
 * - Loading state management
 */

(function () {
    'use strict';

    // ========================================================================
    // CONFIGURATION
    // ========================================================================
    const CONFIG = {
        toastDuration: 4000,
        animationDuration: 300
    };

    // ========================================================================
    // CSRF TOKEN HANDLING
    // ========================================================================
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    // ========================================================================
    // FETCH WITH CSRF - Core AJAX function
    // ========================================================================
    async function fetchWithCsrf(url, options = {}) {
        const defaultOptions = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        };

        const mergedOptions = {
            ...defaultOptions,
            ...options,
            headers: {
                ...defaultOptions.headers,
                ...(options.headers || {})
            }
        };

        // Handle FormData - don't set Content-Type, let browser set it with boundary
        if (options.body instanceof FormData) {
            delete mergedOptions.headers['Content-Type'];
            mergedOptions.body = options.body;
        } else if (options.body && typeof options.body === 'object') {
            mergedOptions.body = JSON.stringify(options.body);
        }

        try {
            const response = await fetch(url, mergedOptions);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Request failed');
            }

            return data;
        } catch (error) {
            console.error('Fetch error:', error);
            throw error;
        }
    }

    // ========================================================================
    // TOAST NOTIFICATION SYSTEM
    // ========================================================================
    function createToastContainer() {
        let container = document.getElementById('ajax-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'ajax-toast-container';
            container.className = 'ajax-toast-container';
            document.body.appendChild(container);
        }
        return container;
    }

    function showToast(message, type = 'success') {
        const container = createToastContainer();

        const toast = document.createElement('div');
        toast.className = `ajax-toast ajax-toast-${type}`;

        const icons = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'ℹ'
        };

        toast.innerHTML = `
            <span class="ajax-toast-icon">${icons[type] || icons.info}</span>
            <span class="ajax-toast-message">${message}</span>
            <button class="ajax-toast-close" onclick="this.parentElement.remove()">×</button>
        `;

        container.appendChild(toast);

        // Trigger animation
        requestAnimationFrame(() => {
            toast.classList.add('ajax-toast-show');
        });

        // Auto-dismiss
        setTimeout(() => {
            toast.classList.remove('ajax-toast-show');
            setTimeout(() => toast.remove(), CONFIG.animationDuration);
        }, CONFIG.toastDuration);

        return toast;
    }

    // ========================================================================
    // CART BADGE UPDATE
    // ========================================================================
    function updateCartBadge(count) {
        const badges = document.querySelectorAll('.cart-badge, .cart-count');
        badges.forEach(badge => {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'flex' : 'none';
        });

        // Also update any cart links that might have count embedded
        const cartLinks = document.querySelectorAll('.cart-link');
        cartLinks.forEach(link => {
            let badge = link.querySelector('.cart-badge');
            if (!badge && count > 0) {
                badge = document.createElement('span');
                badge.className = 'cart-badge';
                link.appendChild(badge);
            }
            if (badge) {
                badge.textContent = count;
                badge.style.display = count > 0 ? 'flex' : 'none';
            }
        });
    }

    // ========================================================================
    // LOADING STATE MANAGEMENT
    // ========================================================================
    function setLoading(element, isLoading) {
        if (isLoading) {
            element.classList.add('ajax-loading');
            element.disabled = true;
            element.dataset.originalContent = element.innerHTML;

            // Add spinner
            const spinner = document.createElement('span');
            spinner.className = 'ajax-spinner';
            element.innerHTML = '';
            element.appendChild(spinner);
        } else {
            element.classList.remove('ajax-loading');
            element.disabled = false;
            if (element.dataset.originalContent) {
                element.innerHTML = element.dataset.originalContent;
                delete element.dataset.originalContent;
            }
        }
    }

    // Function to set loading on a container
    function setContainerLoading(container, isLoading) {
        if (isLoading) {
            container.classList.add('ajax-container-loading');
            const overlay = document.createElement('div');
            overlay.className = 'ajax-loading-overlay';
            overlay.innerHTML = '<span class="ajax-spinner ajax-spinner-lg"></span>';
            container.appendChild(overlay);
        } else {
            container.classList.remove('ajax-container-loading');
            const overlay = container.querySelector('.ajax-loading-overlay');
            if (overlay) overlay.remove();
        }
    }

    // ========================================================================
    // FORM DATA HELPER
    // ========================================================================
    function formToObject(form) {
        const formData = new FormData(form);
        const obj = {};
        formData.forEach((value, key) => {
            obj[key] = value;
        });
        return obj;
    }

    // ========================================================================
    // ELEMENT ANIMATIONS
    // ========================================================================
    function fadeOut(element, callback) {
        element.style.transition = `opacity ${CONFIG.animationDuration}ms ease, transform ${CONFIG.animationDuration}ms ease`;
        element.style.opacity = '0';
        element.style.transform = 'translateX(20px)';

        setTimeout(() => {
            element.style.height = element.offsetHeight + 'px';
            element.style.overflow = 'hidden';

            requestAnimationFrame(() => {
                element.style.transition = `height ${CONFIG.animationDuration}ms ease, margin ${CONFIG.animationDuration}ms ease, padding ${CONFIG.animationDuration}ms ease`;
                element.style.height = '0';
                element.style.marginTop = '0';
                element.style.marginBottom = '0';
                element.style.paddingTop = '0';
                element.style.paddingBottom = '0';

                setTimeout(() => {
                    if (callback) callback();
                    element.remove();
                }, CONFIG.animationDuration);
            });
        }, CONFIG.animationDuration);
    }

    function fadeIn(element) {
        element.style.opacity = '0';
        element.style.transform = 'translateY(-10px)';

        requestAnimationFrame(() => {
            element.style.transition = `opacity ${CONFIG.animationDuration}ms ease, transform ${CONFIG.animationDuration}ms ease`;
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        });
    }

    // ========================================================================
    // NUMBER ANIMATION
    // ========================================================================
    function animateNumber(element, newValue) {
        const current = parseFloat(element.textContent.replace(/[^0-9.-]/g, '')) || 0;
        const target = parseFloat(newValue);
        const duration = 300;
        const startTime = performance.now();
        const isPrice = element.textContent.includes('Rs.');

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            // Easing function
            const easeOutQuad = progress * (2 - progress);
            const currentValue = current + (target - current) * easeOutQuad;

            if (isPrice) {
                element.textContent = 'Rs. ' + currentValue.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            } else {
                element.textContent = Math.round(currentValue);
            }

            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }

        requestAnimationFrame(update);
    }

    // ========================================================================
    // EXPORT TO GLOBAL SCOPE
    // ========================================================================
    window.WatchifyAjax = {
        fetch: fetchWithCsrf,
        showToast,
        updateCartBadge,
        setLoading,
        setContainerLoading,
        formToObject,
        fadeOut,
        fadeIn,
        animateNumber,
        getCsrfToken,
        CONFIG
    };

})();
