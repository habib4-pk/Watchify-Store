/**
 * Product Validation JavaScript
 * Consolidated from home.blade.php, featured.blade.php, searchedResult.blade.php
 * Handles product form validation and add-to-cart functionality
 */

(function () {
    'use strict';

    /**
     * Get configuration from data attributes on the script tag or body
     */
    function getConfig() {
        const body = document.body;
        return {
            isAuthenticated: body.dataset.authenticated === 'true',
            loginUrl: body.dataset.loginUrl || '/login',
            placeholderImage: body.dataset.placeholderImage || '/images/placeholder-watch.jpg'
        };
    }

    /**
     * Validate the product details form before submission
     * @param {HTMLFormElement} form - The form to validate
     * @returns {boolean} Whether the form is valid
     */
    window.validateDetailsForm = function (form) {
        const idInput = form.querySelector('input[name="id"]');

        if (!idInput || !idInput.value) {
            alert('Invalid watch selection. Please try again.');
            return false;
        }

        const watchId = parseInt(idInput.value);
        if (isNaN(watchId) || watchId <= 0) {
            alert('Invalid watch ID. Please refresh the page.');
            return false;
        }

        return true;
    };

    /**
     * Validate add-to-cart form before submission
     * @param {HTMLFormElement} form - The form to validate
     * @param {Event} event - The submit event
     * @returns {boolean} Whether the form is valid
     */
    window.validateAddToCart = function (form, event) {
        const button = form.querySelector('button[type="submit"]');
        const idInput = form.querySelector('input[name="id"]');
        const config = getConfig();

        // Validate ID
        if (!idInput || !idInput.value) {
            event.preventDefault();
            alert('Invalid watch selection. Please try again.');
            return false;
        }

        const watchId = parseInt(idInput.value);
        if (isNaN(watchId) || watchId <= 0) {
            event.preventDefault();
            alert('Invalid watch ID. Please refresh the page.');
            return false;
        }

        // Check authentication
        if (!config.isAuthenticated) {
            event.preventDefault();
            if (confirm('Please login to add items to your cart. Would you like to login now?')) {
                window.location.href = config.loginUrl;
            }
            return false;
        }

        // Check if already processing
        if (button.disabled || button.classList.contains('btn-loading')) {
            event.preventDefault();
            return false;
        }

        // Add loading state
        button.disabled = true;
        button.classList.add('btn-loading');
        const originalText = button.getAttribute('data-original-text') || button.textContent;
        button.textContent = 'Adding...';

        // Re-enable after timeout
        setTimeout(function () {
            button.disabled = false;
            button.classList.remove('btn-loading');
            button.textContent = originalText;
        }, 5000);

        return true;
    };

    /**
     * Initialize product image error handling
     */
    function initImageErrorHandling() {
        const config = getConfig();
        const productImages = document.querySelectorAll('.product-image');

        productImages.forEach(img => {
            img.addEventListener('error', function () {
                this.src = config.placeholderImage;
                this.alt = 'Image not available';
            });
        });
    }

    /**
     * Prevent multiple rapid clicks on buttons
     */
    function initButtonProtection() {
        const allButtons = document.querySelectorAll('.btn-view, .btn-add:not(.btn-disabled)');
        allButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                if (this.disabled) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    }

    /**
     * Add keyboard accessibility to product cards
     */
    function initKeyboardAccessibility() {
        const productCards = document.querySelectorAll('.product-card');
        productCards.forEach(card => {
            card.addEventListener('keypress', function (e) {
                if (e.key === 'Enter' && e.target === this) {
                    const detailsButton = this.querySelector('.btn-view');
                    if (detailsButton) {
                        detailsButton.click();
                    }
                }
            });
        });
    }

    /**
     * Highlight search term in results (optional enhancement)
     */
    function initSearchHighlighting() {
        const searchQuery = document.body.dataset.searchQuery;
        if (searchQuery) {
            const productNames = document.querySelectorAll('.product-name');
            productNames.forEach(name => {
                const text = name.textContent;
                const regex = new RegExp(`(${searchQuery})`, 'gi');
                const highlightedText = text.replace(regex, '<mark style="background: #fff3cd; padding: 2px 4px;">$1</mark>');
                name.innerHTML = highlightedText;
            });
        }
    }

    /**
     * Initialize all product validation features
     */
    document.addEventListener('DOMContentLoaded', function () {
        initImageErrorHandling();
        initButtonProtection();
        initKeyboardAccessibility();
        initSearchHighlighting();
    });
})();
