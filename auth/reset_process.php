<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
    session_start();
require_once '../includes/db_connect.php';

if (!isset($_SESSION['reset_email']) || !isset($_SESSION['verified_token'])) {
    header('Location: ../pages/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $email = $_SESSION['reset_email'];

    if (empty($new_password) || empty($confirm_password)) {
        $_SESSION['error'] = 'جميع الحقول مطلوبة.';
        header('Location: ../pages/reset_password.php');
        exit;
    }

    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = 'كلمتي المرور غير متطابقتين.';
        header('Location: ../pages/reset_password.php');
        exit;
    }

    if (strlen($new_password) < 6) {
        $_SESSION['error'] = 'يجب أن تكون كلمة المرور 6 أحرف أو أكثر.';
        header('Location: ../pages/reset_password.php');
        exit;
    }

    // Hash the new password
    $hash = password_hash($new_password, PASSWORD_DEFAULT);

    try {
        $pdo->beginTransaction();

        // Update password in users table
        $stmt = $pdo->prepare('UPDATE users SET password_hash = ? WHERE email = ?');
        $stmt->execute([$hash, $email]);

        // Delete the used token
        $stmt = $pdo->prepare('DELETE FROM password_resets WHERE email = ?');
        $stmt->execute([$email]);

        $pdo->commit();

        // Clear reset session variables
        unset($_SESSION['reset_email']);
        unset($_SESSION['verified_token']);

        // Set success message and redirect to login
        $_SESSION['success'] = 'تم تغيير كلمة المرور بنجاح، يمكنك الآن تسجيل الدخول.';
        header('Location: ../pages/login.php');
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = 'حدث خطأ أثناء تغيير كلمة المرور، يرجى المحاولة لاحقاً. ' . $e->getMessage();
        header('Location: ../pages/reset_password.php');
        exit;
    }
} else {
    header('Location: ../pages/reset_password.php');
    exit;
}
?>
