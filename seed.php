<?php
/**
 * ============================================================================
 * Seed Script for Tata CLiQ Replica
 * Run this script once to populate MongoDB Atlas with initial sample data.
 * Requirements met: At least 3 related collections (users, products, orders),
 * with >= 10 realistic records.
 * ============================================================================
 */

require_once __DIR__ . '/config/db.php';

echo "<h2>Starting Tata CLiQ Database Seeding...</h2>";

// ============================================================================
// 1. SEED PRODUCTS (12 Items across Fashion, Footwear, Electronics, Luxury)
// ============================================================================
$products = [
    [
        'title' => 'Peter England Men Slim Fit Formal Shirt',
        'brand' => 'Peter England',
        'category' => 'Men Fashion',
        'price' => 1299,
        'mrp' => 1999,
        'discount_percent' => 35,
        'stock' => 15,
        'rating' => 4.4,
        'reviews_count' => 84,
        'image' => 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=600&auto=format&fit=crop&q=80',
        'description' => 'Sophisticated and crisp slim-fit shirt made with 100% breathable cotton, ideal for boardroom meetings and formal dinners.',
        'features' => ['100% Breathable Cotton', 'Full Sleeves with Cuffs', 'Spread Collar', 'Machine Wash']
    ],
    [
        'title' => 'Titan Neo Splash Analog Black Dial Watch',
        'brand' => 'Titan',
        'category' => 'Watches',
        'price' => 4795,
        'mrp' => 5995,
        'discount_percent' => 20,
        'stock' => 8,
        'rating' => 4.7,
        'reviews_count' => 142,
        'image' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?w=600&auto=format&fit=crop&q=80',
        'description' => 'Classy analog watch with durable stainless steel strap and mineral crystal glass. Water-resistant up to 50 meters.',
        'features' => ['Quartz Movement', 'Stainless Steel Strap', 'Water Resistant 50m', '2-Year Warranty']
    ],
    [
        'title' => 'W for Woman Floral Print Straight Kurta',
        'brand' => 'W for Woman',
        'category' => 'Women Fashion',
        'price' => 1499,
        'mrp' => 2499,
        'discount_percent' => 40,
        'stock' => 2, // Low stock: triggers "Only 2 left - Hurry!"
        'rating' => 4.3,
        'reviews_count' => 61,
        'image' => 'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?w=600&auto=format&fit=crop&q=80',
        'description' => 'Brighten your festive wardrobe with this elegant floral printed regular straight kurta with mandarin neck and side slits.',
        'features' => ['Viscose Rayon Fabric', 'Three-Quarter Sleeves', 'Mandarin Collar', 'Dry Clean Only']
    ],
    [
        'title' => 'Sony WH-1000XM5 Wireless Noise Cancelling Headphones',
        'brand' => 'Sony',
        'category' => 'Electronics',
        'price' => 26990,
        'mrp' => 34990,
        'discount_percent' => 23,
        'stock' => 12,
        'rating' => 4.9,
        'reviews_count' => 320,
        'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&auto=format&fit=crop&q=80',
        'description' => 'Industry-leading noise cancellation with two processors and 8 microphones. Up to 30-hour battery life with fast charging.',
        'features' => ['Industry-leading ANC', '30-hour Battery', 'Multipoint Connection', 'High-Res Audio']
    ],
    [
        'title' => 'Puma Smash v2 Leather Unisex Sneakers',
        'brand' => 'Puma',
        'category' => 'Footwear',
        'price' => 2199,
        'mrp' => 3999,
        'discount_percent' => 45,
        'stock' => 0, // Out of stock: triggers "Out of Stock" disabled button
        'rating' => 4.5,
        'reviews_count' => 195,
        'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&auto=format&fit=crop&q=80',
        'description' => 'Classic tennis-inspired street shoe with soft suede and smooth leather upper. Features rubber cupsole for maximum traction.',
        'features' => ['Genuine Soft Leather', 'Cushioned Footbed', 'Grippy Rubber Outsole', 'Lace-Up Fastening']
    ],
    [
        'title' => 'Fossil Gen 6 Smartwatch with Wear OS',
        'brand' => 'Fossil',
        'category' => 'Watches',
        'price' => 18495,
        'mrp' => 24995,
        'discount_percent' => 26,
        'stock' => 5,
        'rating' => 4.2,
        'reviews_count' => 78,
        'image' => 'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=600&auto=format&fit=crop&q=80',
        'description' => 'Track your heart rate, cardio level, and SpO2. Fast charge to 80% in just 30 minutes with Snapdragon 4100+ chipset.',
        'features' => ['SpO2 & Heart Rate Tracker', 'Qualcomm 4100+ Processor', 'Fast Charging', 'Water Resistant 3ATM']
    ],
    [
        'title' => 'Apple AirPods Pro (2nd Generation) with MagSafe',
        'brand' => 'Apple',
        'category' => 'Electronics',
        'price' => 20900,
        'mrp' => 24900,
        'discount_percent' => 16,
        'stock' => 10,
        'rating' => 4.8,
        'reviews_count' => 540,
        'image' => 'https://images.unsplash.com/photo-1600294037681-c80b4cb5b434?w=600&auto=format&fit=crop&q=80',
        'description' => 'Next-level Active Noise Cancellation and Adaptive Transparency. Personalized Spatial Audio with dynamic head tracking.',
        'features' => ['H2 Apple Silicon', 'Touch Control Volume', 'Up to 6h Listening Time', 'Dust, Sweat & Water Resistant']
    ],
    [
        'title' => 'Woodland Brown Rugged Leather Trekking Boots',
        'brand' => 'Woodland',
        'category' => 'Footwear',
        'price' => 3895,
        'mrp' => 4995,
        'discount_percent' => 22,
        'stock' => 7,
        'rating' => 4.6,
        'reviews_count' => 112,
        'image' => 'https://images.unsplash.com/photo-1520639888713-7851133b1ed0?w=600&auto=format&fit=crop&q=80',
        'description' => 'Heavy-duty nubuck leather boots designed for rough terrains, forest hikes, and everyday winter protection.',
        'features' => ['Nubuck Leather Upper', 'Deep Lug Rubber Outsole', 'Rustproof Metal Eyelets', 'Ankle Support']
    ],
    [
        'title' => 'Biba Solid Anarkali Kurta with Dupatta Set',
        'brand' => 'Biba',
        'category' => 'Women Fashion',
        'price' => 2999,
        'mrp' => 4999,
        'discount_percent' => 40,
        'stock' => 14,
        'rating' => 4.5,
        'reviews_count' => 93,
        'image' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?w=600&auto=format&fit=crop&q=80',
        'description' => 'Graceful floor-length festive anarkali suit set accompanied by matching churidar pants and a bordered organza dupatta.',
        'features' => ['Art Silk & Chiffon', 'Gota Patti Detailing', 'Floor-length Flare', 'Festive Ready']
    ],
    [
        'title' => 'Tanishq 22KT Yellow Gold Stud Earrings',
        'brand' => 'Tanishq',
        'category' => 'Luxury',
        'price' => 16450,
        'mrp' => 18200,
        'discount_percent' => 10,
        'stock' => 4,
        'rating' => 4.9,
        'reviews_count' => 48,
        'image' => 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=600&auto=format&fit=crop&q=80',
        'description' => 'Hallmarked 22kt pure yellow gold stud earrings displaying delicate floral filigree workmanship. Tata trust assured.',
        'features' => ['22KT Hallmarked Gold', 'Screw Back Closure', 'Certificate of Authenticity', 'Tata CLiQ Luxury Certified']
    ],
    [
        'title' => 'Levis 511 Slim Fit Dark Indigo Jeans',
        'brand' => 'Levis',
        'category' => 'Men Fashion',
        'price' => 2399,
        'mrp' => 3999,
        'discount_percent' => 40,
        'stock' => 20,
        'rating' => 4.6,
        'reviews_count' => 210,
        'image' => 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=600&auto=format&fit=crop&q=80',
        'description' => 'Modern slim fit with room to move. Added stretch for all-day comfort. Woven with Levi’s Flex eco-performance denim.',
        'features' => ['99% Cotton, 1% Elastane', 'Zip Fly with Button Closure', '5-Pocket Styling', 'Mid Rise']
    ],
    [
        'title' => 'Casio Vintage Digital Gold Dial Watch',
        'brand' => 'Casio',
        'category' => 'Watches',
        'price' => 3995,
        'mrp' => 4495,
        'discount_percent' => 11,
        'stock' => 11,
        'rating' => 4.7,
        'reviews_count' => 380,
        'image' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=600&auto=format&fit=crop&q=80',
        'description' => 'Iconic retro gold-toned digital watch with LED light, daily alarm, hourly time signal, and 1/100-second digital stopwatch.',
        'features' => ['Gold Ion Plated Band', '7-Year Battery Life', 'Water Resistant', 'LED Backlight']
    ]
];

