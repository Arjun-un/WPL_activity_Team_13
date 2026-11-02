<?php
/**
 * ============================================================================
 * Session & Cookie Management
 * Handles user login state, last-visit tracking cookie, and cart counters.
 * ============================================================================
 */

// Start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================================================
// ON-SPOT MODIFICATION SPOT #2: LAST-VISIT COOKIE EXPIRY & FORMAT
// Evaluator may ask to change cookie expiry duration or date format.
// ============================================================================
$COOKIE_NAME = "tatacliq_last_visit";
$COOKIE_EXPIRY = time() + (86400 * 30); // 30 days from now

// Capture previous visit time before updating the cookie
$last_visit_display = "Welcome to Tata CLiQ! First time here?";
if (isset($_COOKIE[$COOKIE_NAME])) {
    $last_visit_display = "Welcome back! Your last visit was on: " . htmlspecialchars($_COOKIE[$COOKIE_NAME]);
}

// Update the cookie with current timestamp for the NEXT visit
setcookie($COOKIE_NAME, date("d M Y, h:i A"), $COOKIE_EXPIRY, "/");

/**
 * Check if the user is authenticated
 */
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get logged-in user details
 */
function get_current_user_data() {
    if (!is_logged_in()) {
        return null;
    }
    return [
        'id'       => $_SESSION['user_id'],
        'name'     => $_SESSION['user_name'] ?? 'User',
        'email'    => $_SESSION['user_email'] ?? '',
        'cliq_id'  => $_SESSION['cliq_id'] ?? 'CLIQ-0000-XX'
    ];
}

/**
 * Protect pages requiring authentication
 */
function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php?msg=" . urlencode("Please sign in to access this page."));
        exit;
    }
}

/**
 * Count total items in user's shopping cart
 */
function get_cart_total_items() {
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        return 0;
    }
    $count = 0;
    foreach ($_SESSION['cart'] as $qty) {
        $count += (int)$qty;
    }
    return $count;
}
?>
