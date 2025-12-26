/**
 * admin-ajax.js - AJAX handlers for admin panel operations
 * Delete products, update orders, delete users without page reload
 */

(function () {
    'use strict';

    const API = {
        deleteProduct: '/api/admin/products/delete',
        updateOrder: '/api/admin/orders/update-status',
        deleteUser: '/api/admin/users/delete'
    };

    // ========================================================================
    // INITIALIZE ON DOM READY
    // ========================================================================
    document.addEventListener('DOMContentLoaded', initAdminAjax);

    function initAdminAjax() {
        initDeleteProductForms();
        initOrderStatusForms();
        initDeleteUserForms();
    }

    // ========================================================================
    // DELETE PRODUCT
    // ========================================================================
    function initDeleteProductForms() {
        document.querySelectorAll('form[action*="watches/delete"], form[action*="products/delete"]').forEach(form => {
            form.addEventListener('submit', handleDeleteProduct);
        });
    }

    async function handleDeleteProduct(e) {
        e.preventDefault();

        const form = e.target;
        const button = form.querySelector('button[type="submit"]');
        const productId = form.querySelector('input[name="id"]')?.value;
        const row = form.closest('tr, .product-row, .watch-card');

        if (!productId) return;

        if (!confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
            return;
        }

        WatchifyAjax.setLoading(button, true);

        try {
            const response = await WatchifyAjax.fetch(API.deleteProduct, {
                body: { id: productId }
            });

            if (response.success) {
                WatchifyAjax.showToast(response.message || 'Product deleted successfully!', 'success');

                // Animate row removal
                if (row) {
                    WatchifyAjax.fadeOut(row);
                }
            } else {
                WatchifyAjax.showToast(response.message || 'Failed to delete product', 'error');
            }
        } catch (error) {
            WatchifyAjax.showToast(error.message || 'Failed to delete product', 'error');
        } finally {
            WatchifyAjax.setLoading(button, false);
        }
    }

    // ========================================================================
    // UPDATE ORDER STATUS
    // ========================================================================
    function initOrderStatusForms() {
        document.querySelectorAll('form[action*="update-status"], form[action*="updateOrderStatus"]').forEach(form => {
            form.addEventListener('submit', handleUpdateOrderStatus);
        });

        // Also handle inline status selects
        document.querySelectorAll('select[name="status"]').forEach(select => {
            const form = select.closest('form');
            if (form) {
                // Will be handled by form submit
                return;
            }
            // Standalone select - handle change event
            select.addEventListener('change', handleStatusChange);
        });
    }

    async function handleUpdateOrderStatus(e) {
        e.preventDefault();

        const form = e.target;
        const button = form.querySelector('button[type="submit"]');
        const orderId = form.querySelector('input[name="order_id"]')?.value;
        const status = form.querySelector('select[name="status"]')?.value;
        const statusBadge = form.closest('tr')?.querySelector('.status-badge, .order-status');

        if (!orderId || !status) return;

        WatchifyAjax.setLoading(button, true);

        try {
            const response = await WatchifyAjax.fetch(API.updateOrder, {
                body: { order_id: orderId, status: status }
            });

            if (response.success) {
                WatchifyAjax.showToast(response.message || 'Order status updated!', 'success');

                // Update status badge if exists
                if (statusBadge && response.newStatus) {
                    updateStatusBadge(statusBadge, response.newStatus);
                }
            } else {
                WatchifyAjax.showToast(response.message || 'Failed to update status', 'error');
            }
        } catch (error) {
            WatchifyAjax.showToast(error.message || 'Failed to update status', 'error');
        } finally {
            WatchifyAjax.setLoading(button, false);
        }
    }

    async function handleStatusChange(e) {
        const select = e.target;
        const orderId = select.dataset.orderId;
        const status = select.value;
        const row = select.closest('tr');
        const statusBadge = row?.querySelector('.status-badge, .order-status');

        if (!orderId || !status) return;

        select.disabled = true;

        try {
            const response = await WatchifyAjax.fetch(API.updateOrder, {
                body: { order_id: orderId, status: status }
            });

            if (response.success) {
                WatchifyAjax.showToast(response.message || 'Status updated!', 'success');

                if (statusBadge && response.newStatus) {
                    updateStatusBadge(statusBadge, response.newStatus);
                }

                // Flash row to indicate update
                if (row) {
                    row.classList.add('ajax-pulse');
                    setTimeout(() => row.classList.remove('ajax-pulse'), 600);
                }
            } else {
                WatchifyAjax.showToast(response.message || 'Failed to update', 'error');
            }
        } catch (error) {
            WatchifyAjax.showToast(error.message || 'Failed to update', 'error');
        } finally {
            select.disabled = false;
        }
    }

    function updateStatusBadge(badge, status) {
        badge.textContent = status;
        badge.className = 'status-badge';

        const statusLower = status.toLowerCase();
        if (statusLower === 'pending') {
            badge.classList.add('status-pending');
            badge.style.background = '#fef3c7';
            badge.style.color = '#92400e';
        } else if (statusLower === 'shipped') {
            badge.classList.add('status-shipped');
            badge.style.background = '#dbeafe';
            badge.style.color = '#1e40af';
        } else if (statusLower === 'completed') {
            badge.classList.add('status-completed');
            badge.style.background = '#d1fae5';
            badge.style.color = '#065f46';
        } else if (statusLower === 'cancelled') {
            badge.classList.add('status-cancelled');
            badge.style.background = '#fee2e2';
            badge.style.color = '#991b1b';
        }
    }

    // ========================================================================
    // DELETE USER
    // ========================================================================
    function initDeleteUserForms() {
        document.querySelectorAll('form[action*="users/delete"]').forEach(form => {
            form.addEventListener('submit', handleDeleteUser);
        });
    }

    async function handleDeleteUser(e) {
        e.preventDefault();

        const form = e.target;
        const button = form.querySelector('button[type="submit"]');
        const userId = form.querySelector('input[name="id"]')?.value;
        const row = form.closest('tr, .user-row');

        if (!userId) return;

        if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            return;
        }

        WatchifyAjax.setLoading(button, true);

        try {
            const response = await WatchifyAjax.fetch(API.deleteUser, {
                body: { id: userId }
            });

            if (response.success) {
                WatchifyAjax.showToast(response.message || 'User deleted successfully!', 'success');

                // Animate row removal
                if (row) {
                    WatchifyAjax.fadeOut(row);
                }
            } else {
                WatchifyAjax.showToast(response.message || 'Failed to delete user', 'error');
            }
        } catch (error) {
            WatchifyAjax.showToast(error.message || 'Failed to delete user', 'error');
        } finally {
            WatchifyAjax.setLoading(button, false);
        }
    }

})();
