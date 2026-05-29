<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
    session_start();
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

check_admin();

$base_url = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) ? '/emad-stor/' : '/';
$message = '';

// إضافة كود جديد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_coupon'])) {
    $code = strtoupper(trim($_POST['code'] ?? ''));
    $discount = (int)($_POST['discount'] ?? 0);
    $limit = (int)($_POST['limit'] ?? 100);

    if (empty($code) || $discount <= 0 || $discount > 100) {
        $message = '<div class="alert alert-danger">الرجاء إدخال بيانات صحيحة (نسبة الخصم بين 1 و 100).</div>';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO coupons (code, discount_percent, usage_limit) VALUES (?, ?, ?)');
            $stmt->execute([$code, $discount, $limit]);
            $message = '<div class="alert alert-success">تمت إضافة كود الخصم بنجاح.</div>';
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">الكود موجود مسبقاً أو حدث خطأ.</div>';
        }
    }
}

// حذف كود
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare('DELETE FROM coupons WHERE id = ?')->execute([$id]);
    header('Location: coupons.php');
    exit;
}

// جلب جميع الكوبونات
$stmt = $pdo->query('SELECT * FROM coupons ORDER BY id DESC');
$coupons = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الكوبونات | فضيات ابو عماد</title>
    <link href="https://fonts.googleapis.com/css2?family=Playpen+Sans+Arabic:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { font-family: 'Playpen Sans Arabic', sans-serif; background-color: #f4f7f6; margin: 0; display: flex; }
        .sidebar { width: 250px; background-color: #0B1B2B; color: white; height: 100vh; padding-top: 20px; position: fixed; right: 0; top: 0; }
        .sidebar h2 { text-align: center; margin-bottom: 30px; font-size: 1.5rem; }
        .sidebar a { display: block; color: white; padding: 15px 20px; text-decoration: none; border-bottom: 1px solid #1a365d; }
        .sidebar a:hover, .sidebar a.active { background-color: #1a365d; }
        .content { flex: 1; padding: 40px; margin-right: 250px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 20px; }
        h1, h2 { color: #0B1B2B; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: right; border-bottom: 1px solid #ddd; }
        th { background-color: #0B1B2B; color: white; }
        .btn { padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; color: white; font-family: inherit; font-weight: bold; }
        .btn-primary { background-color: #FFD966; color: #0B1B2B; }
        .btn-danger { background-color: #dc3545; }
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
        .alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
        .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .input-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-family: inherit; }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="content">
    <h1>إدارة الكوبونات (Promo Codes)</h1>
    
    <?php echo $message; ?>

    <div style="display: flex; gap: 20px; align-items: flex-start;">
        <div class="card" style="flex: 1;">
            <h2>إضافة كود جديد</h2>
            <form action="coupons.php" method="POST">
                <div class="input-group">
                    <label>رمز الكود (مثال: EMAD20)</label>
                    <input type="text" name="code" required style="text-transform: uppercase;">
                </div>
                <div class="input-group">
                    <label>نسبة الخصم (%)</label>
                    <input type="number" name="discount" min="1" max="100" required>
                </div>
                <div class="input-group">
                    <label>الحد الأقصى للاستخدام (عدد المرات)</label>
                    <input type="number" name="limit" value="100" min="1" required>
                </div>
                <button type="submit" name="add_coupon" class="btn btn-primary" style="width: 100%;">إضافة الكود</button>
            </form>
        </div>

        <div class="card" style="flex: 2;">
            <h2>الأكواد الحالية</h2>
            <?php if (empty($coupons)): ?>
                <p>لا توجد أكواد خصم حالياً.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>الكود</th>
                            <th>الخصم</th>
                            <th>الاستخدامات</th>
                            <th>تاريخ الإضافة</th>
                            <th>إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($coupons as $c): ?>
                            <tr>
                                <td><span style="background: #e2e8f0; padding: 5px 10px; border-radius: 4px; font-weight: bold;"><?php echo htmlspecialchars($c['code']); ?></span></td>
                                <td><span style="color: #28a745; font-weight: bold;"><?php echo $c['discount_percent']; ?>%</span></td>
                                <td><?php echo $c['used_count'] . ' / ' . $c['usage_limit']; ?></td>
                                <td><?php echo date('Y-m-d', strtotime($c['created_at'])); ?></td>
                                <td>
                                    <a href="coupons.php?delete=<?php echo $c['id']; ?>" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا الكود؟');"><i class="fas fa-trash"></i> حذف</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
