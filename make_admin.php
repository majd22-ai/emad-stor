<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
    session_start();
require 'includes/db_connect.php';

// التأكد من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    die("يجب عليك تسجيل الدخول أولاً في الموقع لكي تصبح أدمن.");
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
    $stmt->execute([$user_id]);
    
    // تحديث الجلسة
    $_SESSION['user_role'] = 'admin';
    
    echo "<h1>تمت الترقية بنجاح!</h1>";
    echo "<p>حسابك الآن يمتلك صلاحيات الأدمن. <a href='admin/index.php'>اضغط هنا للذهاب للوحة التحكم</a></p>";
} catch (Exception $e) {
    echo "حدث خطأ: " . $e->getMessage();
}
?>
