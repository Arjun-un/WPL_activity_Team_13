<?php
/**
 * ============================================================================
 * Page 2: Product Catalogue (catalog.php)
 * Demonstrates:
 * - Querying MongoDB with filters (search query, category)
 * - PHP Server-Side Associative Array Processing & Selectable Sorting (usort)
 * - Dynamic client-side live filtering (filter.js)
 * ============================================================================
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';

// Capture GET filters
$selected_category = isset($_GET['cat']) ? trim($_GET['cat']) : '';
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$sort_by = isset($_GET['sort']) ? trim($_GET['sort']) : 'featured';

// Build MongoDB query filter
$filter = [];
if (!empty($selected_category)) {
    $filter['category'] = $selected_category;
}
if (!empty($search_query)) {
    // Regex case-insensitive search across title or brand
    $filter['$or'] = [
        ['title' => new MongoDB\BSON\Regex($search_query, 'i')],
        ['brand' => new MongoDB\BSON\Regex($search_query, 'i')],
        ['category' => new MongoDB\BSON\Regex($search_query, 'i')]
    ];
}

// Fetch products from MongoDB
$products = get_db_records('products', $filter);

// ============================================================================
// ON-SPOT MODIFICATION SPOT #6: PHP ASSOCIATIVE ARRAY SORTING
// Evaluator may ask to alter sorting behavior (e.g. sort by stock, name, etc.)
// ============================================================================
if (!empty($products)) {
    switch ($sort_by) {
        case 'price_asc': // Price: Low to High
            usort($products, function ($a, $b) {
                return $a['price'] <=> $b['price'];
            });
            break;

        case 'price_desc': // Price: High to Low
            usort($products, function ($a, $b) {
                return $b['price'] <=> $a['price'];
            });
            break;

        case 'rating_desc': // Customer Rating
            usort($products, function ($a, $b) {
                return $b['rating'] <=> $a['rating'];
            });
            break;

        case 'discount_desc': // Biggest Discount
            usort($products, function ($a, $b) {
                return $b['discount_percent'] <=> $a['discount_percent'];
            });
            break;

        default: // Default: Latest / Featured
            // Keep native DB order
            break;
    }
}

// Extract distinct categories from catalog for sidebar filter
$all_categories = ['Men Fashion', 'Women Fashion', 'Watches', 'Footwear', 'Electronics', 'Luxury'];
?>

<main class="main-wrapper">

    <!-- Catalog Header & Breadcrumb -->
    <div style="margin-bottom: 20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
        <div>
            <h1 style="font-size: 1.6rem; font-weight: 800;">
                <?= !empty($selected_category) ? htmlspecialchars($selected_category) : 'All Collections'; ?>
                <?php if (!empty($search_query)): ?>
                    <span style="font-size:1rem; color:var(--cliq-gray); font-weight:normal;">— Search for "<?= htmlspecialchars($search_query); ?>"</span>
                <?php endif; ?>
            </h1>
            <p style="font-size:0.85rem; color:var(--cliq-gray); margin-top:4px;">
                Showing <strong id="visible-products-count"><?= count($products); ?></strong> luxury products
            </p>
        </div>

        <!-- Controls: Client Live Search + PHP Selectable Sort Form -->
        <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
            <!-- Real-Time Client Filter (filter.js) -->
            <input 
                type="text" 
                id="catalog-live-search" 
                placeholder="Instant filter on page..." 
                class="form-control" 
                style="width: 220px; padding: 7px 12px; font-size: 0.85rem;"
                data-help="Instant client-side filter without reloading the page"
            >

            <!-- Server-Side Selectable Sorting Form -->
            <form method="GET" action="catalog.php" style="display:flex; align-items:center; gap:6px;">
                <?php if (!empty($selected_category)): ?>
                    <input type="hidden" name="cat" value="<?= htmlspecialchars($selected_category); ?>">
                <?php endif; ?>
                <?php if (!empty($search_query)): ?>
                    <input type="hidden" name="q" value="<?= htmlspecialchars($search_query); ?>">
                <?php endif; ?>

                <label for="sort-select" style="font-size: 0.85rem; font-weight: 600;">Sort By:</label>
                <select name="sort" id="sort-select" class="form-control" style="width: auto; padding: 7px 10px; font-size: 0.85rem;" onchange="this.form.submit()">
                    <option value="featured" <?= $sort_by == 'featured' ? 'selected' : ''; ?>>Featured / Popular</option>
                    <option value="price_asc" <?= $sort_by == 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                    <option value="price_desc" <?= $sort_by == 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                    <option value="rating_desc" <?= $sort_by == 'rating_desc' ? 'selected' : ''; ?>>Customer Rating</option>
                    <option value="discount_desc" <?= $sort_by == 'discount_desc' ? 'selected' : ''; ?>>Discount & Offers</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Layout: Left Category Pill Filters + Main Grid -->
    <div style="display:flex; gap:8px; margin-bottom:24px; overflow-x:auto; padding-bottom:6px;">
        <a href="catalog.php" class="btn-secondary" style="padding:6px 14px; font-size:0.8rem; border-radius:20px; <?= empty($selected_category) ? 'background:var(--cliq-dark); color:#fff;' : ''; ?>">
            All (<?= count(get_db_records('products')); ?>)
        </a>
        <?php foreach ($all_categories as $cat): ?>
            <a href="catalog.php?cat=<?= urlencode($cat); ?>" class="btn-secondary" style="padding:6px 14px; font-size:0.8rem; border-radius:20px; <?= $selected_category === $cat ? 'background:var(--cliq-dark); color:#fff;' : ''; ?>">
                <?= htmlspecialchars($cat); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Product Grid -->
    <div class="product-grid" id="catalog-products-container">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $p): ?>
                <div 
                    class="product-card" 
                    data-product-card 
                    data-title="<?= htmlspecialchars($p['title']); ?>" 
                    data-brand="<?= htmlspecialchars($p['brand']); ?>"
                    data-category="<?= htmlspecialchars($p['category']); ?>"
                    data-price="<?= $p['price']; ?>"
                >
                    <a href="product_details.php?id=<?= $p['_id']; ?>" class="product-img-wrapper">
                        <img src="<?= htmlspecialchars($p['image']); ?>" alt="<?= htmlspecialchars($p['title']); ?>" loading="lazy">
                        <div class="product-rating">
                            <span class="star">&#9733;</span> <?= $p['rating']; ?> | <?= $p['reviews_count']; ?>
                        </div>
                    </a>
                    <div class="product-info">
                        <div class="product-brand"><?= htmlspecialchars($p['brand']); ?></div>
                        <a href="product_details.php?id=<?= $p['_id']; ?>" class="product-title" title="<?= htmlspecialchars($p['title']); ?>">
                            <?= htmlspecialchars($p['title']); ?>
                        </a>
                        
                        <div class="price-row">
                            <span class="current-price">&#8377;<?= number_format($p['price']); ?></span>
                            <span class="original-mrp">&#8377;<?= number_format($p['mrp']); ?></span>
                            <span class="discount-badge"><?= $p['discount_percent']; ?>% OFF</span>
                        </div>

                        <!-- Mandatory Stock Alert Logic -->
                        <div class="stock-indicator">
                            <?php if ($p['stock'] == 0): ?>
                                <span class="out-of-stock">&cross; Out of Stock</span>
                            <?php elseif ($p['stock'] <= 3): ?>
                                <span class="low-stock">&#9888; Only <?= $p['stock']; ?> left - Hurry!</span>
                            <?php else: ?>
                                <span class="in-stock">&check; In Stock (<?= $p['stock']; ?> available)</span>
                            <?php endif; ?>
                        </div>

                        <div class="card-actions">
                            <?php if ($p['stock'] > 0): ?>
                                <a href="cart.php?action=add&id=<?= $p['_id']; ?>" class="btn-add-cart">
                                    + Add To Cart
                                </a>
                            <?php else: ?>
                                <button class="btn-add-cart" disabled>Out of Stock</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1/-1; background:#fff; padding:40px; text-align:center; border-radius:8px; border:1px solid #e0e0e0;">
                <h3>No products matched your selection.</h3>
                <p style="margin:10px 0 20px; color:#666;">Try adjusting your search query or reset your filters.</p>
                <a href="catalog.php" class="btn-primary">View All Products</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- No results alert for client-side filter -->
    <div id="no-results-alert" style="display:none; background:#fff; padding:30px; text-align:center; border-radius:8px; border:1px solid #e0e0e0; margin-bottom:30px;">
        <p>No products match your live search keyword.</p>
    </div>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

