<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
    session_start();
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// حماية الصفحة
check_admin();

$base_url = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) ? '/emad-stor/' : '/';

// معالجة تغيير حالة الطلب
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $error = "رمز الأمان غير صالح. الرجاء تحديث الصفحة والمحاولة مجدداً.";
    } else {
        $order_id = (int)$_POST['order_id'];
        $status = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $order_id]);
    
    $success = "تم تحديث حالة الطلب رقم $order_id بنجاح.";

    // إرسال بريد إلكتروني للمستخدم
    try {
        $stmt_order = $pdo->prepare("SELECT user_id, customer_name FROM orders WHERE id = ?");
        $stmt_order->execute([$order_id]);
        $order_info = $stmt_order->fetch();

        if ($order_info && $order_info['user_id']) {
            $stmt_user = $pdo->prepare("SELECT email FROM users WHERE id = ?");
            $stmt_user->execute([$order_info['user_id']]);
            $user_info = $stmt_user->fetch();

            if ($user_info && !empty($user_info['email'])) {
                $to = $user_info['email'];
                $subject = "تحديث حالة طلبك رقم #" . $order_id . " - متجر أبو عماد";
                
                $status_msg = "تم تحديث حالة طلبك إلى: ";
                if ($status == 'pending') $status_msg .= "قيد المراجعة";
                elseif ($status == 'shipped') $status_msg .= "تم الشحن";
                elseif ($status == 'delivered') $status_msg .= "مكتمل وتم التسليم";
                elseif ($status == 'cancelled') $status_msg .= "ملغي";
                else $status_msg .= $status;

                $message = "مرحباً " . $order_info['customer_name'] . "،\n\n";
                $message .= $status_msg . ".\n\n";
                $message .= "يمكنك متابعة طلبك من خلال لوحة التحكم الخاصة بك في الموقع (تتبع الطلبات).\n\n";
                $message .= "شكراً لتسوقك معنا!\nمتجر أبو عماد";

                $headers = "From: noreply@emad-stor.com\r\n";
                $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

                // استخدم @ لتجنب ظهور أخطاء إذا لم يكن الخادم معداً لإرسال الإيميلات (مثل XAMPP الافتراضي)
                @mail($to, $subject, $message, $headers);
            }
        }
    } catch (Exception $e) {
        // تجاهل أخطاء البريد لكي لا تعطل عملية التحديث
    }
    }
}

