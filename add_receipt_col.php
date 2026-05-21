<?php
require_once __DIR__ . '/includes/db_connect.php';

try {
    $pdo->exec("ALTER TABLE orders ADD COLUMN IF NOT EXISTS payment_receipt VARCHAR(255)");
    echo "Column payment_receipt added successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
