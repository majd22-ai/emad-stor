<?php
session_start();
require 'includes/db_connect.php';

try {
    // Check if table exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS categories (
        id SERIAL PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        slug VARCHAR(100) UNIQUE NOT NULL
    )");
    
    $cats = [
        ['name' => 'خواتم رجالية', 'slug' => 'me-rings'],
        ['name' => 'مسابح', 'slug' => 'men-beads'],
        ['name' => 'خواتم نسائية', 'slug' => 'wo-rings'],
        ['name' => 'قلائد', 'slug' => 'wo-necklaces'],
        ['name' => 'أساور', 'slug' => 'wo-bracelets'],
        ['name' => 'أقراط', 'slug' => 'wo-earrings']
    ];
    
    foreach ($cats as $cat) {
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug) SELECT ?, ? WHERE NOT EXISTS (SELECT 1 FROM categories WHERE slug = ?)");
        $stmt->execute([$cat['name'], $cat['slug'], $cat['slug']]);
    }
    
    echo "<h1>تم إصلاح الأقسام بنجاح!</h1>";
    echo "<p>يمكنك الآن تصفح الأقسام في المتجر بدون مشاكل.</p>";
    echo "<a href='index.php'>العودة للصفحة الرئيسية</a>";
    
} catch (Exception $e) {
    echo "حدث خطأ: " . $e->getMessage();
}
?>
