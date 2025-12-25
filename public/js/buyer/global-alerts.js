/**
 * Global Alerts JavaScript
 * Extracted from buyer/layout.blade.php
 * Handles alert dismissal, auto-timeout, and form double-submission prevention
 */

(function () {
    'use strict';

    /**
     * Close an alert by ID with fade-out animation
     * @param {string} alertId - The ID of the alert element to close
     */
    window.closeAlert = function (alertId) {
        const alert = document.getElementById(alertId);
        if (alert) {
            alert.classList.add('fade-out');
            setTimeout(() => {
                alert.remove();
            }, 400);
        }
    };

    /**
     * Initialize alert auto-dismiss and form protection on DOM ready
     */
    document.addEventListener('DOMContentLoaded', function () {
        // Auto-dismiss alerts after 5 seconds
        const alerts = document.querySelectorAll('.global-alert');

        alerts.forEach(alert => {
            setTimeout(() => {
                if (alert && document.body.contains(alert)) {
                    alert.classList.add('fade-out');
                    setTimeout(() => {
                        if (alert && document.body.contains(alert)) {
                            alert.remove();
                        }
                    }, 400);
                }
            }, 5000);
        });

        // Prevent form double submission globally
        const forms = document.querySelectorAll('form');

        forms.forEach(form => {
            form.addEventListener('submit', function (e) {
                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton && !submitButton.disabled) {
                    submitButton.disabled = true;
                    const originalText = submitButton.textContent;
                    submitButton.textContent = 'Processing...';

                    // Re-enable after 3 seconds in case of validation errors
                    setTimeout(() => {
                        submitButton.disabled = false;
                        submitButton.textContent = originalText;
                    }, 3000);
                }
            });
        });
    });
})();
