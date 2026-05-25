<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
    session_start();
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (empty($token) || empty($password) || empty($password_confirm)) {
        $_SESSION['error'] = 'جميع الحقول مطلوبة.';
        header('Location: ../pages/reset_password.php?token=' . urlencode($token));
        exit;
    }

    if ($password !== $password_confirm) {
        $_SESSION['error'] = 'كلمتا المرور غير متطابقتين.';
        header('Location: ../pages/reset_password.php?token=' . urlencode($token));
        exit;
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = 'كلمة المرور يجب أن تتكون من 6 أحرف على الأقل.';
        header('Location: ../pages/reset_password.php?token=' . urlencode($token));
        exit;
    }

    try {
        // التحقق من صحة الرمز مرة أخرى
        $stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if ($user) {
            // تشفير كلمة المرور الجديدة
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // تحديث كلمة المرور وإلغاء الرمز
            $updateStmt = $pdo->prepare("UPDATE users SET password_hash = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
            $updateStmt->execute([$password_hash, $user['id']]);

            $_SESSION['success'] = 'تم تغيير كلمة المرور بنجاح. يمكنك الآن تسجيل الدخول.';
            header('Location: ../pages/login.php');
            exit;
        } else {
            $_SESSION['error'] = 'رابط الاستعادة غير صالح أو منتهي الصلاحية.';
            header('Location: ../pages/reset_password.php?token=' . urlencode($token));
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'حدث خطأ أثناء الاتصال بقاعدة البيانات.';
        header('Location: ../pages/reset_password.php?token=' . urlencode($token));
        exit;
    }
} else {
    header('Location: ../pages/login.php');
    exit;
}
?>
