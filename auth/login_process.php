<?php
session_start();
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'البريد الإلكتروني وكلمة المرور مطلوبان.';
        header('Location: ../pages/login.php');
        exit;
    }

    // البحث عن المستخدم
    $stmt = $pdo->prepare('SELECT id, full_name, password_hash, role FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        // تسجيل الدخول بنجاح
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