// Clean existing products and re-seed
try {
    $bulk = new MongoDB\Driver\BulkWrite();
    $bulk->delete([]); // empty filter deletes all
    $GLOBALS['mongoManager']->executeBulkWrite("$DB_NAME.products", $bulk);

    $bulkInsert = new MongoDB\Driver\BulkWrite();
    foreach ($products as $p) {
        $p['created_at'] = date('Y-m-d H:i:s');
        $bulkInsert->insert($p);
    }
    $res = $GLOBALS['mongoManager']->executeBulkWrite("$DB_NAME.products", $bulkInsert);
    echo "<p style='color:green;'>&check; Products seeded: " . $res->getInsertedCount() . " documents added to 'products' collection.</p>";
} catch (Exception $e) {
    echo "<p style='color:red;'>&cross; Product seeding error: " . $e->getMessage() . "</p>";
}

// ============================================================================
// 2. SEED DEFAULT USER (users collection)
// ============================================================================
$sample_user = [
    'name' => 'Arjun Sharma',
    'email' => 'arjun@tatacliq.com',
    'password_hash' => password_hash('Cliq@2026', PASSWORD_DEFAULT),
    'cliq_id' => 'CLIQ-1001-IN',
    'phone' => '9876543210',
    'created_at' => date('Y-m-d H:i:s')
];

