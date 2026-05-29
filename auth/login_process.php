<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
    session_start();
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../includes/functions.php';
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $_SESSION['error'] = 'انتهت صلاحية الجلسة (CSRF). يرجى تحديث الصفحة والمحاولة مجدداً.';
        header('Location: ../pages/login.php');
        exit;
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'البريد الإلكتروني وكلمة المرور مطلوبان.';
        header('Location: ../pages/login.php');
        exit;
    }

    // البحث عن المستخدم
    $stmt = $pdo->prepare('SELECT id, full_name, password_hash, role, is_verified FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'] ?? '')) {
        if (isset($user['is_verified']) && !$user['is_verified']) {
            $_SESSION['error'] = 'حسابك غير مفعل. الرجاء مراجعة بريدك الإلكتروني لتفعيل الحساب. <br><a href="../auth/resend_verification.php?email=' . urlencode($email) . '" style="color:#1E3A5F; font-weight:bold; text-decoration:underline; margin-top:5px; display:inline-block;">إعادة إرسال رابط التفعيل</a>';
            header('Location: ../pages/login.php');
            exit;
        }
        // تسجيل الدخول بنجاح (منع Session Fixation)
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['is_logged_in'] = true;

        header('Location: ../index.php');
        exit;
    } else {
        // بيانات الاعتماد خاطئة
        $_SESSION['error'] = 'البريد الإلكتروني أو كلمة المرور غير صحيحة.';
        header('Location: ../pages/login.php');
        exit;
    }
}
?>
