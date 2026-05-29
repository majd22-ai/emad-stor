<?php
$host = 'ep-tiny-wave-aqa2wj71-pooler.c-8.us-east-1.aws.neon.tech';
$db   = 'neondb';
$user = 'neondb_owner';
$pass = 'npg_NZPcHVn5UqI8';
$port = '5432'; // المنفذ الافتراضي لـ PostgreSQL

$dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => true, // Required for Neon PgBouncer pooler
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // تسجيل الخطأ في السيرفر دون إظهار تفاصيل القاعدة للمستخدم النهائي (Security: Information Disclosure Prevention)
    error_log("Database Connection Error: " . $e->getMessage());
    die("عذراً، حدث خطأ أثناء الاتصال بقاعدة البيانات. الرجاء المحاولة لاحقاً.");
}
?>
