<?php
require_once __DIR__ . '/includes/db_connect.php';

$sql = "
    -- إضافة دور المستخدم إذا لم يكن موجوداً
    ALTER TABLE users ADD COLUMN IF NOT EXISTS role VARCHAR(20) DEFAULT 'customer';

    -- جدول التصنيفات
    CREATE TABLE IF NOT EXISTS categories (
        id SERIAL PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        slug VARCHAR(100) UNIQUE NOT NULL
    );

    -- إدخال تصنيفات افتراضية إذا كان الجدول فارغاً
    INSERT INTO categories (name, slug) 
    SELECT 'خواتم رجالية', 'me-rings'
    WHERE NOT EXISTS (SELECT 1 FROM categories WHERE slug = 'me-rings');
    
    INSERT INTO categories (name, slug) 
    SELECT 'مسابح', 'men-beads'
    WHERE NOT EXISTS (SELECT 1 FROM categories WHERE slug = 'men-beads');

    INSERT INTO categories (name, slug) 
    SELECT 'خواتم نسائية', 'wo-rings'
    WHERE NOT EXISTS (SELECT 1 FROM categories WHERE slug = 'wo-rings');

    INSERT INTO categories (name, slug) 
    SELECT 'قلائد', 'wo-necklaces'
    WHERE NOT EXISTS (SELECT 1 FROM categories WHERE slug = 'wo-necklaces');

    INSERT INTO categories (name, slug) 
    SELECT 'أساور', 'wo-bracelets'
    WHERE NOT EXISTS (SELECT 1 FROM categories WHERE slug = 'wo-bracelets');

    INSERT INTO categories (name, slug) 
    SELECT 'أقراط', 'wo-earrings'
    WHERE NOT EXISTS (SELECT 1 FROM categories WHERE slug = 'wo-earrings');

    -- جدول المنتجات
    CREATE TABLE IF NOT EXISTS products (
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
    echo "Admin database tables created successfully.\n";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
