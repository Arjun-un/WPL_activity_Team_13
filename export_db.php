<?php
/**
 * ============================================================================
 * MongoDB Export Script for Tata CLiQ Replica
 * Exports all collections (users, products, orders) to a JSON file in data/
 * meeting the database export deliverable requirement.
 * ============================================================================
 */

require_once __DIR__ . '/config/db.php';

$export = [
    'exported_at' => date('Y-m-d H:i:s'),
    'database'    => $DB_NAME,
    'collections' => [
        'users'    => get_db_records('users'),
        'products' => get_db_records('products'),
        'orders'   => get_db_records('orders')
    ]
];

$output_file = __DIR__ . '/data/tatacliq_mongodb_export.json';
file_put_contents($output_file, json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

echo "<h3>MongoDB Export Completed Successfully!</h3>";
echo "<p>Exported to: <code>data/tatacliq_mongodb_export.json</code></p>";
echo "<p>Users: " . count($export['collections']['users']) . "</p>";
echo "<p>Products: " . count($export['collections']['products']) . "</p>";
echo "<p>Orders: " . count($export['collections']['orders']) . "</p>";
?>
