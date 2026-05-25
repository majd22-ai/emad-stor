<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
    session_start();
$_SESSION['currency'] = 'SAR';
require_once 'includes/functions.php';

// Mock variables for header.php
$_SERVER['HTTP_HOST'] = 'emad-stor.onrender.com';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['HTTPS'] = 'on';

// Mock DB connection just enough to not crash
$pdo = new PDO('sqlite::memory:');
$pdo->exec("CREATE TABLE orders (status VARCHAR(50))");

ob_start();
include 'includes/header.php';
$output = ob_get_clean();

if (strpos($output, 'assets/css/style.css') !== false) {
    echo "CSS Link found!\n";
} else {
    echo "CSS LINK MISSING!\n";
}

$lines = explode("\n", $output);
foreach ($lines as $i => $line) {
    if (strpos($line, 'style.css') !== false || strpos($line, 'currencySymbol') !== false || strpos($line, 'Warning') !== false || strpos($line, 'Error') !== false) {
        echo "Line " . ($i+1) . ": " . $line . "\n";
    }
}
?>
