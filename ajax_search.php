<?php
require_once 'includes/db_connect.php';

header('Content-Type: application/json');

if (!isset($_GET['q']) || empty(trim($_GET['q']))) {
    echo json_encode(['categories' => [], 'products' => []]);
    exit;
}

$q = trim($_GET['q']);
$searchTerm = '%' . $q . '%';

try {
    // جلب الأقسام المطابقة
    $stmt_cat = $pdo->prepare("SELECT id, name, slug FROM categories WHERE name LIKE ? LIMIT 5");
    $stmt_cat->execute([$searchTerm]);
    $categories = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);

    // جلب المنتجات المطابقة
    $stmt_prod = $pdo->prepare("SELECT id, title, price, image_url FROM products WHERE title LIKE ? OR description LIKE ? ORDER BY id DESC LIMIT 5");
    $stmt_prod->execute([$searchTerm, $searchTerm]);
    $products = $stmt_prod->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'categories' => $categories,
        'products' => $products
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error']);
}
