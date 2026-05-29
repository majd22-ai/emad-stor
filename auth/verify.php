<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
session_start();
require_once '../includes/db_connect.php';

$email = $_GET['email'] ?? '';
$token = $_GET['token'] ?? '';

if (empty($email) || empty($token)) {
    $_SESSION['error'] = 'رابط التفعيل غير صالح.';
    header('Location: ../pages/login.php');
    exit;
}

// Check if user exists with this token
$stmt = $pdo->prepare('SELECT id, is_verified FROM users WHERE email = ? AND verification_token = ?');
$stmt->execute([$email, $token]);
$user = $stmt->fetch();

if ($user) {
    if ($user['is_verified']) {
        $_SESSION['success'] = 'هذا الحساب مفعل مسبقاً. يمكنك تسجيل الدخول الآن.';
    } else {
        // Update user to verified and remove token
        $update_stmt = $pdo->prepare('UPDATE users SET is_verified = TRUE, verification_token = NULL WHERE id = ?');
        if ($update_stmt->execute([$user['id']])) {
            $_SESSION['success'] = 'تم تفعيل حسابك بنجاح! يمكنك الآن تسجيل الدخول.';
        } else {
            $_SESSION['error'] = 'حدث خطأ أثناء التفعيل، يرجى المحاولة لاحقاً.';
        }
    }
} else {
    $_SESSION['error'] = 'رابط التفعيل غير صحيح أو منتهي الصلاحية.';
}

header('Location: ../pages/login.php');
exit;
?>
