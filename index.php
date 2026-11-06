<?php
/**
 * ============================================================================
 * Page 1: Homepage (index.php)
 * Authentic Tata CLiQ design with Hero Banner, Category Shortcuts,
 * Featured Brands, and Trending Products fetched dynamically from MongoDB.
 * ============================================================================
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';

// Fetch top 4 featured deals from MongoDB
$featured_products = get_db_records('products', [], ['sort' => ['discount_percent' => -1], 'limit' => 4]);
?>

<main class="main-wrapper">

    <!-- Hero Promotional Banner -->
    <section class="hero-banner">
        <div class="hero-content">
            <h1>The Grand <span>CLiQ</span> Fest</h1>
            <p>Experience authentic luxury with 100% verified brands. Enjoy up to 50% discount on watches, fashion, footwear, and consumer audio.</p>
            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <a href="catalog.php" class="btn-primary" data-help="Browse the full catalog with live filters">Explore Full Catalogue &rarr;</a>
                <a href="interaction_demo.php" class="btn-secondary" data-help="Visit syllabus-required mouse coordinate & panel demonstration">View Interaction Demo &starf;</a>
            </div>
        </div>
        <div style="font-size: 5rem; opacity: 0.85;">
            &#127873;
        </div>
    </section>

    <!-- Top Categories -->
    <div class="section-title">
        <h2>Shop By Curated Category</h2>
        <a href="catalog.php">View All &rarr;</a>
    </div>

    <div class="category-cards-grid">
        <a href="catalog.php?cat=Men+Fashion" class="category-card" data-help="Explore shirts, denims and formal wear">
            <div class="cat-icon">&#128084;</div>
            <h3>Men's Fashion</h3>
            <span>Up to 45% Off</span>
        </a>
        <a href="catalog.php?cat=Women+Fashion" class="category-card" data-help="Explore kurtas, dresses and festive sets">
            <div class="cat-icon">&#128087;</div>
            <h3>Women's Ethnic</h3>
            <span>Trending Styles</span>
        </a>
        <a href="catalog.php?cat=Watches" class="category-card" data-help="Explore Titan, Casio and Fossil timepieces">
            <div class="cat-icon">&#9201;</div>
            <h3>Watches & Smart</h3>
            <span>Titan, Fossil, Casio</span>
        </a>
        <a href="catalog.php?cat=Footwear" class="category-card" data-help="Explore Puma sneakers and Woodland boots">
            <div class="cat-icon">&#128095;</div>
            <h3>Footwear</h3>
            <span>Casuals & Boots</span>
        </a>
        <a href="catalog.php?cat=Electronics" class="category-card" data-help="Explore Sony headphones and Apple AirPods">
            <div class="cat-icon">&#127911;</div>
            <h3>Audio & Gadgets</h3>
            <span>Sony, Apple & More</span>
        </a>
        <a href="catalog.php?cat=Luxury" class="category-card" data-help="Explore pure 22K gold jewelry & diamonds">
            <div class="cat-icon">&#128142;</div>
            <h3>Luxury & Jewelry</h3>
            <span>Tata Tanishq Trust</span>
        </a>
    </div>

    <!-- Featured Products from MongoDB -->
    <div class="section-title">
        <h2>Trending Deals on Tata CLiQ</h2>
        <a href="catalog.php">Explore All Products &rarr;</a>
    </div>

    <div class="product-grid">
        <?php if (!empty($featured_products)): ?>
            <?php foreach ($featured_products as $p): ?>
                <div class="product-card" data-product-card>
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

                        <!-- Mandatory Stock Alert Feature -->
                        <div class="stock-indicator">
                            <?php if ($p['stock'] == 0): ?>
                                <span class="out-of-stock">&cross; Out of Stock</span>
                            <?php elseif ($p['stock'] <= 3): ?>
                                <span class="low-stock">&#9888; Only <?= $p['stock']; ?> left in stock!</span>
                            <?php else: ?>
                                <span class="in-stock">&check; In Stock</span>
                            <?php endif; ?>
                        </div>

                        <div class="card-actions">
                            <?php if ($p['stock'] > 0): ?>
                                <a href="cart.php?action=add&id=<?= $p['_id']; ?>" class="btn-add-cart" data-help="Add 1 unit directly to cart">
                                    + Add To Cart
                                </a>
                            <?php else: ?>
                                <button class="btn-add-cart" disabled>Unavailable</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="grid-column: 1/-1; padding: 20px; background:#fff; border-radius:8px;">
                No products found. Please run <a href="seed.php" target="_blank">seed.php</a> to populate sample data into MongoDB.
            </p>
        <?php endif; ?>
    </div>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

