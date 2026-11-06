<?php
/**
 * Global Header Component
 * Displays the Tata CLiQ navigation, last-visit cookie notice, and cart badge.
 */
require_once __DIR__ . '/../config/session.php';
$cart_count = get_cart_total_items();
$current_user = get_current_user_data();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tata CLiQ — Online Shopping India Replica</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Lucide icons or SVG helper styles -->
</head>
<body>

    <!-- Top Announcement Bar / Cookie Tracking Display -->
    <div class="top-notice-bar">
        <div>
            <span class="cookie-info">&bull; <?= $last_visit_display; ?></span>
        </div>
        <div>
            <a href="interaction_demo.php" data-help="Explore mandatory cursor, coordinates & panel interactions">&starf; Interaction Demo</a>
            <a href="feedback.php" data-help="Syllabus File-Handling CRUD Module">&check; Feedback (File CRUD)</a>
            <a href="catalog.php">All Products</a>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="navbar">
        <div class="nav-container">
            <!-- Brand Logo -->
            <a href="index.php" class="logo-container" title="Tata CLiQ Home">
                <div class="logo-badge">TATA <span>CLiQ</span></div>
                <div class="luxury-pill">LUXURY</div>
            </a>

            <!-- Search Bar with Live Shortcut Hint -->
            <form action="catalog.php" method="GET" class="search-form">
                <input 
                    type="text" 
                    id="global-search-input" 
                    name="q" 
                    placeholder="Search for luxury brands, watches, fashion... (Press '/' to focus)" 
                    value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>"
                    data-help="Type any brand or keyword and press Enter or use '/' as keyboard shortcut"
                    autocomplete="off"
                >
                <button type="submit" aria-label="Search">&#128269;</button>
            </form>

            <!-- Navigation Actions -->
            <div class="nav-actions">
                <?php if (is_logged_in()): ?>
                    <a href="orders.php" class="nav-link-item" data-help="View your simulated Tata CLiQ order history">
                        &#128100; Hello, <strong><?= htmlspecialchars($current_user['name']); ?></strong>
                    </a>
                    <a href="login.php?action=logout" class="nav-link-item" style="color: #c53030;" data-help="End your session and sign out">
                        Logout
                    </a>
                <?php else: ?>
                    <a href="login.php" class="nav-link-item" data-help="Sign in with your Tata CLiQ credentials">
                        &#128100; Sign In / Register
                    </a>
                <?php endif; ?>

                <!-- Cart Button with Dynamic Badge -->
                <a href="cart.php" class="cart-btn" data-help="Review selected items and simulated checkout">
                    &#128722; Cart <span class="cart-badge" id="nav-cart-counter"><?= $cart_count; ?></span>
                </a>
            </div>
        </div>

        <!-- Sub-navigation Category Bar -->
        <nav class="category-subnav">
            <ul>
                <li class="<?= empty($_GET['cat']) && basename($_SERVER['PHP_SELF']) == 'catalog.php' ? 'active' : ''; ?>">
                    <a href="catalog.php">All Categories</a>
                </li>
                <li><a href="catalog.php?cat=Men+Fashion">Men's Fashion</a></li>
                <li><a href="catalog.php?cat=Women+Fashion">Women's Fashion</a></li>
                <li><a href="catalog.php?cat=Watches">Watches</a></li>
                <li><a href="catalog.php?cat=Footwear">Footwear</a></li>
                <li><a href="catalog.php?cat=Electronics">Electronics</a></li>
                <li><a href="catalog.php?cat=Luxury">Luxury & Jewelry</a></li>
            </ul>
        </nav>
    </header>

