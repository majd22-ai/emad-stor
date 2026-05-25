<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
session_start();
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// حماية الصفحة
check_admin();

if (!isset($_GET['id'])) {
    exit('Invalid request');
}

$order_id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT payment_receipt FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$receipt = $stmt->fetchColumn();

if ($receipt) {
    if (strpos($receipt, 'data:') === 0) {
        // استخراج نوع الملف والبيانات المشفرة
        list($type, $data) = explode(';', $receipt);
        list(, $data)      = explode(',', $data);
        $type = str_replace('data:', '', $type);
        $data = base64_decode($data);
        
        header("Content-Type: $type");
        // Use inline to display in browser
        header("Content-Disposition: inline; filename=\"receipt_$order_id.$type\"");
        echo $data;
        exit;
    } else {
        // مسار ملف عادي
        header("Location: ../" . $receipt);
        exit;
    }
}

echo "لا يوجد إيصال لهذا الطلب.";
exit;
