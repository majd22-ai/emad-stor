<?php
session_start();
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
check_admin();

if (!isset($_GET['id'])) {
    redirect('categories.php');
}

$id = (int)$_GET['id'];

// جلب بيانات القسم
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch();

if (!$category) {
    $_SESSION['error'] = "القسم غير موجود.";
    redirect('categories.php');
}

// معالجة التعديل
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $name = trim($_POST['name']);
    $slug = trim($_POST['slug']);
    
    if (!empty($name) && !empty($slug)) {
        try {
            $updateStmt = $pdo->prepare("UPDATE categories SET name = ?, slug = ? WHERE id = ?");
            $updateStmt->execute([$name, $slug, $id]);
            $_SESSION['success'] = "تم تعديل القسم بنجاح.";
            redirect('categories.php');
        } catch (PDOException $e) {
            $error = "حدث خطأ، ربما الرمز (Slug) مستخدم مسبقاً لقسم آخر.";
        }
    } else {
        $error = "يرجى تعبئة جميع الحقول.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل قسم | لوحة التحكم</title>
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
        .btn { padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; color: white; text-decoration: none; display: inline-block; }
        .btn-primary { background-color: #0B1B2B; }
        .btn-secondary { background-color: #6c757d; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
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
    <h1>تعديل القسم</h1>

    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card">
        <form method="POST">
            <input type="hidden" name="action" value="edit">
            <div class="form-group">
                <label>اسم القسم:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
            </div>
            <div class="form-group">
                <label>الرمز (Slug) بالإنجليزية:</label>
                <input type="text" name="slug" value="<?php echo htmlspecialchars($category['slug']); ?>" required pattern="[a-zA-Z0-9\-]+">
            </div>
            <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
            <a href="categories.php" class="btn btn-secondary">إلغاء والعودة</a>
        </form>
    </div>
</div>

</body>
</html>
