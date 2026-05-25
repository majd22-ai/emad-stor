<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
    session_start();
$_SESSION['currency'] = 'SAR';
require_once 'includes/functions.php';

// Mock variables for header.php
$_SERVER['HTTP_HOST'] = 'emad-stor.onrender.com';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['HTTPS'] = 'on';

// Mock DB connection just enough to not crash if possible, or we just test header.php
$pdo = new PDO('sqlite::memory:');

ob_start();
include 'includes/header.php';
$output = ob_get_clean();

file_put_contents('test_output.html', $output);
echo "Wrote output to test_output.html\n";
?>
