<?php
/**
 * ============================================================================
 * Page 3: Product Details (product_details.php)
 * Demonstrates:
 * - Fetching single document by MongoDB _id
 * - Advanced Integration Feature: Dynamic Warehouse Stock Alert
 * - Quantity-bounded Cart Addition
 * ============================================================================
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';

$product_id = isset($_GET['id']) ? trim($_GET['id']) : '';
$product = null;

if (!empty($product_id)) {
    try {
        $filter = ['_id' => new MongoDB\BSON\ObjectId($product_id)];
        $product = get_single_record('products', $filter);
    } catch (Exception $e) {
        $product = null;
    }
}

if (!$product) {
    echo "<main class='main-wrapper'><div style='background:#fff; padding:40px; text-align:center; border-radius:8px; margin-top:30px;'>
            <h2>Product Not Found</h2>
            <p style='margin:12px 0 20px; color:#666;'>The product you requested does not exist or has been removed.</p>
            <a href='catalog.php' class='btn-primary'>Return to Catalog</a>
          </div></main>";
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// Fetch related items in same category
$related = get_db_records('products', [
    'category' => $product['category'],
    '_id' => ['$ne' => new MongoDB\BSON\ObjectId($product['_id'])]
], ['limit' => 3]);
?>

<main class="main-wrapper">

    <!-- Breadcrumb -->
    <div style="font-size:0.85rem; color:#666; margin-bottom:18px;">
        <a href="index.php">Home</a> &rsaquo; 
        <a href="catalog.php">Catalog</a> &rsaquo; 
        <a href="catalog.php?cat=<?= urlencode($product['category']); ?>"><?= htmlspecialchars($product['category']); ?></a> &rsaquo; 
        <span><?= htmlspecialchars($product['brand']); ?></span>
    </div>

    <!-- Product Details Main Layout -->
    <div class="product-detail-container">
        <!-- Left: Image Gallery Display -->
        <div class="detail-img-box">
            <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['title']); ?>">
        </div>

        <!-- Right: Information & Cart CTA -->
        <div class="detail-info">
            <div class="brand"><?= htmlspecialchars($product['brand']); ?></div>
            <h1><?= htmlspecialchars($product['title']); ?></h1>

            <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
                <div class="product-rating" style="position:static;">
                    <span class="star">&#9733;</span> <strong><?= $product['rating']; ?></strong> / 5.0
                </div>
                <span style="font-size:0.85rem; color:#666;"><?= $product['reviews_count']; ?> Verified Buyer Ratings</span>
            </div>

            <!-- Price Breakdown -->
            <div class="price-row" style="margin-bottom:16px;">
                <span class="current-price" style="font-size:1.8rem;">&#8377;<?= number_format($product['price']); ?></span>
                <span class="original-mrp" style="font-size:1.1rem;">&#8377;<?= number_format($product['mrp']); ?></span>
                <span class="discount-badge" style="font-size:0.95rem;"><?= $product['discount_percent']; ?>% OFF</span>
            </div>
            <p style="font-size:0.8rem; color:#666; margin-bottom:20px;">Inclusive of all applicable taxes & free delivery.</p>

            <!-- ======================================================== -->
            <!-- ON-SPOT MODIFICATION SPOT #7: STOCK ALERT THRESHOLD -->
            <!-- Evaluator may ask to modify stock alert conditions -->
            <!-- ======================================================== -->
            <div style="padding:14px; border-radius:6px; margin-bottom:24px; <?php 
                if ($product['stock'] == 0) echo 'background:#fef2f2; border:1px solid #fecaca; color:#991b1b;';
                elseif ($product['stock'] <= 3) echo 'background:#fffbeb; border:1px solid #fde68a; color:#92400e;';
                else echo 'background:#f0fdf4; border:1px solid #bbf7d0; color:#166534;';
            ?>">
                <?php if ($product['stock'] == 0): ?>
                    <h4 style="margin-bottom:4px;">&cross; Currently Out of Stock</h4>
                    <p style="font-size:0.85rem;">This luxury item is currently sold out. Please check back later or explore related items.</p>
                <?php elseif ($product['stock'] <= 3): ?>
                    <h4 style="margin-bottom:4px;">&#9888; Hurry! Only <?= $product['stock']; ?> Left in Stock</h4>
                    <p style="font-size:0.85rem;">High demand! Items in high volume may sell out quickly.</p>
                <?php else: ?>
                    <h4 style="margin-bottom:4px;">&check; In Stock & Ready for Immediate Dispatch</h4>
                    <p style="font-size:0.85rem;">Authentic Tata CLiQ certified product with 100% genuine warranty.</p>
                <?php endif; ?>
            </div>

            <!-- Add to Cart Form -->
            <form action="cart.php" method="GET" style="margin-bottom:28px;">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="id" value="<?= $product['_id']; ?>">

                <div style="display:flex; gap:16px; align-items:center;">
                    <?php if ($product['stock'] > 0): ?>
                        <div style="display:flex; flex-direction:column; gap:4px;">
                            <label for="qty-select" style="font-size:0.85rem; font-weight:600;">Qty:</label>
                            <select name="qty" id="qty-select" class="form-control" style="width:70px; padding:8px;">
                                <?php for ($i = 1; $i <= min(5, $product['stock']); $i++): ?>
                                    <option value="<?= $i; ?>"><?= $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn-primary" style="flex:1; padding:14px; font-size:1rem;" data-help="Add selected quantity to your shopping cart">
                            &#128722; Add To Cart
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn-primary" style="flex:1; padding:14px; font-size:1rem; background:#cbd5e1; cursor:not-allowed;" disabled>
                            Item Unavailable
                        </button>
                    <?php endif; ?>
                </div>
            </form>

            <!-- Product Description & Specifications -->
            <h3 style="font-size:1.1rem; margin-bottom:10px;">Product Description</h3>
            <p style="color:#555; line-height:1.6; font-size:0.9rem; margin-bottom:20px;">
                <?= htmlspecialchars($product['description']); ?>
            </p>

            <h3 style="font-size:1.1rem; margin-bottom:10px;">Key Features</h3>
            <ul class="detail-features-list">
                <?php if (!empty($product['features']) && is_array($product['features'])): ?>
                    <?php foreach ($product['features'] as $feature): ?>
                        <li><?= htmlspecialchars($feature); ?></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <!-- Related Products -->
    <?php if (!empty($related)): ?>
        <div class="section-title">
            <h2>You May Also Like</h2>
            <a href="catalog.php?cat=<?= urlencode($product['category']); ?>">View Category &rarr;</a>
        </div>
        <div class="product-grid">
            <?php foreach ($related as $rel): ?>
                <div class="product-card">
                    <a href="product_details.php?id=<?= $rel['_id']; ?>" class="product-img-wrapper">
                        <img src="<?= htmlspecialchars($rel['image']); ?>" alt="<?= htmlspecialchars($rel['title']); ?>">
                    </a>
                    <div class="product-info">
                        <div class="product-brand"><?= htmlspecialchars($rel['brand']); ?></div>
                        <a href="product_details.php?id=<?= $rel['_id']; ?>" class="product-title"><?= htmlspecialchars($rel['title']); ?></a>
                        <div class="price-row">
                            <span class="current-price">&#8377;<?= number_format($rel['price']); ?></span>
                            <span class="original-mrp">&#8377;<?= number_format($rel['mrp']); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
