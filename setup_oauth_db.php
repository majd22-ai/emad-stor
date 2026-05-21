<?php
require_once __DIR__ . '/includes/db_connect.php';
$sql = "
    ALTER TABLE users ADD COLUMN IF NOT EXISTS oauth_provider VARCHAR(50) DEFAULT NULL;
    ALTER TABLE users ADD COLUMN IF NOT EXISTS oauth_uid VARCHAR(255) DEFAULT NULL;
    ALTER TABLE users ALTER COLUMN password_hash DROP NOT NULL;
";
try {
    $pdo->exec($sql);
    echo "Table users altered for OAuth successfully.\n";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
