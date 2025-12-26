/**
 * search-ajax.js - Live search functionality
 * Search without page reload with instant results
 */

(function () {
    'use strict';

    const API = {
        search: '/api/search'
    };

    let searchTimeout = null;
    const DEBOUNCE_DELAY = 300;

    // ========================================================================
    // INITIALIZE ON DOM READY
    // ========================================================================
    document.addEventListener('DOMContentLoaded', initSearchAjax);

    function initSearchAjax() {
        initLiveSearch();
        initSearchForms();
        initSortFilters();
    }

    // ========================================================================
    // LIVE SEARCH (Navbar search)
    // ========================================================================
    function initLiveSearch() {
        const searchInputs = document.querySelectorAll('.search-input, input[name="query"]');

        searchInputs.forEach(input => {
            const form = input.closest('form');
            const container = input.closest('.search-container');

            // Create results dropdown
            let resultsDropdown = container?.querySelector('.search-results-dropdown');
            if (!resultsDropdown && container) {
                resultsDropdown = document.createElement('div');
                resultsDropdown.className = 'search-results-dropdown';
                container.appendChild(resultsDropdown);
            }

            // Live search on input
            input.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                const query = input.value.trim();

                if (query.length < 2) {
                    if (resultsDropdown) {
                        resultsDropdown.classList.remove('show');
                        resultsDropdown.innerHTML = '';
                    }
                    return;
                }

                searchTimeout = setTimeout(() => {
                    performLiveSearch(query, resultsDropdown);
                }, DEBOUNCE_DELAY);
            });

            // Hide dropdown on blur (with delay to allow clicking results)
            input.addEventListener('blur', () => {
                setTimeout(() => {
                    if (resultsDropdown) {
                        resultsDropdown.classList.remove('show');
                    }
                }, 200);
            });

            // Show dropdown on focus if has results
            input.addEventListener('focus', () => {
                if (resultsDropdown && resultsDropdown.innerHTML.trim()) {
                    resultsDropdown.classList.add('show');
                }
            });
        });
    }

    async function performLiveSearch(query, dropdown) {
        if (!dropdown) return;

        try {
            const response = await fetch(`${API.search}?query=${encodeURIComponent(query)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success && data.watches) {
                renderSearchResults(data.watches, dropdown, query);
            }
        } catch (error) {
            console.error('Search error:', error);
        }
    }

    function renderSearchResults(watches, dropdown, query) {
        if (watches.length === 0) {
            dropdown.innerHTML = `
                <div class="search-no-results">
                    No watches found for "${query}"
                </div>
            `;
        } else {
            const resultsHtml = watches.slice(0, 5).map(watch => `
                <a href="/shop/product?id=${watch.id}" class="search-result-item">
                    <div class="search-result-image">
                        <img src="${watch.image || '/images/placeholder-watch.jpg'}" alt="${watch.name}">
                    </div>
                    <div class="search-result-info">
                        <span class="search-result-name">${highlightMatch(watch.name, query)}</span>
                        <span class="search-result-price">Rs. ${formatPrice(watch.price)}</span>
                    </div>
                </a>
            `).join('');

            dropdown.innerHTML = resultsHtml;

            if (watches.length > 5) {
                dropdown.innerHTML += `
                    <a href="/shop/search?query=${encodeURIComponent(query)}" class="search-view-all">
                        View all ${watches.length} results →
                    </a>
                `;
            }
        }

        dropdown.classList.add('show');
    }

    function highlightMatch(text, query) {
        const regex = new RegExp(`(${escapeRegex(query)})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    }

    function escapeRegex(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    function formatPrice(price) {
        return parseFloat(price).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // ========================================================================
    // SEARCH PAGE FORM (Full page search)
    // ========================================================================
    function initSearchForms() {
        document.querySelectorAll('form[action*="search"]').forEach(form => {
            // Don't prevent default - let the form navigate to search results page
            // But enhance with loading state
            form.addEventListener('submit', (e) => {
                const button = form.querySelector('button[type="submit"]');
                const input = form.querySelector('input[name="query"]');

                if (!input?.value.trim()) {
                    e.preventDefault();
                    WatchifyAjax.showToast('Please enter a search term', 'warning');
                    return;
                }

                if (button) {
                    WatchifyAjax.setLoading(button, true);
                }
            });
        });
    }

    // ========================================================================
    // SORT FILTERS (On search results / home / featured pages)
    // ========================================================================
    function initSortFilters() {
        const sortSelects = document.querySelectorAll('select[name="sort"], .sort-select');

        sortSelects.forEach(select => {
            select.addEventListener('change', async (e) => {
                const sort = e.target.value;
                const currentUrl = new URL(window.location.href);
                const query = currentUrl.searchParams.get('query') || '';

                // Check if we're on a page that can be filtered via AJAX
                const productsGrid = document.querySelector('.products-grid, .watches-grid, .watch-grid');

                if (productsGrid && window.location.pathname.includes('/shop')) {
                    // AJAX filter
                    await performAjaxFilter(sort, query, productsGrid);
                } else {
                    // Navigate with sort parameter
                    currentUrl.searchParams.set('sort', sort);
                    window.location.href = currentUrl.toString();
                }
            });
        });
    }

    async function performAjaxFilter(sort, query, container) {
        WatchifyAjax.setContainerLoading(container, true);

        try {
            let url = `${API.search}?sort=${sort}`;
            if (query) {
                url += `&query=${encodeURIComponent(query)}`;
            }

            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success && data.watches) {
                renderProductGrid(data.watches, container);

                // Update URL without reload
                const newUrl = new URL(window.location.href);
                newUrl.searchParams.set('sort', sort);
                window.history.pushState({}, '', newUrl.toString());
            }
        } catch (error) {
            WatchifyAjax.showToast('Failed to filter products', 'error');
        } finally {
            WatchifyAjax.setContainerLoading(container, false);
        }
    }

    function renderProductGrid(watches, container) {
        if (watches.length === 0) {
            container.innerHTML = `
                <div class="no-products">
                    <p>No watches found</p>
                </div>
            `;
            return;
        }

        const html = watches.map(watch => `
            <div class="watch-card ajax-fade-in" data-watch-id="${watch.id}">
                <a href="/shop/product?id=${watch.id}" class="watch-image-link">
                    <img src="${watch.image || '/images/placeholder-watch.jpg'}" alt="${watch.name}" class="watch-image">
                    ${watch.featured === 'yes' ? '<span class="featured-badge">Featured</span>' : ''}
                </a>
                <div class="watch-info">
                    <h3 class="watch-name">${watch.name}</h3>
                    <p class="watch-price">Rs. ${formatPrice(watch.price)}</p>
                    <form action="/cart/add" method="POST" class="add-to-cart-form">
                        <input type="hidden" name="id" value="${watch.id}">
                        <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                    </form>
                </div>
            </div>
        `).join('');

        container.innerHTML = html;

        // Reinitialize cart AJAX for new buttons
        if (typeof initCartAjax === 'function') {
            initCartAjax();
        }
    }

})();
