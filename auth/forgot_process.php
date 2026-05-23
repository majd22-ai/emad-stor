<?php
session_start();
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $_SESSION['error'] = 'الرجاء إدخال البريد الإلكتروني.';
        header('Location: ../pages/forgot_password.php');
        exit;
    }

    // Check if email exists in users table
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate 7-digit random code
        $token = str_pad(rand(0, 9999999), 7, '0', STR_PAD_LEFT);
        
        // Expiration time: 15 minutes from now
        $expires_at = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        // First, delete any existing reset codes for this email to prevent spam
        $pdo->prepare('DELETE FROM password_resets WHERE email = ?')->execute([$email]);

        // Insert new token
        $stmt = $pdo->prepare('INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)');
        if ($stmt->execute([$email, $token, $expires_at])) {
            
            // Prepare Email
            $subject = 'كود استعادة كلمة المرور - متجر أبو عماد';
            $message = "مرحباً،\n\nلقد طلبت استعادة كلمة المرور الخاصة بحسابك.\nكود التحقق الخاص بك هو: $token\n\nهذا الكود صالح لمدة 15 دقيقة فقط.\nإذا لم تطلب هذا، يرجى تجاهل هذه الرسالة.";
            $headers = 'From: noreply@emad-stor.com' . "\r\n" .
                       'Reply-To: noreply@emad-stor.com' . "\r\n" .
                       'X-Mailer: PHP/' . phpversion();

            // Attempt to send email
            $mail_sent = @mail($email, $subject, $message, $headers);

            $_SESSION['reset_email'] = $email; // Store email in session to use in next step
            $_SESSION['success'] = "تم إرسال كود التحقق إلى بريدك الإلكتروني بنجاح.";
            header('Location: ../pages/verify_code.php');
            exit;
        } else {
            $_SESSION['error'] = 'حدث خطأ أثناء معالجة طلبك، يرجى المحاولة لاحقاً.';
        }
    } else {
        // We shouldn't reveal if the email exists or not for security reasons ideally, 
        // but for better UX we tell them it's not registered.
        $_SESSION['error'] = 'البريد الإلكتروني غير مسجل لدينا.';
    }

    header('Location: ../pages/forgot_password.php');
    exit;
} else {
    header('Location: ../pages/login.php');
    exit;
}
?>
