<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
    session_start();
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
check_admin();

// جلب الأقسام لاستخدامها في نموذج الإضافة
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

// معالجة إضافة منتج
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $category_id = (int)$_POST['category_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    
    // معالجة رفع الصورة
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/images/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = time() . '_' . basename($_FILES['image']['name']);
        $targetFilePath = $uploadDir . $fileName;
        
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        
        if (in_array(strtolower($fileType), $allowTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $image_url = 'assets/images/products/' . $fileName;
            } else {
                $_SESSION['error'] = "فشل في رفع الصورة.";
            }
        } else {
            $_SESSION['error'] = "عذراً، فقط صيغ JPG, JPEG, PNG, GIF مسموحة.";
        }
    }

    if (!isset($_SESSION['error']) && !empty($title) && $price > 0 && $category_id > 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO products (category_id, title, description, price, image_url) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$category_id, $title, $description, $price, $image_url]);
            $_SESSION['success'] = "تم إضافة المنتج بنجاح.";
        } catch (PDOException $e) {
            $_SESSION['error'] = "حدث خطأ أثناء الإضافة: " . $e->getMessage();
        }
    } elseif (!isset($_SESSION['error'])) {
        $_SESSION['error'] = "يرجى تعبئة الحقول الإلزامية.";
    }
    redirect('products.php');
}

// معالجة الحذف
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // سحب رابط الصورة لحذفها من السيرفر
    $stmt = $pdo->prepare("SELECT image_url FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $prod = $stmt->fetch();
    if ($prod && !empty($prod['image_url'])) {
        $filePath = '../' . $prod['image_url'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['success'] = "تم حذف المنتج بنجاح.";
    redirect('products.php');
}

// جلب المنتجات مع اسم القسم
$products = $pdo->query("
    SELECT p.*, c.name as category_name 
    FROM products p 
    JOIN categories c ON p.category_id = c.id 
    ORDER BY p.id DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة المنتجات | لوحة التحكم</title>
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
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-family: inherit; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; }
        .alert-error { background-color: #f8d7da; color: #721c24; }
        .prod-img { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>لوحة التحكم</h2>
    <a href="index.php"><i class="fas fa-home"></i> الرئيسية</a>
    <a href="categories.php"><i class="fas fa-list"></i> إدارة الأقسام</a>
    <a href="products.php" class="active"><i class="fas fa-box"></i> إدارة المنتجات</a>
    <a href="orders.php"><i class="fas fa-shopping-cart"></i> إدارة الطلبات</a>
    <a href="coupons.php"><i class="fas fa-tags"></i> إدارة الكوبونات</a>
    <a href="users.php"><i class="fas fa-users"></i> إدارة المستخدمين</a>
    <a href="../index.php"><i class="fas fa-store"></i> العودة للمتجر</a>
    <a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
</div>

<div class="content">
    <h1>إدارة المنتجات</h1>

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
        <h3>إضافة منتج جديد</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label>القسم:</label>
                <select name="category_id" required>
                    <option value="">اختر القسم...</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>عنوان/اسم المنتج:</label>
                <input type="text" name="title" required>
            </div>
            <div class="form-group">
                <label>السعر ($):</label>
                <input type="number" step="0.01" name="price" required>
            </div>
            <div class="form-group">
                <label>الوصف (اختياري):</label>
                <textarea name="description" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>صورة المنتج:</label>
                <input type="file" name="image" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">إضافة المنتج</button>
        </form>
    </div>

    <div class="card">
        <h3>المنتجات الحالية</h3>
        <table>
            <thead>
                <tr>
                    <th>صورة</th>
                    <th>المنتج</th>
                    <th>القسم</th>
                    <th>السعر</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $prod): ?>
                <tr>
                    <td>
                        <?php if (!empty($prod['image_url'])): ?>
                            <img src="../<?php echo htmlspecialchars($prod['image_url']); ?>" alt="img" class="prod-img">
                        <?php else: ?>
                            <span>بدون صورة</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($prod['title']); ?></td>
                    <td><?php echo htmlspecialchars($prod['category_name']); ?></td>
                    <td><?php echo htmlspecialchars($prod['price']); ?>$</td>
                    <td>
                        <a href="edit_product.php?id=<?php echo $prod['id']; ?>" class="btn btn-primary" style="background-color: #ffc107; color: black; margin-left: 5px;">تعديل</a>
                        <a href="?delete=<?php echo $prod['id']; ?>" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟');">حذف</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (count($products) === 0): ?>
                <tr><td colspan="5" style="text-align:center;">لا توجد منتجات حالياً.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
