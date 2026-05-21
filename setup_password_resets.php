<?php
require_once 'includes/db_connect.php';

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS password_resets (
        id SERIAL PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        token VARCHAR(10) NOT NULL,
        expires_at TIMESTAMP NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    echo "password_resets table created successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
