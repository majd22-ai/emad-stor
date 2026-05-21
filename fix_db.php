<?php
require_once __DIR__ . '/includes/db_connect.php';

$sql = "
    DROP TABLE IF EXISTS products;
    
    CREATE TABLE products (
        id SERIAL PRIMARY KEY,
        category_id INT REFERENCES categories(id) ON DELETE CASCADE,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        price DECIMAL(10, 2) NOT NULL,
        image_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
";

try {
    $pdo->exec($sql);
    echo "Products table recreated successfully.\n";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
