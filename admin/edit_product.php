<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
    session_start();
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
check_admin();

if (!isset($_GET['id'])) {
    redirect('products.php');
}

$id = (int)$_GET['id'];

// جلب بيانات المنتج
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    $_SESSION['error'] = "المنتج غير موجود.";
    redirect('products.php');
}

// جلب الأقسام
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

// معالجة التعديل
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $category_id = (int)$_POST['category_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    
    // معالجة رفع الصورة إن وجدت وتحويلها إلى Base64
    $image_url = $product['image_url'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'webp');
        
        if (in_array($fileType, $allowTypes)) {
            $imageContent = file_get_contents($_FILES['image']['tmp_name']);
            if ($imageContent !== false) {
                $base64 = base64_encode($imageContent);
                $mime = mime_content_type($_FILES['image']['tmp_name']);
                if (!$mime) {
                    $mime = 'image/' . ($fileType === 'jpg' ? 'jpeg' : $fileType);
                }
                $image_url = 'data:' . $mime . ';base64,' . $base64;
            } else {
                $error = "فشل في قراءة الصورة الجديدة.";
            }
        } else {
            $error = "عذراً، فقط صيغ JPG, JPEG, PNG, GIF مسموحة.";
        }
    }

    if (!isset($error) && !empty($title) && $price > 0 && $category_id > 0) {
        try {
            $updateStmt = $pdo->prepare("UPDATE products SET category_id = ?, title = ?, description = ?, price = ?, image_url = ? WHERE id = ?");
            $updateStmt->execute([$category_id, $title, $description, $price, $image_url, $id]);
            $_SESSION['success'] = "تم تعديل المنتج بنجاح.";
            redirect('products.php');
        } catch (PDOException $e) {
            $error = "حدث خطأ أثناء التعديل: " . $e->getMessage();
        }
    } elseif (!isset($error)) {
        $error = "يرجى تعبئة الحقول الإلزامية بشكل صحيح.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل منتج | لوحة التحكم</title>
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
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-family: inherit; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .alert-error { background-color: #f8d7da; color: #721c24; }
        .prod-img-preview { width: 100px; height: 100px; object-fit: cover; border-radius: 4px; margin-bottom: 10px; display: block; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>لوحة التحكم</h2>
    <a href="index.php"><i class="fas fa-home"></i> الرئيسية</a>
    <a href="categories.php"><i class="fas fa-list"></i> إدارة الأقسام</a>
    <a href="products.php"><i class="fas fa-box"></i> إدارة المنتجات</a>
    <a href="coupons.php"><i class="fas fa-tags"></i> إدارة الكوبونات</a>
    <a href="users.php"><i class="fas fa-users"></i> إدارة المستخدمين</a>
    <a href="../index.php"><i class="fas fa-store"></i> العودة للمتجر</a>
    <a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
</div>

<div class="content">
    <h1>تعديل المنتج</h1>

    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="edit">
            <div class="form-group">
                <label>القسم:</label>
                <select name="category_id" required>
                    <option value="">اختر القسم...</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id'] == $product['category_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>عنوان/اسم المنتج:</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($product['title']); ?>" required>
            </div>
            <div class="form-group">
                <label>السعر ($):</label>
                <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>
            <div class="form-group">
                <label>الوصف (اختياري):</label>
                <textarea name="description" rows="3"><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label>صورة المنتج الحالية:</label>
                <?php if (!empty($product['image_url'])): ?>
                    <?php if (strpos($product['image_url'], 'data:image') === 0): ?>
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="صورة المنتج" class="prod-img-preview">
                    <?php else: ?>
                        <img src="../<?php echo htmlspecialchars($product['image_url']); ?>" alt="صورة المنتج" class="prod-img-preview">
                    <?php endif; ?>
                <?php else: ?>
                    <p>بدون صورة</p>
                <?php endif; ?>
                <label>تغيير الصورة (اختياري):</label>
                <input type="file" name="image" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
            <a href="products.php" class="btn btn-secondary">إلغاء والعودة</a>
        </form>
    </div>
</div>

</body>
</html>
