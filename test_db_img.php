<?php
require 'includes/db_connect.php';

$stmt = $pdo->query("SELECT id, title, image_url, created_at FROM products ORDER BY id DESC LIMIT 5");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

print_r($products);
?>
