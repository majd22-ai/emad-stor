<?php
require 'includes/db_connect.php';
$stmt = $pdo->query("SELECT NOW()");
print_r($stmt->fetchAll());
?>
