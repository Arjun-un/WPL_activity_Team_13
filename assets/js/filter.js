/**
 * ============================================================================
 * Dynamic Client-Side Product Filtering
 * Filters catalog items on the fly by title, brand, or price without page reload
 * ============================================================================
 */

document.addEventListener('DOMContentLoaded', function () {
    const liveSearchInput = document.getElementById('catalog-live-search');
    const productCards = document.querySelectorAll('.product-card[data-product-card]');
    const noResultsMsg = document.getElementById('no-results-alert');
    const visibleCountEl = document.getElementById('visible-products-count');

    if (!liveSearchInput || !productCards.length) return;

    liveSearchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();
        let visibleCount = 0;

        productCards.forEach(card => {
            const title = (card.getAttribute('data-title') || '').toLowerCase();
            const brand = (card.getAttribute('data-brand') || '').toLowerCase();
            const category = (card.getAttribute('data-category') || '').toLowerCase();

            // Match if query is contained in title, brand, or category
            if (title.includes(query) || brand.includes(query) || category.includes(query)) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (visibleCountEl) {
            visibleCountEl.textContent = visibleCount;
        }

        if (noResultsMsg) {
            if (visibleCount === 0) {
                noResultsMsg.style.display = 'block';
            } else {
                noResultsMsg.style.display = 'none';
            }
        }
    });
});
