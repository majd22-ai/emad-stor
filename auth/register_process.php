<?php
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

    // Insert new user
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)');
    if ($stmt->execute([$name, $email, $hash])) {
        $_SESSION['success'] = 'تم إنشاء الحساب بنجاح، يمكنك تسجيل الدخول الآن.';
        header('Location: ../pages/login.php');
        exit;
    } else {
        $_SESSION['error'] = 'حدث خطأ أثناء التسجيل، حاول مرة أخرى.';
        header('Location: ../pages/register.php');
        exit;
    }
}
?>
