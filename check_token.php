<?php
require 'includes/db_connect.php';
$stmt = $pdo->query("SELECT reset_token, reset_token_expiry FROM users WHERE reset_token IS NOT NULL");
print_r($stmt->fetchAll());
?>
