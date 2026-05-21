<?php
require_once __DIR__ . '/includes/db_connect.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email']);
    
    // التحقق من وجود المستخدم
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) {
        // ترقية المستخدم إلى مدير
        $updateStmt = $pdo->prepare("UPDATE users SET role = 'admin' WHERE email = ?");
        if ($updateStmt->execute([$email])) {
            $message = "<div style='color: green;'>✅ تم ترقية الحساب ($email) إلى مدير بنجاح! يمكنك الآن تسجيل الدخول والذهاب للوحة التحكم.</div>";
        } else {
            $message = "<div style='color: red;'>❌ حدث خطأ أثناء الترقية.</div>";
        }
    } else {
        $message = "<div style='color: red;'>❌ هذا البريد الإلكتروني غير مسجل في النظام. يرجى إنشاء حساب أولاً.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ترقية حساب إلى مدير</title>
    <style>
        body { font-family: Tahoma, Arial, sans-serif; background: #f4f7fa; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .box { background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 400px; text-align: center; }
        input { width: 90%; padding: 10px; margin: 15px 0; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; }
        button { background: #0B1B2B; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px; width: 100%; }
        button:hover { background: #1E3A5F; }
    </style>
</head>
<body>
    <div class="box">
        <h2>🛠️ أداة ترقية حساب المدير</h2>
        <p>أدخل بريدك الإلكتروني (الذي قمت بإنشاء حساب به في المتجر) لترقيته إلى حساب مدير.</p>
        <?php echo $message; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="البريد الإلكتروني لحسابك..." required>
            <button type="submit">ترقية إلى مدير 👑</button>
        </form>
        <div style="margin-top: 20px;">
            <a href="index.php" style="color: #0B1B2B;">العودة للمتجر</a>
        </div>
    </div>
</body>
</html>