// جلب الطلبات
$stmt = $pdo->query("SELECT id, user_id, customer_name, customer_phone, customer_address, total_price, status, created_at, coupon_code, discount_amount, delivery_fee, payment_method, shipping_method, currency, exchange_rate, latitude, longitude, CASE WHEN payment_receipt IS NOT NULL AND payment_receipt != '' THEN 1 ELSE 0 END as has_receipt FROM orders WHERE status != 'delivered' ORDER BY created_at DESC");
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الطلبات | فضيات ابو عماد</title>
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
        .status-badge { padding: 5px 10px; border-radius: 20px; font-size: 0.85em; font-weight: bold; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-shipped { background: #cce5ff; color: #004085; }
        .status-delivered { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .btn { padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; color: white; background-color: #0B1B2B; text-decoration: none; display: inline-block; }
        .btn:hover { background-color: #1a365d; }
        .alert { padding: 10px; margin-bottom: 20px; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        
        .order-details { display: none; background: #f9f9f9; padding: 15px; margin-top: 10px; border-radius: 8px; border: 1px solid #ddd; }
        .order-details.active { display: block; }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="content">
    <h1>إدارة الطلبات</h1>
    
    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <div class="card">
        <?php if (count($orders) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>اسم العميل</th>
                        <th>رقم الهاتف</th>
                        <th>الإجمالي</th>
                        <th>تاريخ الطلب</th>
                        <th>طريقة الدفع</th>
                        <th>طريقة الشحن</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['customer_phone']); ?></td>
                            <td>
                                <?php 
                                if (!empty($order['currency']) && !empty($order['exchange_rate'])) {
                                    $hist_price = $order['total_price'] * $order['exchange_rate'];
                                    $symbol = get_currency_symbol($order['currency']);
                                    echo ($order['currency'] === 'YER') ? number_format($hist_price, 0) . $symbol : number_format($hist_price, 2) . $symbol;
                                } else {
                                    echo format_price($order['total_price']); 
                                }
                                ?>
                            </td>
                            <td dir="ltr" style="text-align: right;"><?php echo date('Y-m-d H:i', strtotime($order['created_at'])); ?></td>
                            <td><span style="background: #e2e8f0; padding: 4px 8px; border-radius: 4px; font-size: 0.85em;"><?php echo htmlspecialchars($order['payment_method'] ?? 'الدفع عند الاستلام'); ?></span></td>
                            <td><span style="background: #e6f2ff; color: #0056b3; padding: 4px 8px; border-radius: 4px; font-size: 0.85em;"><i class="fas fa-truck"></i> <?php echo htmlspecialchars($order['shipping_method'] ?? 'توصيل'); ?></span></td>
                            <td>
                                <?php 
                                    $statusClass = 'status-pending';
                                    $statusText = 'قيد المراجعة';
                                    if ($order['status'] == 'shipped') { $statusClass = 'status-shipped'; $statusText = 'تم الشحن'; }
                                    elseif ($order['status'] == 'delivered') { $statusClass = 'status-delivered'; $statusText = 'مكتمل'; }
                                    elseif ($order['status'] == 'cancelled') { $statusClass = 'status-cancelled'; $statusText = 'ملغي'; }
                                ?>
                                <span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                            </td>
                            <td>
                                <button class="btn" onclick="toggleDetails(<?php echo $order['id']; ?>)">التفاصيل</button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7" style="padding: 0; border: none;">
                                <div id="details-<?php echo $order['id']; ?>" class="order-details">
                                    <h4>العنوان: <?php echo htmlspecialchars($order['customer_address']); ?></h4>
                                    
                                    <?php
                                    $stmt_items = $pdo->prepare("
                                        SELECT oi.*, p.title 
                                        FROM order_items oi 
                                        LEFT JOIN products p ON oi.product_id = p.id 
                                        WHERE oi.order_id = ?
                                    ");
                                    $stmt_items->execute([$order['id']]);
                                    $orderItems = $stmt_items->fetchAll();
                                    ?>
                                    
                                    <?php if (!empty($order['latitude']) && !empty($order['longitude'])): ?>
                                    <div style="margin: 15px 0; padding: 15px; background: #e8f5e9; border-radius: 8px; border: 1px solid #c8e6c9;">
                                        <h5 style="margin-top: 0; color: #2e7d32; font-size: 1.1em;"><i class="fas fa-motorcycle"></i> بيانات الإسناد للمندوب:</h5>
                                        <?php
                                        $osm_url = "https://www.openstreetmap.org/?mlat={$order['latitude']}&mlon={$order['longitude']}";
                                        $total_price_for_msg = '';
                                        if (!empty($order['currency']) && !empty($order['exchange_rate'])) {
                                            $hist_price = $order['total_price'] * $order['exchange_rate'];
                                            $symbol = get_currency_symbol($order['currency']);
                                            $total_price_for_msg = ($order['currency'] === 'YER') ? number_format($hist_price, 0) . ' ' . $symbol : number_format($hist_price, 2) . ' ' . $symbol;
                                        } else {
                                            $total_price_for_msg = format_price($order['total_price']);
                                        }
                                        
                                        $whatsapp_text = "طلب جديد #{$order['id']}\n" .
                                                         "العميل: {$order['customer_name']}\n" .
                                                         "رقم العميل: {$order['customer_phone']}\n";
                                        
                                        foreach ($orderItems as $item) {
                                            $name = $item['title'] ? $item['title'] : 'منتج محذوف';
                                            $item_price_display = '';
                                            if (!empty($order['currency']) && !empty($order['exchange_rate'])) {
                                                $hist_item_price = $item['price'] * $order['exchange_rate'];
                                                $symbol = get_currency_symbol($order['currency']);
                                                $item_price_display = ($order['currency'] === 'YER') ? number_format($hist_item_price, 0) . ' ' . $symbol : number_format($hist_item_price, 2) . ' ' . $symbol;
                                            } else {
                                                $item_price_display = format_price($item['price']); 
                                            }
                                            $whatsapp_text .= "المنتج: {$name}\n";
                                            $whatsapp_text .= "سعر المنتج: {$item_price_display}\n";
                                        }
                                        
                                        $delivery_fee_display = '';
                                        if (!empty($order['currency']) && !empty($order['exchange_rate'])) {
                                            $hist_del_price = ($order['delivery_fee'] ?? 0) * $order['exchange_rate'];
                                            $symbol = get_currency_symbol($order['currency']);
                                            $delivery_fee_display = ($order['currency'] === 'YER') ? number_format($hist_del_price, 0) . ' ' . $symbol : number_format($hist_del_price, 2) . ' ' . $symbol;
                                        } else {
                                            $delivery_fee_display = format_price($order['delivery_fee'] ?? 0);
                                        }
                                        $whatsapp_text .= "رسوم التوصيل: {$delivery_fee_display}\n";
                                        $whatsapp_text .= "الإجمالي: {$total_price_for_msg}\n";
                                        $whatsapp_text .= "العنوان: {$order['customer_address']}";
                                        
                                        $whatsapp_url = "https://wa.me/?text=" . urlencode($whatsapp_text);
                                        ?>
                                        <p style="margin-bottom: 10px; color: #333;"><strong>رسوم التوصيل المحسوبة:</strong> <?php echo $delivery_fee_display; ?></p>
                                        <a href="<?php echo htmlspecialchars($whatsapp_url); ?>" target="_blank" class="btn" style="background-color: #25D366; color: white; display: inline-flex; align-items: center; gap: 8px; font-weight: bold; padding: 10px 15px;"><i class="fab fa-whatsapp" style="font-size: 1.2em;"></i> إرسال للمندوب (واتساب)</a>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($order['has_receipt']) && $order['has_receipt'] == 1): ?>
                                    <div style="margin: 15px 0; padding: 10px; background: #e3f2fd; border-radius: 8px;">
                                        <h5 style="margin-top: 0; color: #0d47a1;">صورة الإيصال / الحوالة:</h5>
                                        <a href="view_receipt.php?id=<?php echo $order['id']; ?>" target="_blank" class="btn" style="background-color: #0056b3; margin-top: 5px;">
                                            <i class="fas fa-external-link-alt"></i> عرض إيصال الدفع
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <h5>المنتجات المطلوبة:</h5>
                                    <ul>
                                        <?php
                                        foreach ($orderItems as $item) {
                                            $name = $item['title'] ? htmlspecialchars($item['title']) : 'منتج محذوف';
                                            
                                            $item_price_display = '';
                                            if (!empty($order['currency']) && !empty($order['exchange_rate'])) {
                                                $hist_item_price = $item['price'] * $order['exchange_rate'];
                                                $symbol = get_currency_symbol($order['currency']);
                                                $item_price_display = ($order['currency'] === 'YER') ? number_format($hist_item_price, 0) . $symbol : number_format($hist_item_price, 2) . $symbol;
                                            } else {
                                                $item_price_display = format_price($item['price']); 
                                            }
                                            
                                            echo "<li>{$name} (مقاس: {$item['size']}) - الكمية: {$item['quantity']} × {$item_price_display}</li>";
                                        }
                                        ?>
                                    </ul>

                                    <form action="" method="POST" style="margin-top: 15px; border-top: 1px solid #ccc; padding-top: 10px;">
                                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <label>تحديث الحالة:</label>
                                        <select name="status" style="padding: 5px;">
                                            <option value="pending" <?php echo $order['status']=='pending'?'selected':''; ?>>قيد المراجعة</option>
                                            <option value="shipped" <?php echo $order['status']=='shipped'?'selected':''; ?>>تم الشحن</option>
                                            <option value="delivered" <?php echo $order['status']=='delivered'?'selected':''; ?>>مكتمل</option>
                                            <option value="cancelled" <?php echo $order['status']=='cancelled'?'selected':''; ?>>ملغي</option>
                                        </select>
                                        <button type="submit" class="btn" style="padding: 5px 10px;">تحديث</button>
                                    </form>

                                    <?php 
                                        $inv_wa_text = "مرحباً {$order['customer_name']} 🌷\n";
                                        $inv_wa_text .= "هذه فاتورة شراء لطلبك رقم #{$order['id']} من متجر أبو عماد للفضيات.\n\n";
                                        $inv_wa_text .= "🧾 تفاصيل الطلب:\n";
                                        foreach ($orderItems as $item) {
                                            $n = $item['title'] ? htmlspecialchars($item['title']) : 'منتج محذوف';
                                            $qty = $item['quantity'];
                                            $inv_wa_text .= "- {$n} (الكمية: {$qty})\n";
                                        }
                                        $inv_wa_text .= "\n🚚 رسوم التوصيل: {$delivery_fee_display}\n";
                                        if (!empty($order['discount_amount']) && $order['discount_amount'] > 0) {
                                            $discount_display = '';
                                            if (!empty($order['currency']) && !empty($order['exchange_rate'])) {
                                                $hist_disc = $order['discount_amount'] * $order['exchange_rate'];
                                                $symbol = get_currency_symbol($order['currency']);
                                                $discount_display = ($order['currency'] === 'YER') ? number_format($hist_disc, 0) . ' ' . $symbol : number_format($hist_disc, 2) . ' ' . $symbol;
                                            } else {
                                                $discount_display = format_price($order['discount_amount']);
                                            }
                                            $inv_wa_text .= "🏷️ الخصم: -{$discount_display}\n";
                                        }
                                        $inv_wa_text .= "💰 الإجمالي النهائي: {$total_price_for_msg}\n\n";
                                        $inv_wa_text .= "نأمل أن تنال منتجاتنا إعجابك! شكراً لتسوقك معنا.";
                                        
                                        $cust_phone = $order['customer_phone'];
                                        if (strpos($cust_phone, '+') === 0) $cust_phone = substr($cust_phone, 1);
                                        $inv_wa_url = "https://wa.me/" . $cust_phone . "?text=" . urlencode($inv_wa_text);
                                    ?>
                                    <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border: 1px solid #ddd; border-radius: 8px;">
                                        <h5 style="margin-top: 0; margin-bottom: 10px; color: #333;"><i class="fas fa-file-invoice"></i> خيارات الفاتورة</h5>
                                        <a href="view_invoice.php?id=<?php echo $order['id']; ?>&type=purchase" target="_blank" class="btn" style="background-color: #0B1B2B; color: white; margin-bottom: 5px;"><i class="fas fa-print"></i> طباعة الفاتورة للعميل</a>
                                        <a href="view_invoice.php?id=<?php echo $order['id']; ?>&type=delivery" target="_blank" class="btn" style="background-color: #e67e22; color: white; margin-bottom: 5px;"><i class="fas fa-motorcycle"></i> طباعة فاتورة التوصيل للمندوب</a>
                                        <a href="<?php echo htmlspecialchars($inv_wa_url); ?>" target="_blank" class="btn" style="background-color: #25D366; color: white; margin-bottom: 5px;"><i class="fab fa-whatsapp"></i> إرسال الفاتورة (واتساب)</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>لا توجد طلبات حتى الآن.</p>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleDetails(id) {
    const detailsDiv = document.getElementById('details-' + id);
    if (detailsDiv) {
        detailsDiv.classList.toggle('active');
    }
}
</script>
</body>
</html>
