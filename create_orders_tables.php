<?php
require_once 'includes/db_connect.php';

try {
    // Create orders table
    $pdo->exec("CREATE TABLE IF NOT EXISTS orders (
        id SERIAL PRIMARY KEY,
        user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
        customer_name VARCHAR(100) NOT NULL,
        customer_phone VARCHAR(20) NOT NULL,
        customer_address TEXT NOT NULL,
        total_price DECIMAL(10,2) NOT NULL,
        status VARCHAR(20) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Create order_items table
    $pdo->exec("CREATE TABLE IF NOT EXISTS order_items (
        id SERIAL PRIMARY KEY,
        order_id INTEGER REFERENCES orders(id) ON DELETE CASCADE,
        product_id INTEGER REFERENCES products(id) ON DELETE SET NULL,
        quantity INTEGER NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        size VARCHAR(50)
    )");

    echo "Orders tables created successfully.\n";
} catch (PDOException $e) {
    echo "Error creating tables: " . $e->getMessage() . "\n";
}
