<?php
$host = 'localhost';
$db   = 'emad_db';
$user = 'postgres';
$pass = '1234';
$port = '5432'; // المنفذ الافتراضي لـ PostgreSQL

$dsn = "pgsql:host=$host;port=$port;dbname=$db;";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // تسجيل الخطأ في السيرفر دون إظهار تفاصيل القاعدة للمستخدم النهائي (Security: Information Disclosure Prevention)
    error_log("Database Connection Error: " . $e->getMessage());
    die("عذراً، حدث خطأ أثناء الاتصال بقاعدة البيانات. الرجاء المحاولة لاحقاً.");
}
?>
