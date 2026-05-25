<?php
$host = 'ep-muddy-night-a26v57m5.eu-central-1.aws.neon.tech';
$db   = 'emad-stor';
$user = 'emad-stor_owner';
$pass = 'npg_i8UIN0jcvZLa';
$endpoint = 'ep-muddy-night-a26v57m5';

$dsn = "pgsql:host=$host;dbname=$db;sslmode=require;options=endpoint%3D$endpoint";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $pdo->exec("ALTER TABLE products ALTER COLUMN image_url TYPE TEXT");
    echo "Successfully altered table products image_url to TEXT.\n";
    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
