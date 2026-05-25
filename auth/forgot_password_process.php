<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
    session_start();
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $_SESSION['error'] = 'الرجاء إدخال البريد الإلكتروني.';
        header('Location: ../pages/forgot_password.php');
        exit;
    }

    try {
        // التحقق من وجود البريد الإلكتروني
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // إنشاء رمز سري عشوائي
            $token = bin2hex(random_bytes(32));
            
            // حفظ الرمز في قاعدة البيانات مع تحديد الانتهاء بعد ساعة من الآن بتوقيت السيرفر
            $updateStmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = NOW() + INTERVAL '1 hour' WHERE id = ?");
            $updateStmt->execute([$token, $user['id']]);

            // إنشاء رابط الاستعادة
            // نستخدم HTTP_HOST و SCRIPT_NAME لمعرفة المسار الأساسي
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
            $host = $_SERVER['HTTP_HOST'];
            $base_dir = dirname($_SERVER['SCRIPT_NAME'], 2); // نعود مجلدين للوراء
            
            $reset_url = $protocol . "://" . $host . $base_dir . "/pages/reset_password.php?token=" . $token;

            // في بيئة الإنتاج الفعلية يجب إرسال بريد إلكتروني هنا باستخدام mail() أو PHPMailer
            /*
            $subject = 'استعادة كلمة المرور - متجر ابو عماد';
            $message = "مرحباً،\nلقد طلبت استعادة كلمة المرور الخاصة بك. يرجى الضغط على الرابط التالي لإنشاء كلمة مرور جديدة:\n" . $reset_url . "\n\nإذا لم تطلب ذلك، يرجى تجاهل هذه الرسالة.";
            $headers = 'From: noreply@emad-stor.com' . "\r\n" .
                       'Reply-To: support@emad-stor.com' . "\r\n" .
                       'X-Mailer: PHP/' . phpversion();
            mail($email, $subject, $message, $headers);
            */

            // من أجل الاختبار المحلي سنعرض الرابط في الصفحة
            $_SESSION['success'] = 'تم إنشاء رابط استعادة كلمة المرور بنجاح. في بيئة حقيقية سيتم إرساله لبريدك.';
            $_SESSION['reset_link'] = $reset_url; 
        } else {
            // من الأفضل أمنياً عدم تأكيد ما إذا كان البريد موجوداً أم لا للمهاجمين
            // ولكن لتسهيل تجربة المستخدم نظهر رسالة
            $_SESSION['error'] = 'لا يوجد حساب مسجل بهذا البريد الإلكتروني.';
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'حدث خطأ أثناء معالجة الطلب: ' . $e->getMessage();
    }

    header('Location: ../pages/forgot_password.php');
    exit;
} else {
    header('Location: ../pages/forgot_password.php');
    exit;
}
?>
