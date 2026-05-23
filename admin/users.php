<?php
session_start();
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// حماية الصفحة
check_admin();

$base_url = '/emad-stor/';

// معالجة تغيير صلاحيات المستخدم
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['action'])) {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $error = "رمز الأمان غير صالح. الرجاء المحاولة مجدداً.";
    } else {
        $target_user_id = (int)$_POST['user_id'];
        $action = $_POST['action'];

        // منع المدير من تغيير صلاحيات نفسه عن طريق الخطأ
        if ($target_user_id === $_SESSION['user_id']) {
            $error = "لا يمكنك تغيير صلاحيات حسابك الخاص.";
        } else {
            if ($action === 'make_admin') {
                $stmt = $pdo->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
                $stmt->execute([$target_user_id]);
                $success = "تم ترقية المستخدم إلى مدير بنجاح.";
            } elseif ($action === 'make_customer') {
                $stmt = $pdo->prepare("UPDATE users SET role = 'customer' WHERE id = ?");
                $stmt->execute([$target_user_id]);
                $success = "تم تحويل المدير إلى مستخدم عادي بنجاح.";
            }
        }
    }
}

// جلب جميع المستخدمين
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة المستخدمين | فضيات ابو عماد</title>
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
        h1 { color: #0B1B2B; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: right; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; color: #333; }
        tr:hover { background-color: #f1f1f1; }
        .role-badge { padding: 5px 10px; border-radius: 20px; font-size: 0.85em; font-weight: bold; }
        .role-admin { background: #cce5ff; color: #004085; }
        .role-customer { background: #e2e3e5; color: #383d41; }
        .btn { padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; color: white; text-decoration: none; display: inline-block; font-family: inherit;}
        .btn-make-admin { background-color: #28a745; }
        .btn-make-admin:hover { background-color: #218838; }
        .btn-make-customer { background-color: #dc3545; }
        .btn-make-customer:hover { background-color: #c82333; }
        .alert { padding: 10px; margin-bottom: 20px; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .search-box { margin-bottom: 20px; padding: 10px; width: 100%; max-width: 400px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit;}
    </style>
</head>
<body>

<div class="sidebar">
    <h2>لوحة التحكم</h2>
    <a href="index.php"><i class="fas fa-home"></i> الرئيسية</a>
    <a href="categories.php"><i class="fas fa-list"></i> إدارة الأقسام</a>
    <a href="products.php"><i class="fas fa-box"></i> إدارة المنتجات</a>
    <a href="orders.php"><i class="fas fa-shopping-cart"></i> إدارة الطلبات</a>
    <a href="coupons.php"><i class="fas fa-tags"></i> إدارة الكوبونات</a>
    <a href="users.php" class="active"><i class="fas fa-users"></i> إدارة المستخدمين</a>
    <a href="../index.php"><i class="fas fa-store"></i> العودة للمتجر</a>
    <a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
</div>

<div class="content">
    <h1>إدارة المستخدمين</h1>
    
    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="card">
        <input type="text" id="searchInput" class="search-box" placeholder="ابحث عن مستخدم بالاسم أو الإيميل..." onkeyup="filterUsers()">

        <?php if (count($users) > 0): ?>
            <table id="usersTable">
                <thead>
                    <tr>
                        <th>رقم</th>
                        <th>اسم المستخدم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الصلاحية</th>
                        <th>تاريخ التسجيل</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>#<?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <?php if ($user['role'] === 'admin'): ?>
                                    <span class="role-badge role-admin"><i class="fas fa-user-shield"></i> مدير</span>
                                <?php else: ?>
                                    <span class="role-badge role-customer">مستخدم عادي</span>
                                <?php endif; ?>
                            </td>
                            <td dir="ltr" style="text-align: right;"><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></td>
                            <td>
                                <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                                    <form action="" method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد من تغيير صلاحيات هذا المستخدم؟');">
                                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <?php if ($user['role'] !== 'admin'): ?>
                                            <input type="hidden" name="action" value="make_admin">
                                            <button type="submit" class="btn btn-make-admin"><i class="fas fa-level-up-alt"></i> ترقية كمدير</button>
                                        <?php else: ?>
                                            <input type="hidden" name="action" value="make_customer">
                                            <button type="submit" class="btn btn-make-customer"><i class="fas fa-level-down-alt"></i> إزالة الإدارة</button>
                                        <?php endif; ?>
                                    </form>
                                <?php else: ?>
                                    <span style="color: #6c757d; font-size: 0.9em;">(هذا حسابك)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>لا يوجد مستخدمين.</p>
        <?php endif; ?>
    </div>
</div>

<script>
function filterUsers() {
    var input, filter, table, tr, tdName, tdEmail, i, txtValueName, txtValueEmail;
    input = document.getElementById("searchInput");
    filter = input.value.toLowerCase();
    table = document.getElementById("usersTable");
    tr = table.getElementsByTagName("tr");

    for (i = 1; i < tr.length; i++) {
        tdName = tr[i].getElementsByTagName("td")[1];
        tdEmail = tr[i].getElementsByTagName("td")[2];
        if (tdName || tdEmail) {
            txtValueName = tdName.textContent || tdName.innerText;
            txtValueEmail = tdEmail.textContent || tdEmail.innerText;
            if (txtValueName.toLowerCase().indexOf(filter) > -1 || txtValueEmail.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }       
    }
}
</script>
</body>
</html>
