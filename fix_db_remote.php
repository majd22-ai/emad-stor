<?php
require_once 'includes/db_connect.php';

try {
    // محاولة إضافة العمود الناقص إذا لم يكن موجوداً
    $pdo->exec("ALTER TABLE password_resets ADD COLUMN expires_at TIMESTAMP NULL;");
    echo "تم تحديث قاعدة البيانات بنجاح: إضافة عمود expires_at.<br>";
} catch (PDOException $e) {
    echo "ملاحظة: " . $e->getMessage() . "<br>";
}

echo "<br>الآن يمكنك العودة للموقع وتجربة استعادة كلمة المرور.";
?>
