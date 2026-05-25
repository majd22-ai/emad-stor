<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
    session_start();
require_once '../includes/db_connect.php';
require_once '../includes/oauth_config.php';

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // الحصول على رمز الوصول (Access Token)
    $tokenUrl = 'https://oauth2.googleapis.com/token';
    $postData = [
        'code' => $code,
        'client_id' => GOOGLE_CLIENT_ID,
        'client_secret' => GOOGLE_CLIENT_SECRET,
        'redirect_uri' => GOOGLE_REDIRECT_URL,
        'grant_type' => 'authorization_code'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $tokenUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $tokenData = json_decode($response, true);

    if (isset($tokenData['access_token'])) {
        $accessToken = $tokenData['access_token'];

        // جلب بيانات المستخدم من Google
        $userInfoUrl = 'https://www.googleapis.com/oauth2/v2/userinfo';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $userInfoUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
        $userInfoResponse = curl_exec($ch);
        curl_close($ch);

        $googleUser = json_decode($userInfoResponse, true);

        if (isset($googleUser['id'])) {
            $oauth_uid = $googleUser['id'];
            $email = $googleUser['email'];
            $full_name = $googleUser['name'];

            // التحقق مما إذا كان المستخدم موجوداً مسبقاً
            $stmt = $pdo->prepare("SELECT id, full_name, role FROM users WHERE oauth_provider = 'google' AND oauth_uid = ?");
            $stmt->execute([$oauth_uid]);
            $user = $stmt->fetch();

            if (!$user) {
                // قد يكون مسجلاً مسبقاً بنفس الإيميل بطريقة عادية
                $stmt = $pdo->prepare("SELECT id, full_name, role FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $userByEmail = $stmt->fetch();

                if ($userByEmail) {
                    // تحديث الحساب ليرتبط بجوجل
                    $stmt = $pdo->prepare("UPDATE users SET oauth_provider = 'google', oauth_uid = ? WHERE email = ?");
                    $stmt->execute([$oauth_uid, $email]);
                    $user_id = $userByEmail['id'];
                    $user_name = $userByEmail['full_name'];
                    $user_role = $userByEmail['role'];
                } else {
                    // حساب جديد بالكامل
                    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, oauth_provider, oauth_uid, role) VALUES (?, ?, 'google', ?, 'customer')");
                    $stmt->execute([$full_name, $email, $oauth_uid]);
                    $user_id = $pdo->lastInsertId();
                    $user_name = $full_name;
                    $user_role = 'customer';
                }
            } else {
                $user_id = $user['id'];
                $user_name = $user['full_name'];
                $user_role = $user['role'];
            }

            // إنشاء الجلسة للمستخدم
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $user_name;
            $_SESSION['user_role'] = $user_role;
            $_SESSION['is_logged_in'] = true;

            header('Location: ../index.php');
            exit;
        }
    }
}

$_SESSION['error'] = 'فشل تسجيل الدخول عبر جوجل.';
header('Location: ../pages/login.php');
exit;
?>
