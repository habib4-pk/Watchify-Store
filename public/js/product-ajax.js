/**
 * product-ajax.js - AJAX handlers for product page
 * Add to cart, reviews without page reload
 */

(function () {
    'use strict';

    // Use WEB routes for proper session support
    const ROUTES = {
        reviewStore: '/reviews/store',
        reviewDelete: '/reviews/delete'
    };

    // ========================================================================
    // INITIALIZE ON DOM READY
    // ========================================================================
    document.addEventListener('DOMContentLoaded', initProductAjax);

    function initProductAjax() {
        initReviewForms();
        initDeleteReviewButtons();
    }

    // ========================================================================
    // REVIEW FORM
    // ========================================================================
    function initReviewForms() {
        const reviewForms = document.querySelectorAll('form[action*="review/store"], form[action*="reviews/store"]');
        reviewForms.forEach(form => {
            form.addEventListener('submit', handleReviewSubmit);
        });

        // Star rating interaction
        initStarRating();
    }

    function initStarRating() {
        const starContainers = document.querySelectorAll('.star-rating-input, .rating-stars');
        starContainers.forEach(container => {
            const stars = container.querySelectorAll('.star, [data-rating]');
            const ratingInput = container.querySelector('input[name="rating"]') ||
                container.closest('form')?.querySelector('input[name="rating"]');

            stars.forEach((star, index) => {
                star.style.cursor = 'pointer';

                star.addEventListener('click', () => {
                    const rating = star.dataset.rating || (index + 1);
                    if (ratingInput) {
                        ratingInput.value = rating;
                    }
                    updateStarDisplay(container, rating);
                });

                star.addEventListener('mouseenter', () => {
                    const rating = star.dataset.rating || (index + 1);
                    updateStarDisplay(container, rating, true);
                });
            });

            container.addEventListener('mouseleave', () => {
                const currentRating = ratingInput?.value || 0;
                updateStarDisplay(container, currentRating);
            });
        });
    }

    function updateStarDisplay(container, rating, isHover = false) {
        const stars = container.querySelectorAll('.star, [data-rating]');
        stars.forEach((star, index) => {
            const starRating = star.dataset.rating || (index + 1);
            if (starRating <= rating) {
                star.classList.add('active', 'filled');
                star.style.color = '#fbbf24';
            } else {
                star.classList.remove('active', 'filled');
                star.style.color = isHover ? '#d1d5db' : '#e5e7eb';
            }
        });
    }

    async function handleReviewSubmit(e) {
        e.preventDefault();

        const form = e.target;
        const button = form.querySelector('button[type="submit"]');
        const watchId = form.querySelector('input[name="watch_id"]')?.value;
        const rating = form.querySelector('input[name="rating"]')?.value ||
            form.querySelector('select[name="rating"]')?.value;
        const comment = form.querySelector('textarea[name="comment"]')?.value ||
            form.querySelector('input[name="comment"]')?.value;

        // Check if user is logged in
        if (document.body.dataset.authenticated !== 'true') {
            WatchifyAjax.showToast('Please login first', 'info');
            setTimeout(() => {
                window.location.href = document.body.dataset.loginUrl || '/account/login';
            }, 1000);
            return;
        }

        if (!rating) {
            WatchifyAjax.showToast('Please select a rating', 'warning');
            return;
        }

        WatchifyAjax.setLoading(button, true);

        try {
            const response = await WatchifyAjax.fetch(ROUTES.reviewStore, {
                body: {
                    watch_id: watchId,
                    rating: rating,
                    comment: comment || ''
                }
            });

            if (response.success) {
                WatchifyAjax.showToast(response.message || 'Review submitted!', 'success');

                // Update average rating display
                if (response.avgRating !== undefined) {
                    updateAverageRating(response.avgRating, response.reviewCount);
                }

                // Add or update the review in the list
                if (!response.isUpdate) {
                    addReviewToList(response.review);
                } else {
                    updateReviewInList(response.review);
                }

                // Clear the form
                form.reset();
                const starContainer = form.querySelector('.star-rating-input, .rating-stars');
                if (starContainer) {
                    updateStarDisplay(starContainer, 0);
                }
            } else {
                WatchifyAjax.showToast(response.message || 'Could not submit review', 'error');
            }
        } catch (error) {
            WatchifyAjax.showToast(error.message || 'Failed to submit review', 'error');
        } finally {
            WatchifyAjax.setLoading(button, false);
        }
    }

    // ========================================================================
    // DELETE REVIEW
    // ========================================================================
    function initDeleteReviewButtons() {
        document.querySelectorAll('form[action*="review/delete"], form[action*="reviews/delete"]').forEach(form => {
            form.addEventListener('submit', handleReviewDelete);
        });

        // Also handle delete buttons/links
        document.querySelectorAll('.delete-review-btn, [data-delete-review]').forEach(btn => {
            btn.addEventListener('click', handleReviewDelete);
        });
    }

    async function handleReviewDelete(e) {
        e.preventDefault();

        const target = e.target.closest('form') || e.target.closest('[data-delete-review]');
        const button = target.querySelector('button[type="submit"]') || e.target;
        const reviewId = target.querySelector('input[name="review_id"]')?.value ||
            target.dataset.reviewId;
        const reviewEl = target.closest('.review-item, .review-card, [data-review-id]');

        if (!reviewId) return;

        if (!confirm('Are you sure you want to delete this review?')) {
            return;
        }

        WatchifyAjax.setLoading(button, true);

        try {
            const response = await WatchifyAjax.fetch(ROUTES.reviewDelete, {
                body: { review_id: reviewId }
            });

            if (response.success) {
                WatchifyAjax.showToast(response.message || 'Review deleted', 'success');

                // Update average rating display
                if (response.avgRating !== undefined) {
                    updateAverageRating(response.avgRating, response.reviewCount);
                }

                // Remove the review from the list
                if (reviewEl) {
                    WatchifyAjax.fadeOut(reviewEl);
                }
            } else {
                WatchifyAjax.showToast(response.message || 'Could not delete review', 'error');
            }
        } catch (error) {
            WatchifyAjax.showToast(error.message || 'Failed to delete review', 'error');
        } finally {
            WatchifyAjax.setLoading(button, false);
        }
    }

    // ========================================================================
    // HELPER FUNCTIONS
    // ========================================================================
    function updateAverageRating(avgRating, reviewCount) {
        // Update rating number
        const ratingEls = document.querySelectorAll('.avg-rating, .product-rating-value, [data-avg-rating]');
        ratingEls.forEach(el => {
            el.textContent = avgRating.toFixed(1);
        });

        // Update review count
        const countEls = document.querySelectorAll('.review-count, [data-review-count]');
        countEls.forEach(el => {
            el.textContent = `${reviewCount} review${reviewCount !== 1 ? 's' : ''}`;
        });

        // Update star visualization
        const starDisplays = document.querySelectorAll('.product-rating-stars, .rating-display');
        starDisplays.forEach(display => {
            updateRatingStars(display, avgRating);
        });
    }

    function updateRatingStars(container, rating) {
        const stars = container.querySelectorAll('.star, [data-star]');
        stars.forEach((star, index) => {
            const starPosition = index + 1;
            if (rating >= starPosition) {
                star.classList.add('filled');
                star.style.color = '#fbbf24';
            } else if (rating > starPosition - 1) {
                // Partial star
                star.classList.add('half');
            } else {
                star.classList.remove('filled', 'half');
                star.style.color = '#e5e7eb';
            }
        });
    }

    function addReviewToList(review) {
        const reviewsContainer = document.querySelector('.reviews-list, .reviews-container, #reviews');
        if (!reviewsContainer) return;

        // Remove "no reviews" message if present
        const noReviews = reviewsContainer.querySelector('.no-reviews');
        if (noReviews) noReviews.remove();

        const reviewHtml = createReviewElement(review);
        reviewsContainer.insertAdjacentHTML('afterbegin', reviewHtml);

        // Animate in
        const newReview = reviewsContainer.firstElementChild;
        if (newReview) {
            WatchifyAjax.fadeIn(newReview);
        }

        // Reinit delete handler for new review
        const deleteForm = newReview?.querySelector('form[action*="review/delete"], form[action*="reviews/delete"]');
        if (deleteForm) {
            deleteForm.addEventListener('submit', handleReviewDelete);
        }
    }

    function updateReviewInList(review) {
        const reviewEl = document.querySelector(`[data-review-id="${review.id}"], .review-item[data-id="${review.id}"]`);
        if (reviewEl) {
            // Update content
            const ratingEl = reviewEl.querySelector('.review-rating, [data-rating]');
            const commentEl = reviewEl.querySelector('.review-comment, .review-text');

            if (ratingEl) ratingEl.textContent = '★'.repeat(review.rating) + '☆'.repeat(5 - review.rating);
            if (commentEl) commentEl.textContent = review.comment;

            // Flash to indicate update
            reviewEl.classList.add('ajax-pulse');
            setTimeout(() => reviewEl.classList.remove('ajax-pulse'), 600);
        }
    }

    function createReviewElement(review) {
        const stars = '★'.repeat(review.rating) + '☆'.repeat(5 - review.rating);
        return `
            <div class="review-item" data-review-id="${review.id}">
                <div class="review-header">
                    <div class="reviewer-avatar">${review.user.initial}</div>
                    <div class="reviewer-info">
                        <span class="reviewer-name">${review.user.name}</span>
                        <span class="review-date">${review.created_at}</span>
                    </div>
                    <div class="review-rating" style="color: #fbbf24;">${stars}</div>
                </div>
                ${review.comment ? `<p class="review-comment">${review.comment}</p>` : ''}
                <form action="/reviews/delete" method="POST" class="delete-review-form">
                    <input type="hidden" name="review_id" value="${review.id}">
                    <button type="submit" class="delete-review-btn">Delete</button>
                </form>
            </div>
        `;
    }

})();
