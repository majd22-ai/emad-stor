<?php
require_once 'includes/db_connect.php';

try {
    // 1. Create coupons table
    $pdo->exec("CREATE TABLE IF NOT EXISTS coupons (
        id SERIAL PRIMARY KEY,
        code VARCHAR(50) NOT NULL UNIQUE,
        discount_percent INT NOT NULL,
        usage_limit INT DEFAULT 100,
        used_count INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    echo "Coupons table created successfully.\n";

    // 2. Add coupon_code and discount_amount to orders table
    try {
        $pdo->exec("ALTER TABLE orders ADD COLUMN coupon_code VARCHAR(50) NULL");
        echo "Added coupon_code to orders.\n";
    } catch (Exception $e) {
        echo "coupon_code might already exist.\n";
    }
    
    try {
        $pdo->exec("ALTER TABLE orders ADD COLUMN discount_amount DECIMAL(10,2) DEFAULT 0.00");
        echo "Added discount_amount to orders.\n";
    } catch (Exception $e) {
        echo "discount_amount might already exist.\n";
    }

    echo "Database setup for coupons is complete!\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
