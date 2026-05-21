<?php
require_once __DIR__ . '/includes/db_connect.php';
try {
    $stmt = $pdo->query("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'products'");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($columns);
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
