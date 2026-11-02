<?php
/**
 * ============================================================================
 * Protected Page: User Orders & Profile (orders.php)
 * Demonstrates:
 * - Session authentication protection (require_login)
 * - Displaying structured user identifier
 * - MongoDB 'orders' collection lookup and CRUD (Cancel Order / Delete)
 * ============================================================================
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/session.php';

// Enforce Session Protection
require_login();

$user = get_current_user_data();

// ----------------------------------------------------------------------------
// Handle Cancel Order (MongoDB Update/Delete CRUD)
// ----------------------------------------------------------------------------
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
$success_msg = isset($_GET['success']) ? $_GET['success'] : '';

if (isset($_GET['cancel_id'])) {
    $cancel_id = trim($_GET['cancel_id']);
    try {
        $filter = [
            'order_id' => $cancel_id,
            '$or' => [
                ['user_email' => $user['email']],
                ['user_id' => $user['id']]
            ]
        ];
        // Soft delete / Status update to 'Cancelled'
        $updated = update_db_record('orders', $filter, ['$set' => ['status' => 'Cancelled']]);
        if ($updated) {
            header("Location: orders.php?msg=" . urlencode("Order #$cancel_id has been cancelled."));
            exit;
        }
    } catch (Exception $e) {}
}

// Fetch orders placed by this user from MongoDB
$user_orders = get_db_records('orders', [
    '$or' => [
        ['user_email' => $user['email']],
        ['user_id' => $user['id']]
    ]
], ['sort' => ['created_at' => -1]]);

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-wrapper">

    <!-- User Profile Header -->
    <div style="background:#ffffff; border-radius:8px; padding:24px; border:1px solid var(--cliq-border); margin-bottom:28px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
        <div>
            <span class="luxury-pill" style="margin-bottom:6px; display:inline-block;">Tata CLiQ Privileged Member</span>
            <h1 style="font-size:1.5rem; margin-bottom:4px;"><?= htmlspecialchars($user['name']); ?></h1>
            <p style="font-size:0.85rem; color:#666;">
                Email: <strong><?= htmlspecialchars($user['email']); ?></strong> &bull; 
                Membership ID: <strong><code style="background:#f1f5f9; padding:2px 6px; border-radius:4px;"><?= htmlspecialchars($user['cliq_id']); ?></code></strong>
            </p>
        </div>
        <div>
            <a href="login.php?action=logout" class="btn-secondary" style="color:#b91c1c; border-color:#fca5a5;" onclick="return confirm('Do you wish to sign out of your session?');">
                &rarr; Sign Out
            </a>
        </div>
    </div>

    <!-- Feedback alerts -->
    <?php if (!empty($success_msg)): ?>
        <div style="background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; padding:12px 18px; border-radius:6px; margin-bottom:20px;">
            &check; <?= htmlspecialchars($success_msg); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($msg)): ?>
        <div style="background:#e0f2fe; color:#0369a1; padding:12px 18px; border-radius:6px; margin-bottom:20px;">
            &check; <?= htmlspecialchars($msg); ?>
        </div>
    <?php endif; ?>

    <!-- Orders Section -->
    <div class="section-title">
        <h2>Your Mock Orders (<?= count($user_orders); ?>)</h2>
        <a href="catalog.php">Browse Catalogue &rarr;</a>
    </div>

    <?php if (!empty($user_orders)): ?>
        <div style="display:flex; flex-direction:column; gap:20px; margin-bottom:40px;">
            <?php foreach ($user_orders as $ord): ?>
                <div style="background:#ffffff; border:1px solid var(--cliq-border); border-radius:8px; padding:20px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #eee; padding-bottom:12px; margin-bottom:16px; flex-wrap:wrap; gap:10px;">
                        <div>
                            <span style="font-size:0.75rem; text-transform:uppercase; color:#888; font-weight:700;">Order ID</span>
                            <div style="font-weight:800; font-size:1.05rem;"><?= htmlspecialchars($ord['order_id']); ?></div>
                            <span style="font-size:0.8rem; color:#666;">Placed on: <?= htmlspecialchars($ord['created_at']); ?></span>
                        </div>
                        <div style="text-align:right;">
                            <span style="font-size:0.75rem; text-transform:uppercase; color:#888; font-weight:700;">Status</span>
                            <div>
                                <?php if ($ord['status'] === 'Confirmed'): ?>
                                    <span style="background:#dcfce7; color:#15803d; font-weight:700; font-size:0.85rem; padding:3px 10px; border-radius:12px;">&check; Confirmed</span>
                                <?php elseif ($ord['status'] === 'Cancelled'): ?>
                                    <span style="background:#fee2e2; color:#b91c1c; font-weight:700; font-size:0.85rem; padding:3px 10px; border-radius:12px;">&cross; Cancelled</span>
                                <?php else: ?>
                                    <span style="background:#fef3c7; color:#b45309; font-weight:700; font-size:0.85rem; padding:3px 10px; border-radius:12px;"><?= htmlspecialchars($ord['status']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Items in Order -->
                    <table class="data-table" style="margin-bottom:14px;">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Unit Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ord['items'] as $it): ?>
                                <tr>
                                    <td style="display:flex; align-items:center; gap:10px;">
                                        <?php if (!empty($it['image'])): ?>
                                            <img src="<?= htmlspecialchars($it['image']); ?>" alt="" style="width:40px; height:40px; object-fit:cover; border-radius:4px;">
                                        <?php endif; ?>
                                        <span><?= htmlspecialchars($it['title']); ?></span>
                                    </td>
                                    <td>&#8377;<?= number_format($it['price']); ?></td>
                                    <td><?= $it['qty']; ?></td>
                                    <td><strong>&#8377;<?= number_format($it['price'] * $it['qty']); ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Summary & Actions -->
                    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                        <div>
                            <span style="font-size:0.85rem; color:#555;">Payment Mode: <strong><?= htmlspecialchars($ord['payment_method'] ?? 'Simulated'); ?></strong></span>
                            <?php if (!empty($ord['discount'])): ?>
                                <span style="font-size:0.85rem; color:var(--cliq-success); margin-left:14px;">Discount: -&#8377;<?= number_format($ord['discount']); ?></span>
                            <?php endif; ?>
                        </div>
                        <div style="display:flex; align-items:center; gap:16px;">
                            <span style="font-size:1.1rem; font-weight:800;">
                                Total Amount: &#8377;<?= number_format($ord['total_amount']); ?>
                            </span>

                            <?php if ($ord['status'] !== 'Cancelled'): ?>
                                <a href="orders.php?cancel_id=<?= urlencode($ord['order_id']); ?>" 
                                   class="btn-secondary" 
                                   style="color:#dc2626; border-color:#fca5a5; font-size:0.8rem; padding:6px 12px;"
                                   onclick="return confirm('Cancel this simulated order?');">
                                    Cancel Order
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div style="background:#fff; border-radius:8px; padding:40px; text-align:center; border:1px solid #e0e0e0; margin-bottom:40px;">
            <h3>You haven't placed any mock orders yet.</h3>
            <p style="color:#666; margin:8px 0 20px;">Explore items in the catalogue and place a simulated purchase.</p>
            <a href="catalog.php" class="btn-primary">Browse Catalogue</a>
        </div>
    <?php endif; ?>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
