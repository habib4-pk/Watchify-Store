/**
 * auth-ajax.js - AJAX handlers for authentication
 * Login, register, logout without page reloads
 */

(function () {
    'use strict';

    // Use WEB routes (not API) because auth needs full session support
    const ROUTES = {
        login: '/account/login',
        register: '/account/register',
        logout: '/logout'
    };

    // ========================================================================
    // INITIALIZE ON DOM READY
    // ========================================================================
    document.addEventListener('DOMContentLoaded', initAuthAjax);

    function initAuthAjax() {
        initLoginForm();
        initRegisterForm();
        initLogoutForms();
    }

    // ========================================================================
    // LOGIN FORM
    // ========================================================================
    function initLoginForm() {
        const loginForm = document.querySelector('form[action*="login"][method="POST"]');
        if (!loginForm) return;

        // Skip if it's the navbar logout form
        if (loginForm.querySelector('input[name="email"]')) {
            loginForm.addEventListener('submit', handleLogin);
        }
    }

    async function handleLogin(e) {
        e.preventDefault();

        const form = e.target;
        const button = form.querySelector('button[type="submit"]');
        const emailInput = form.querySelector('input[name="email"]');
        const passwordInput = form.querySelector('input[name="password"]');

        // Clear previous errors
        clearFormErrors(form);

        // Basic validation
        let hasErrors = false;

        if (!emailInput.value.trim()) {
            showFieldError(emailInput, 'Email is required');
            hasErrors = true;
        } else if (!isValidEmail(emailInput.value)) {
            showFieldError(emailInput, 'Please enter a valid email');
            hasErrors = true;
        }

        if (!passwordInput.value) {
            showFieldError(passwordInput, 'Password is required');
            hasErrors = true;
        }

        if (hasErrors) return;

        WatchifyAjax.setLoading(button, true);

        try {
            const response = await WatchifyAjax.fetch(ROUTES.login, {
                body: {
                    email: emailInput.value,
                    password: passwordInput.value,
                    remember: form.querySelector('input[name="remember"]')?.checked || false
                }
            });

            if (response.success) {
                // Queue toast to show after redirect (don't show now - redirect is too fast)
                WatchifyAjax.queueToast(response.message || 'Login successful!', 'success');

                // Immediate redirect
                window.location.href = response.redirect || '/shop';
            } else {
                WatchifyAjax.showToast(response.message || 'Login failed', 'error');

                // Show field-specific errors
                if (response.errors) {
                    Object.keys(response.errors).forEach(field => {
                        const input = form.querySelector(`input[name="${field}"]`);
                        if (input) {
                            showFieldError(input, response.errors[field][0]);
                        }
                    });
                }

                form.classList.add('ajax-shake');
                setTimeout(() => form.classList.remove('ajax-shake'), 500);
            }
        } catch (error) {
            WatchifyAjax.showToast(error.message || 'Login failed. Please try again.', 'error');
        } finally {
            WatchifyAjax.setLoading(button, false);
        }
    }

    // ========================================================================
    // REGISTER FORM
    // ========================================================================
    function initRegisterForm() {
        const registerForm = document.querySelector('form[action*="register"][method="POST"]');
        if (!registerForm) return;

        if (registerForm.querySelector('input[name="name"]')) {
            registerForm.addEventListener('submit', handleRegister);

            // Real-time password validation
            const passwordInput = registerForm.querySelector('input[name="password"]');
            const confirmInput = registerForm.querySelector('input[name="password_confirmation"]');

            if (passwordInput) {
                passwordInput.addEventListener('input', () => {
                    validatePasswordStrength(passwordInput);
                });
            }

            if (confirmInput && passwordInput) {
                confirmInput.addEventListener('input', () => {
                    validatePasswordMatch(passwordInput, confirmInput);
                });
            }
        }
    }

    async function handleRegister(e) {
        e.preventDefault();

        const form = e.target;
        const button = form.querySelector('button[type="submit"]');
        const nameInput = form.querySelector('input[name="name"]');
        const emailInput = form.querySelector('input[name="email"]');
        const passwordInput = form.querySelector('input[name="password"]');
        const confirmInput = form.querySelector('input[name="password_confirmation"]');

        // Clear previous errors
        clearFormErrors(form);

        // Validation
        let hasErrors = false;

        if (!nameInput.value.trim()) {
            showFieldError(nameInput, 'Name is required');
            hasErrors = true;
        }

        if (!emailInput.value.trim()) {
            showFieldError(emailInput, 'Email is required');
            hasErrors = true;
        } else if (!isValidEmail(emailInput.value)) {
            showFieldError(emailInput, 'Please enter a valid email');
            hasErrors = true;
        }

        if (!passwordInput.value) {
            showFieldError(passwordInput, 'Password is required');
            hasErrors = true;
        } else if (passwordInput.value.length < 8) {
            showFieldError(passwordInput, 'Password must be at least 8 characters');
            hasErrors = true;
        }

        if (passwordInput.value !== confirmInput.value) {
            showFieldError(confirmInput, 'Passwords do not match');
            hasErrors = true;
        }

        if (hasErrors) return;

        WatchifyAjax.setLoading(button, true);

        try {
            const response = await WatchifyAjax.fetch(ROUTES.register, {
                body: {
                    name: nameInput.value,
                    email: emailInput.value,
                    password: passwordInput.value,
                    password_confirmation: confirmInput.value
                }
            });

            if (response.success) {
                // Queue toast to show after redirect
                WatchifyAjax.queueToast(response.message || 'Registration successful!', 'success');

                // Immediate redirect
                window.location.href = response.redirect || '/shop';
            } else {
                WatchifyAjax.showToast(response.message || 'Registration failed', 'error');

                // Show field-specific errors
                if (response.errors) {
                    Object.keys(response.errors).forEach(field => {
                        const input = form.querySelector(`input[name="${field}"]`);
                        if (input) {
                            showFieldError(input, response.errors[field][0]);
                        }
                    });
                }

                form.classList.add('ajax-shake');
                setTimeout(() => form.classList.remove('ajax-shake'), 500);
            }
        } catch (error) {
            WatchifyAjax.showToast(error.message || 'Registration failed. Please try again.', 'error');
        } finally {
            WatchifyAjax.setLoading(button, false);
        }
    }

    // ========================================================================
    // LOGOUT FORMS
    // ========================================================================
    function initLogoutForms() {
        document.querySelectorAll('form[action*="logout"]').forEach(form => {
            form.addEventListener('submit', handleLogout);
        });
    }

    async function handleLogout(e) {
        e.preventDefault();

        const form = e.target;
        const button = form.querySelector('button[type="submit"]');

        // Save original text and show loading with text (not just spinner)
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="ajax-spinner" style="width:14px;height:14px;margin-right:6px;"></span> Logging out...';

        try {
            const response = await WatchifyAjax.fetch(ROUTES.logout, {});

            if (response.success) {
                // Queue toast for after redirect
                WatchifyAjax.queueToast(response.message || 'Logged out successfully', 'success');
                window.location.href = response.redirect || '/shop';
            } else {
                // Fallback - just redirect
                WatchifyAjax.queueToast('Logged out', 'success');
                window.location.href = '/shop';
            }
        } catch (error) {
            // Fallback - just redirect
            WatchifyAjax.queueToast('Logged out', 'success');
            window.location.href = '/shop';
        }
    }

    // ========================================================================
    // HELPER FUNCTIONS
    // ========================================================================
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

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

    function validatePasswordStrength(input) {
        const password = input.value;

        // Remove existing strength indicator
        let strengthEl = input.parentElement.querySelector('.password-strength');
        if (!strengthEl) {
            strengthEl = document.createElement('div');
            strengthEl.className = 'password-strength';
            input.parentElement.appendChild(strengthEl);
        }

        if (password.length === 0) {
            strengthEl.textContent = '';
            return;
        }

        let strength = 0;
        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
        if (password.match(/\d/)) strength++;
        if (password.match(/[^a-zA-Z\d]/)) strength++;

        const labels = ['Weak', 'Fair', 'Good', 'Strong'];
        const colors = ['#ef4444', '#f59e0b', '#3b82f6', '#10b981'];

        strengthEl.textContent = labels[strength - 1] || 'Too short';
        strengthEl.style.color = colors[strength - 1] || '#ef4444';
    }

    function validatePasswordMatch(passwordInput, confirmInput) {
        if (confirmInput.value && passwordInput.value !== confirmInput.value) {
            confirmInput.classList.add('ajax-input-error');
        } else {
            confirmInput.classList.remove('ajax-input-error');
        }
    }

})();
