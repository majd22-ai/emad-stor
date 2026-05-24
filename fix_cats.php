<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'ep-tiny-wave-aqa2wj71-pooler.c-8.us-east-1.aws.neon.tech';
$db   = 'neondb';
$user = 'neondb_owner';
$pass = 'npg_NZPcHVn5UqI8';
$port = '5432';
$endpoint = 'ep-tiny-wave-aqa2wj71-pooler';

$dsn = "pgsql:host=$host;port=$port;dbname=$db;options=endpoint%3D$endpoint;sslmode=require";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
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
    
    echo "Categories inserted successfully.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
