<?php
session_start();
require_once 'includes/db_connect.php';

// التأكد من تسجيل الدخول
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: pages/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$success_msg = '';
$error_msg = '';

// معالجة تحديث البيانات
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $full_name = trim($_POST['full_name'] ?? '');
    
    if (empty($full_name)) {
        $error_msg = 'الاسم الكامل مطلوب.';
    } else {
        $stmt = $pdo->prepare('UPDATE users SET full_name = ? WHERE id = ?');
        if ($stmt->execute([$full_name, $user_id])) {
            $_SESSION['user_name'] = $full_name; // تحديث الجلسة
            $success_msg = 'تم تحديث بياناتك بنجاح.';
        } else {
            $error_msg = 'حدث خطأ أثناء تحديث البيانات.';
        }
    }
}

// جلب بيانات المستخدم
$stmt = $pdo->prepare('SELECT full_name, email FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// جلب طلبات المستخدم
$stmt_orders = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$stmt_orders->execute([$user_id]);
$orders = $stmt_orders->fetchAll();

// متغيرات SEO الافتراضية
$page_title = 'حسابي | فضيات ابو عماد';
include 'includes/header.php';
?>

<div class="page-content" style="max-width: 1000px; margin: 4rem auto; padding: 2rem;">
    <div style="display: flex; flex-wrap: wrap; gap: 2rem;">
        
        <!-- قسم البيانات الشخصية -->
        <div style="flex: 1; min-width: 300px; background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
            <h2 style="color: #0B1B2B; border-right: 4px solid #FFD966; padding-right: 15px; margin-bottom: 2rem;">بياناتي الشخصية</h2>
            
            <?php if ($success_msg): ?>
                <div style="background: #e8f5e9; color: #2e7d32; padding: 10px; border-radius: 8px; margin-bottom: 1rem;">
                    <i class="fas fa-check-circle"></i> <?php echo $success_msg; ?>
                </div>
            <?php endif; ?>
            <?php if ($error_msg): ?>
                <div style="background: #ffebee; color: #c62828; padding: 10px; border-radius: 8px; margin-bottom: 1rem;">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_msg; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="profile.php">
                <div class="input-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: #4A5568;">الاسم الكامل</label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required style="width: 100%; padding: 0.8rem; border: 1.5px solid #E2E8F0; border-radius: 8px; background: #F9FBFD; outline: none; font-family: inherit;">
                </div>
                <div class="input-group" style="margin-bottom: 2rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: #4A5568;">البريد الإلكتروني (لا يمكن تغييره)</label>
                    <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled style="width: 100%; padding: 0.8rem; border: 1.5px solid #E2E8F0; border-radius: 8px; background: #EDF2F7; outline: none; font-family: inherit; color: #718096; cursor: not-allowed;">
                </div>
                <button type="submit" name="update_profile" style="background: #0B1B2B; color: #FFD966; border: none; padding: 12px 25px; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.3s; width: 100%;">حفظ التعديلات</button>
            </form>
        </div>

        <!-- قسم الطلبات -->
        <div style="flex: 2; min-width: 400px; background: white; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
            <h2 style="color: #0B1B2B; border-right: 4px solid #FFD966; padding-right: 15px; margin-bottom: 2rem;">طلباتي السابقة</h2>
            
            <?php if (empty($orders)): ?>
                <div style="text-align: center; padding: 3rem 1rem; color: #718096;">
                    <i class="fas fa-box-open" style="font-size: 3rem; color: #CBD5E0; margin-bottom: 1rem;"></i>
                    <p>لم تقم بإجراء أي طلبات حتى الآن.</p>
                    <a href="index.php" style="display: inline-block; margin-top: 1rem; background: #FFD966; color: #0B1B2B; padding: 10px 20px; border-radius: 20px; text-decoration: none; font-weight: bold;">تصفح المنتجات</a>
                </div>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; min-width: 500px;">
                        <thead>
                            <tr style="background: #F8FAFC; border-bottom: 2px solid #E2E8F0;">
                                <th style="padding: 12px; text-align: right; color: #4A5568;">رقم الطلب</th>
                                <th style="padding: 12px; text-align: right; color: #4A5568;">التاريخ</th>
                                <th style="padding: 12px; text-align: right; color: #4A5568;">الإجمالي</th>
                                <th style="padding: 12px; text-align: right; color: #4A5568;">الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): 
                                $status_color = '#718096';
                                $status_text = 'غير معروف';
                                switch($order['status']) {
                                    case 'pending': 
                                        $status_color = '#F6AD55'; 
                                        $status_text = 'قيد المراجعة';
                                        break;
                                    case 'processing': 
                                        $status_color = '#4299E1'; 
                                        $status_text = 'جاري التجهيز';
                                        break;
                                    case 'shipped': 
                                        $status_color = '#9F7AEA'; 
                                        $status_text = 'تم الشحن';
                                        break;
                                    case 'delivered': 
                                        $status_color = '#48BB78'; 
                                        $status_text = 'تم التوصيل';
                                        break;
                                    case 'cancelled': 
                                        $status_color = '#F56565'; 
                                        $status_text = 'ملغي';
                                        break;
                                }
                            ?>
                            <tr style="border-bottom: 1px solid #EDF2F7; transition: 0.2s;">
                                <td style="padding: 15px 12px; font-weight: bold;">#<?php echo $order['id']; ?></td>
                                <td style="padding: 15px 12px; color: #718096;"><?php echo date('Y-m-d', strtotime($order['created_at'])); ?></td>
                                <td style="padding: 15px 12px; font-weight: bold; color: #0B1B2B;">
                                    <?php 
                                    // عرض السعر بنفس العملة التي تم الطلب بها
                                    if(isset($order['currency']) && isset($order['exchange_rate'])) {
                                        $price_in_currency = $order['total_price'] * $order['exchange_rate'];
                                        $symbol = ($order['currency'] == 'YER') ? ' ر.ي' : (($order['currency'] == 'SAR') ? ' ر.س' : '$');
                                        if($order['currency'] == 'YER') {
                                            echo number_format($price_in_currency, 0) . $symbol;
                                        } else {
                                            echo number_format($price_in_currency, 2) . $symbol;
                                        }
                                    } else {
                                        echo format_price($order['total_price']); 
                                    }
                                    ?>
                                </td>
                                <td style="padding: 15px 12px;">
                                    <span style="background: <?php echo $status_color; ?>20; color: <?php echo $status_color; ?>; padding: 5px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: bold;">
                                        <?php echo $status_text; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
