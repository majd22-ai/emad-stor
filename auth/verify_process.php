<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
    session_start();
require_once '../includes/db_connect.php';

if (!isset($_SESSION['reset_email'])) {
    header('Location: ../pages/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = trim($_POST['token'] ?? '');
    $email = $_SESSION['reset_email'];

    if (empty($token) || strlen($token) !== 7) {
        $_SESSION['error'] = 'كود التحقق غير صحيح.';
        header('Location: ../pages/verify_code.php');
        exit;
    }

    // Check token in database
    $stmt = $pdo->prepare('SELECT * FROM password_resets WHERE email = ? AND token = ?');
    $stmt->execute([$email, $token]);
    $reset_record = $stmt->fetch();

    if ($reset_record) {
        // Check if expired
        $expires_at = strtotime($reset_record['expires_at']);
        $now = time();

        if ($now > $expires_at) {
            $_SESSION['error'] = 'انتهت صلاحية هذا الكود، الرجاء طلب كود جديد.';
            header('Location: ../pages/forgot_password.php');
            exit;
        } else {
            // Valid token, allow password reset
            $_SESSION['verified_token'] = $token; // Save token to prevent direct access to reset_password.php
            header('Location: ../pages/reset_password.php');
            exit;
        }
    } else {
        $_SESSION['error'] = 'كود التحقق غير صحيح، الرجاء التأكد والمحاولة مرة أخرى.';
        header('Location: ../pages/verify_code.php');
        exit;
    }
} else {
    header('Location: ../pages/verify_code.php');
    exit;
}
?>
