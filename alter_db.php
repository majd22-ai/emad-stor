<?php
session_start();
require 'includes/db_connect.php';

try {
    // Alter products table
    $pdo->exec("ALTER TABLE products ALTER COLUMN image_url TYPE TEXT");
    echo "Products table altered successfully.\n";
    
    // We also should alter categories if they have images, but they don't right now.
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