try {
    $bulk = new MongoDB\Driver\BulkWrite();
    $bulk->delete(['email' => $sample_user['email']]);
    $bulk->insert($sample_user);
    $res = $GLOBALS['mongoManager']->executeBulkWrite("$DB_NAME.users", $bulk);
    echo "<p style='color:green;'>&check; Users seeded: Sample user created (<strong>Email:</strong> arjun@tatacliq.com | <strong>Password:</strong> Cliq@2026 | <strong>CLiQ ID:</strong> CLIQ-1001-IN)</p>";
} catch (Exception $e) {
    echo "<p style='color:red;'>&cross; User seeding error: " . $e->getMessage() . "</p>";
}

// ============================================================================
// 3. SEED SAMPLE ORDER (orders collection)
// ============================================================================
$sample_order = [
    'order_id' => 'TC-ORD-' . rand(100000, 999999),
    'user_email' => 'arjun@tatacliq.com',
    'user_name' => 'Arjun Sharma',
    'items' => [
        [
            'title' => 'Peter England Men Slim Fit Formal Shirt',
            'price' => 1299,
            'qty' => 1,
            'image' => 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=600&auto=format&fit=crop&q=80'
        ],
        [
            'title' => 'Titan Neo Splash Analog Black Dial Watch',
            'price' => 4795,
            'qty' => 1,
            'image' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?w=600&auto=format&fit=crop&q=80'
        ]
    ],
    'subtotal' => 6094,
    'discount' => 300,
    'delivery_fee' => 0,
    'total_amount' => 5794,
    'payment_method' => 'Tata Pay (Simulated)',
    'status' => 'Confirmed',
    'created_at' => date('Y-m-d H:i:s')
];

try {
    $bulk = new MongoDB\Driver\BulkWrite();
    $bulk->insert($sample_order);
    $res = $GLOBALS['mongoManager']->executeBulkWrite("$DB_NAME.orders", $bulk);
    echo "<p style='color:green;'>&check; Orders seeded: Sample mock order created with Order ID <strong>" . $sample_order['order_id'] . "</strong></p>";
} catch (Exception $e) {
    echo "<p style='color:red;'>&cross; Order seeding error: " . $e->getMessage() . "</p>";
}

// ============================================================================
// 4. INITIALIZE TEXT FILE FOR SECONDARY MODULE CRUD (feedback.txt)
// ============================================================================
$data_dir = __DIR__ . '/data';
if (!is_dir($data_dir)) {
    mkdir($data_dir, 0777, true);
}
$feedback_file = $data_dir . '/feedback.txt';
if (!file_exists($feedback_file)) {
    $initial_feedbacks = [
        "1|Arjun Sharma|arjun@tatacliq.com|5|Excellent packaging and fast delivery of my Titan watch.|2026-11-01 14:20:00",
        "2|Pooja Patel|pooja@gmail.com|4|Authentic luxury goods on Tata CLiQ. Love the festive collection.|2026-11-02 09:15:30",
        "3|Rohan Verma|rohan@yahoo.com|5|Customer service resolved my size replacement request quickly.|2026-11-02 11:45:10"
    ];
    file_put_contents($feedback_file, implode("\n", $initial_feedbacks) . "\n");
    echo "<p style='color:green;'>&check; File CRUD initialized: <code>data/feedback.txt</code> created with 3 sample logs.</p>";
}

echo "<hr><h3 style='color:navy;'>Database & Sample Data Seeding Completed Successfully!</h3>";
echo "<p><a href='index.php'>Go to Homepage</a> | <a href='catalog.php'>Go to Product Catalog</a></p>";
?>

