<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
    session_start();
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// التأكد من تسجيل الدخول
if (!is_logged_in()) {
    $_SESSION['error'] = 'يجب تسجيل الدخول أولاً لتتبع طلباتك.';
    header('Location: pages/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$orders = [];

try {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // error_log($e->getMessage());
}

include 'includes/header.php';
?>

<div class="checkout-container" style="max-width: 800px; margin: 4rem auto; padding: 2rem; background: #fff; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
    <h2 style="text-align: center; margin-bottom: 2rem; color: #0B1B2B;">تتبع طلباتي</h2>

    <?php if (empty($orders)): ?>
        <div style="text-align: center; padding: 3rem 1rem;">
            <i class="fas fa-box-open" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
            <h3 style="color: #666; margin-bottom: 1rem;">لا توجد طلبات سابقة.</h3>
            <a href="index.php" class="login-btn" style="display: inline-block; width: auto; padding: 0.8rem 2rem; text-decoration: none;">تصفح المتجر</a>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <?php foreach ($orders as $order): ?>
                <div style="background: #F8FAFE; padding: 1.5rem; border-radius: 12px; border: 1px solid #E2E8F0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid #E2E8F0; padding-bottom: 0.5rem;">
                        <h4 style="color: #0B1B2B;">طلب رقم: #<?php echo $order['id']; ?></h4>
                        <span style="background: <?php echo ($order['status'] === 'completed') ? '#4CAF50' : '#FF9800'; ?>; color: white; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.9rem;">
                            <?php 
                                if($order['status'] === 'pending') echo 'قيد المراجعة';
                                elseif($order['status'] === 'completed') echo 'مكتمل';
                                elseif($order['status'] === 'cancelled') echo 'ملغي';
                                else echo htmlspecialchars($order['status']); 
                            ?>
                        </span>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; font-size: 0.95rem; color: #555;">
                        <div>
                            <strong>الإجمالي:</strong> 
                            <?php 
                            if (!empty($order['currency']) && !empty($order['exchange_rate'])) {
                                $hist_price = $order['total_price'] * $order['exchange_rate'];
                                $symbol = get_currency_symbol($order['currency']);
                                echo ($order['currency'] === 'YER') ? number_format($hist_price, 0) . $symbol : number_format($hist_price, 2) . $symbol;
                            } else {
                                echo format_price($order['total_price']); 
                            }
                            ?>
                        </div>
                        <div>
                            <strong>طريقة الدفع:</strong> <?php echo htmlspecialchars($order['payment_method']); ?>
                        </div>
                        <div>
                            <strong>طريقة الشحن:</strong> <?php echo htmlspecialchars($order['shipping_method']); ?>
                        </div>
                        <?php if(isset($order['created_at'])): ?>
                        <div>
                            <strong>تاريخ الطلب:</strong> <?php echo date('Y-m-d H:i', strtotime($order['created_at'])); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
