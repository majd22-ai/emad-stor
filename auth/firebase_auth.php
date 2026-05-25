<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
    session_start();
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['uid'])) {
    echo json_encode(['status' => 'error', 'message' => 'بيانات تسجيل الدخول غير مكتملة']);
    exit;
}

$uid = $data['uid'];
// بعض حسابات فيسبوك لا تعطي إيميل (مسجلة برقم هاتف)، لذلك نضع إيميل افتراضي
$email = isset($data['email']) && !empty($data['email']) ? $data['email'] : $uid . '@facebook-user.local';
$name = isset($data['displayName']) && !empty($data['displayName']) ? $data['displayName'] : 'مستخدم Firebase';

try {
    $stmt = $pdo->prepare('SELECT id, full_name, role FROM users WHERE email = ? OR oauth_uid = ?');
    $stmt->execute([$email, $uid]);
    $user = $stmt->fetch();

    if ($user) {
        // تسجيل الدخول
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_role'] = $user['role'] ?? 'customer';
        $_SESSION['is_logged_in'] = true;
    } else {
        // إنشاء حساب جديد
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, oauth_provider, oauth_uid, role) VALUES (?, ?, 'firebase', ?, 'customer') RETURNING id");
        $stmt->execute([$name, $email, $uid]);
        $new_id = $stmt->fetchColumn();

        session_regenerate_id(true);
        $_SESSION['user_id'] = $new_id;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_role'] = 'customer';
        $_SESSION['is_logged_in'] = true;
    }

    echo json_encode(['status' => 'success', 'message' => 'تم تسجيل الدخول بنجاح']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'حدث خطأ في قاعدة البيانات: ' . $e->getMessage()]);
}
?>
