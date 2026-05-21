<?php
session_start();
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
check_admin();

// معالجة الإضافة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name']);
    $slug = trim($_POST['slug']);
    if (!empty($name) && !empty($slug)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
            $stmt->execute([$name, $slug]);
            $_SESSION['success'] = "تم إضافة القسم بنجاح.";
        } catch (PDOException $e) {
            $_SESSION['error'] = "حدث خطأ، ربما الرمز (Slug) مستخدم مسبقاً.";
        }
    }
    redirect('categories.php');
}

// معالجة الحذف
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['success'] = "تم حذف القسم بنجاح.";
    redirect('categories.php');
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الأقسام | لوحة التحكم</title>
    <link href="https://fonts.googleapis.com/css2?family=Playpen+Sans+Arabic:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { font-family: 'Playpen Sans Arabic', sans-serif; background-color: #f4f7f6; margin: 0; display: flex; }
        .sidebar { width: 250px; background-color: #0B1B2B; color: white; height: 100vh; padding-top: 20px; position: fixed; }
        .sidebar h2 { text-align: center; margin-bottom: 30px; font-size: 1.5rem; }
        .sidebar a { display: block; color: white; padding: 15px 20px; text-decoration: none; border-bottom: 1px solid #1a365d; }
        .sidebar a:hover { background-color: #1a365d; }
        .content { margin-right: 250px; padding: 40px; flex: 1; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 12px; text-align: right; }
        th { background-color: #f8f9fa; }
        .btn { padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; color: white; text-decoration: none; display: inline-block; }
        .btn-primary { background-color: #0B1B2B; }
        .btn-danger { background-color: #dc3545; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; }
        .alert-error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>لوحة التحكم</h2>
    <a href="index.php"><i class="fas fa-home"></i> الرئيسية</a>
    <a href="categories.php"><i class="fas fa-list"></i> إدارة الأقسام</a>
    <a href="products.php"><i class="fas fa-box"></i> إدارة المنتجات</a>
    <a href="../index.php"><i class="fas fa-store"></i> العودة للمتجر</a>
    <a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
</div>

<div class="content">
    <h1>إدارة الأقسام</h1>

    <?php
    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-error">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>

    <div class="card">
        <h3>إضافة قسم جديد</h3>
        <form method="POST">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label>اسم القسم (مثال: خواتم رجالية):</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>الرمز (Slug) بالإنجليزية (مثال: me-rings):</label>
                <input type="text" name="slug" required pattern="[a-zA-Z0-9\-]+">
            </div>
            <button type="submit" class="btn btn-primary">إضافة القسم</button>
        </form>
    </div>

    <div class="card">
        <h3>الأقسام الحالية</h3>
        <table>
            <thead>
                <tr>
                    <th>م</th>
                    <th>اسم القسم</th>
                    <th>الرمز (Slug)</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $index => $cat): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($cat['name']); ?></td>
                    <td><?php echo htmlspecialchars($cat['slug']); ?></td>
                    <td>
                        <a href="edit_category.php?id=<?php echo $cat['id']; ?>" class="btn btn-primary" style="background-color: #ffc107; color: black; margin-left: 5px;">تعديل</a>
                        <a href="?delete=<?php echo $cat['id']; ?>" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟ سيتم حذف جميع منتجات هذا القسم!');">حذف</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (count($categories) === 0): ?>
                <tr><td colspan="4" style="text-align:center;">لا توجد أقسام حالياً.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
