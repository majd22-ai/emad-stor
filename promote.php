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
    $stmt = $pdo->prepare("UPDATE users SET role = 'admin'");
    $stmt->execute();
    $count = $stmt->rowCount();
    echo "Successfully updated $count user(s) to admin role.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
