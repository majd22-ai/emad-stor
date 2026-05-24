<?php
session_start();
require 'includes/db_connect.php';

try {
    // ترقية جميع المستخدمين إلى مدير
    $stmt = $pdo->prepare("UPDATE users SET role = 'admin'");
    $stmt->execute();
    
    // تحديث الجلسة الحالية
    $_SESSION['user_role'] = 'admin';
    
    echo "<h1>تمت الترقية بنجاح! جميع الحسابات أصبحت أدمن.</h1>";
    echo "<p><a href='admin/index.php'>اضغط هنا للذهاب للوحة التحكم</a></p>";
} catch (Exception $e) {
    echo "حدث خطأ: " . $e->getMessage();
}
?>
