<?php
require 'includes/db_connect.php';
$stmt = $pdo->query("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'orders'");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
