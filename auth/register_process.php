<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
    session_start();
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../includes/functions.php';
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $_SESSION['error'] = 'انتهت صلاحية الجلسة (CSRF). يرجى المحاولة مجدداً.';
        header('Location: ../pages/register.php');
        exit;
    }

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['error'] = 'جميع الحقول مطلوبة.';
        header('Location: ../pages/register.php');
        exit;
    }

    // Check if email already exists
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = 'البريد الإلكتروني مسجل بالفعل.';
        header('Location: ../pages/register.php');
        exit;
    }

    // Generate verification token
    $verification_token = bin2hex(random_bytes(32));

    // Insert new user (is_verified defaults to FALSE)
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (full_name, email, password_hash, verification_token, is_verified) VALUES (?, ?, ?, ?, FALSE)');
    if ($stmt->execute([$name, $email, $hash, $verification_token])) {
        // Send Verification Email
        require_once '../includes/mail_config.php';
        $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
        $base_url .= (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) ? '/emad-stor' : '';
        
        $verify_link = $base_url . "/auth/verify.php?email=" . urlencode($email) . "&token=" . $verification_token;
        
        $subject = 'تفعيل حسابك - متجر أبو عماد';
        $message = "مرحباً $name،\n\nشكراً لتسجيلك في متجرنا!\nالرجاء الضغط على الرابط التالي لتفعيل حسابك:\n\n$verify_link\n\nإذا لم تقم بالتسجيل، يرجى تجاهل هذه الرسالة.";
        
        $mail_sent = sendEmail($email, $subject, $message);
        
        if ($mail_sent) {
            $_SESSION['success'] = 'تم إنشاء الحساب بنجاح. لقد أرسلنا رابط التفعيل إلى بريدك الإلكتروني، يرجى تفعيل حسابك لتتمكن من تسجيل الدخول.';
        } else {
            // Fallback for local testing if mail fails
            $_SESSION['success'] = "تم إنشاء الحساب بنجاح. <br><small style='color: #4A627A;'>[رابط التفعيل (للتجربة): <a href='$verify_link'>اضغط هنا</a>]</small>";
        }
        
        header('Location: ../pages/login.php');
        exit;
    } else {
        $_SESSION['error'] = 'حدث خطأ أثناء التسجيل، حاول مرة أخرى.';
        header('Location: ../pages/register.php');
        exit;
    }
}
?>
