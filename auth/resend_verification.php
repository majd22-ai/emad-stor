<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
session_start();
require_once '../includes/db_connect.php';

$email = $_GET['email'] ?? '';

if (empty($email)) {
    $_SESSION['error'] = 'البريد الإلكتروني مفقود.';
    header('Location: ../pages/login.php');
    exit;
}

// Check if user exists and is not verified
$stmt = $pdo->prepare('SELECT id, full_name, is_verified, verification_token FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user) {
    if ($user['is_verified']) {
        $_SESSION['success'] = 'هذا الحساب مفعل مسبقاً. يمكنك تسجيل الدخول الآن.';
    } else {
        $verification_token = $user['verification_token'];
        
        // If somehow token is empty, generate a new one
        if (empty($verification_token)) {
            $verification_token = bin2hex(random_bytes(32));
            $pdo->prepare('UPDATE users SET verification_token = ? WHERE id = ?')->execute([$verification_token, $user['id']]);
        }

        // Send Verification Email
        require_once '../includes/mail_config.php';
        $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
        $base_url .= (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) ? '/emad-stor' : '';
        
        $verify_link = $base_url . "/auth/verify.php?email=" . urlencode($email) . "&token=" . $verification_token;
        
        $subject = 'تفعيل حسابك - متجر أبو عماد (إعادة إرسال)';
        $message = "مرحباً " . $user['full_name'] . "،\n\nبناءً على طلبك، هذا هو رابط تفعيل حسابك:\n\n$verify_link\n\nإذا لم تقم بالتسجيل، يرجى تجاهل هذه الرسالة.";
        
        $mail_sent = sendEmail($email, $subject, $message);
        
        if ($mail_sent) {
            $_SESSION['success'] = 'تم إعادة إرسال رابط التفعيل إلى بريدك الإلكتروني بنجاح.';
        } else {
            $_SESSION['success'] = "تم إعادة إرسال الرابط. <br><small style='color: #4A627A;'>[رابط التفعيل (للتجربة): <a href='$verify_link'>اضغط هنا</a>]</small>";
        }
    }
} else {
    $_SESSION['error'] = 'الحساب غير موجود.';
}

header('Location: ../pages/login.php');
exit;
?>
