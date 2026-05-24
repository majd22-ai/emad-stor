<?php
session_start();
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// ط­ظ…ط§ظٹط© ط§ظ„طµظپط­ط©
check_admin();

$base_url = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) ? '/emad-stor/' : '/';

// ظ…ط¹ط§ظ„ط¬ط© طھط؛ظٹظٹط± ط­ط§ظ„ط© ط§ظ„ط·ظ„ط¨
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $error = "ط±ظ…ط² ط§ظ„ط£ظ…ط§ظ† ط؛ظٹط± طµط§ظ„ط­. ط§ظ„ط±ط¬ط§ط، طھط­ط¯ظٹط« ط§ظ„طµظپط­ط© ظˆط§ظ„ظ…ط­ط§ظˆظ„ط© ظ…ط¬ط¯ط¯ط§ظ‹.";
    } else {
        $order_id = (int)$_POST['order_id'];
        $status = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $order_id]);
    
    $success = "طھظ… طھط­ط¯ظٹط« ط­ط§ظ„ط© ط§ظ„ط·ظ„ط¨ ط±ظ‚ظ… $order_id ط¨ظ†ط¬ط§ط­.";

    // ط¥ط±ط³ط§ظ„ ط¨ط±ظٹط¯ ط¥ظ„ظƒطھط±ظˆظ†ظٹ ظ„ظ„ظ…ط³طھط®ط¯ظ…
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
                $subject = "طھط­ط¯ظٹط« ط­ط§ظ„ط© ط·ظ„ط¨ظƒ ط±ظ‚ظ… #" . $order_id . " - ظ…طھط¬ط± ط£ط¨ظˆ ط¹ظ…ط§ط¯";
                
                $status_msg = "طھظ… طھط­ط¯ظٹط« ط­ط§ظ„ط© ط·ظ„ط¨ظƒ ط¥ظ„ظ‰: ";
                if ($status == 'pending') $status_msg .= "ظ‚ظٹط¯ ط§ظ„ظ…ط±ط§ط¬ط¹ط©";
                elseif ($status == 'shipped') $status_msg .= "طھظ… ط§ظ„ط´ط­ظ†";
                elseif ($status == 'delivered') $status_msg .= "ظ…ظƒطھظ…ظ„ ظˆطھظ… ط§ظ„طھط³ظ„ظٹظ…";
                elseif ($status == 'cancelled') $status_msg .= "ظ…ظ„ط؛ظٹ";
                else $status_msg .= $status;

                $message = "ظ…ط±ط­ط¨ط§ظ‹ " . $order_info['customer_name'] . "طŒ\n\n";
                $message .= $status_msg . ".\n\n";
                $message .= "ظٹظ…ظƒظ†ظƒ ظ…طھط§ط¨ط¹ط© ط·ظ„ط¨ظƒ ظ…ظ† ط®ظ„ط§ظ„ ظ„ظˆط­ط© ط§ظ„طھط­ظƒظ… ط§ظ„ط®ط§طµط© ط¨ظƒ ظپظٹ ط§ظ„ظ…ظˆظ‚ط¹ (طھطھط¨ط¹ ط§ظ„ط·ظ„ط¨ط§طھ).\n\n";
                $message .= "ط´ظƒط±ط§ظ‹ ظ„طھط³ظˆظ‚ظƒ ظ…ط¹ظ†ط§!\nظ…طھط¬ط± ط£ط¨ظˆ ط¹ظ…ط§ط¯";

                $headers = "From: noreply@emad-stor.com\r\n";
                $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

                // ط§ط³طھط®ط¯ظ… @ ظ„طھط¬ظ†ط¨ ط¸ظ‡ظˆط± ط£ط®ط·ط§ط، ط¥ط°ط§ ظ„ظ… ظٹظƒظ† ط§ظ„ط®ط§ط¯ظ… ظ…ط¹ط¯ط§ظ‹ ظ„ط¥ط±ط³ط§ظ„ ط§ظ„ط¥ظٹظ…ظٹظ„ط§طھ (ظ…ط«ظ„ XAMPP ط§ظ„ط§ظپطھط±ط§ط¶ظٹ)
                @mail($to, $subject, $message, $headers);
            }
        }
    } catch (Exception $e) {
        // طھط¬ط§ظ‡ظ„ ط£ط®ط·ط§ط، ط§ظ„ط¨ط±ظٹط¯ ظ„ظƒظٹ ظ„ط§ طھط¹ط·ظ„ ط¹ظ…ظ„ظٹط© ط§ظ„طھط­ط¯ظٹط«
    }
    }
}

// ط¬ظ„ط¨ ط§ظ„ط·ظ„ط¨ط§طھ
$stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ط¥ط¯ط§ط±ط© ط§ظ„ط·ظ„ط¨ط§طھ | ظپط¶ظٹط§طھ ط§ط¨ظˆ ط¹ظ…ط§ط¯</title>
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

<div class="sidebar">
    <h2>ظ„ظˆط­ط© ط§ظ„طھط­ظƒظ…</h2>
    <a href="index.php"><i class="fas fa-home"></i> ط§ظ„ط±ط¦ظٹط³ظٹط©</a>
    <a href="categories.php"><i class="fas fa-list"></i> ط¥ط¯ط§ط±ط© ط§ظ„ط£ظ‚ط³ط§ظ…</a>
    <a href="products.php"><i class="fas fa-box"></i> ط¥ط¯ط§ط±ط© ط§ظ„ظ…ظ†طھط¬ط§طھ</a>
    <a href="orders.php" class="active"><i class="fas fa-shopping-cart"></i> ط¥ط¯ط§ط±ط© ط§ظ„ط·ظ„ط¨ط§طھ</a>
    <a href="users.php"><i class="fas fa-users"></i> ط¥ط¯ط§ط±ط© ط§ظ„ظ…ط³طھط®ط¯ظ…ظٹظ†</a>
    <a href="../index.php"><i class="fas fa-store"></i> ط§ظ„ط¹ظˆط¯ط© ظ„ظ„ظ…طھط¬ط±</a>
    <a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> طھط³ط¬ظٹظ„ ط§ظ„ط®ط±ظˆط¬</a>
</div>

<div class="content">
    <h1>ط¥ط¯ط§ط±ط© ط§ظ„ط·ظ„ط¨ط§طھ</h1>
    
    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <div class="card">
        <?php if (count($orders) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ط±ظ‚ظ… ط§ظ„ط·ظ„ط¨</th>
                        <th>ط§ط³ظ… ط§ظ„ط¹ظ…ظٹظ„</th>
                        <th>ط±ظ‚ظ… ط§ظ„ظ‡ط§طھظپ</th>
                        <th>ط§ظ„ط¥ط¬ظ…ط§ظ„ظٹ</th>
                        <th>طھط§ط±ظٹط® ط§ظ„ط·ظ„ط¨</th>
                        <th>ط·ط±ظٹظ‚ط© ط§ظ„ط¯ظپط¹</th>
                        <th>ط·ط±ظٹظ‚ط© ط§ظ„ط´ط­ظ†</th>
                        <th>ط§ظ„ط­ط§ظ„ط©</th>
                        <th>ط§ظ„ط¥ط¬ط±ط§ط،ط§طھ</th>
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
                            <td><span style="background: #e2e8f0; padding: 4px 8px; border-radius: 4px; font-size: 0.85em;"><?php echo htmlspecialchars($order['payment_method'] ?? 'ط§ظ„ط¯ظپط¹ ط¹ظ†ط¯ ط§ظ„ط§ط³طھظ„ط§ظ…'); ?></span></td>
                            <td><span style="background: #e6f2ff; color: #0056b3; padding: 4px 8px; border-radius: 4px; font-size: 0.85em;"><i class="fas fa-truck"></i> <?php echo htmlspecialchars($order['shipping_method'] ?? 'طھظˆطµظٹظ„'); ?></span></td>
                            <td>
                                <?php 
                                    $statusClass = 'status-pending';
                                    $statusText = 'ظ‚ظٹط¯ ط§ظ„ظ…ط±ط§ط¬ط¹ط©';
                                    if ($order['status'] == 'shipped') { $statusClass = 'status-shipped'; $statusText = 'طھظ… ط§ظ„ط´ط­ظ†'; }
                                    elseif ($order['status'] == 'delivered') { $statusClass = 'status-delivered'; $statusText = 'ظ…ظƒطھظ…ظ„'; }
                                    elseif ($order['status'] == 'cancelled') { $statusClass = 'status-cancelled'; $statusText = 'ظ…ظ„ط؛ظٹ'; }
                                ?>
                                <span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                            </td>
                            <td>
                                <button class="btn" onclick="toggleDetails(<?php echo $order['id']; ?>)">ط§ظ„طھظپط§طµظٹظ„</button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7" style="padding: 0; border: none;">
                                <div id="details-<?php echo $order['id']; ?>" class="order-details">
                                    <h4>ط§ظ„ط¹ظ†ظˆط§ظ†: <?php echo htmlspecialchars($order['customer_address']); ?></h4>
                                    
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
                                        <h5 style="margin-top: 0; color: #2e7d32; font-size: 1.1em;"><i class="fas fa-motorcycle"></i> ط¨ظٹط§ظ†ط§طھ ط§ظ„ط¥ط³ظ†ط§ط¯ ظ„ظ„ظ…ظ†ط¯ظˆط¨:</h5>
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
                                        
                                        $whatsapp_text = "ط·ظ„ط¨ ط¬ط¯ظٹط¯ #{$order['id']}\n" .
                                                         "ط§ظ„ط¹ظ…ظٹظ„: {$order['customer_name']}\n" .
                                                         "ط±ظ‚ظ… ط§ظ„ط¹ظ…ظٹظ„: {$order['customer_phone']}\n";
                                        
                                        foreach ($orderItems as $item) {
                                            $name = $item['title'] ? $item['title'] : 'ظ…ظ†طھط¬ ظ…ط­ط°ظˆظپ';
                                            $item_price_display = '';
                                            if (!empty($order['currency']) && !empty($order['exchange_rate'])) {
                                                $hist_item_price = $item['price'] * $order['exchange_rate'];
                                                $symbol = get_currency_symbol($order['currency']);
                                                $item_price_display = ($order['currency'] === 'YER') ? number_format($hist_item_price, 0) . ' ' . $symbol : number_format($hist_item_price, 2) . ' ' . $symbol;
                                            } else {
                                                $item_price_display = format_price($item['price']); 
                                            }
                                            $whatsapp_text .= "ط§ظ„ظ…ظ†طھط¬: {$name}\n";
                                            $whatsapp_text .= "ط³ط¹ط± ط§ظ„ظ…ظ†طھط¬: {$item_price_display}\n";
                                        }
                                        
                                        $delivery_fee_display = format_price($order['delivery_fee'] ?? 0);
                                        $whatsapp_text .= "ط±ط³ظˆظ… ط§ظ„طھظˆطµظٹظ„: {$delivery_fee_display}\n";
                                        $whatsapp_text .= "ط§ظ„ط¥ط¬ظ…ط§ظ„ظٹ: {$total_price_for_msg}\n";
                                        $whatsapp_text .= "ط§ظ„ظ…ظˆظ‚ط¹: {$osm_url}";
                                        
                                        $whatsapp_url = "https://wa.me/?text=" . urlencode($whatsapp_text);
                                        ?>
                                        <p style="margin-bottom: 10px; color: #333;"><strong>ط±ط³ظˆظ… ط§ظ„طھظˆطµظٹظ„ ط§ظ„ظ…ط­ط³ظˆط¨ط©:</strong> <?php echo format_price($order['delivery_fee'] ?? 0); ?></p>
                                        <a href="<?php echo htmlspecialchars($whatsapp_url); ?>" target="_blank" class="btn" style="background-color: #25D366; color: white; display: inline-flex; align-items: center; gap: 8px; font-weight: bold; padding: 10px 15px;"><i class="fab fa-whatsapp" style="font-size: 1.2em;"></i> ط¥ط±ط³ط§ظ„ ظ„ظ„ظ…ظ†ط¯ظˆط¨ (ظˆط§طھط³ط§ط¨)</a>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($order['payment_receipt'])): ?>
                                    <div style="margin: 15px 0; padding: 10px; background: #e3f2fd; border-radius: 8px;">
                                        <h5 style="margin-top: 0; color: #0d47a1;">طµظˆط±ط© ط§ظ„ط¥ظٹطµط§ظ„ / ط§ظ„ط­ظˆط§ظ„ط©:</h5>
                                        <?php
                                        $receipt_ext = strtolower(pathinfo($order['payment_receipt'], PATHINFO_EXTENSION));
                                        if ($receipt_ext === 'pdf') {
                                            echo '<a href="../' . htmlspecialchars($order['payment_receipt']) . '" target="_blank" class="btn" style="background-color: #0056b3; margin-top: 5px;"><i class="fas fa-file-pdf"></i> ط¹ط±ط¶ ظ…ظ„ظپ PDF</a>';
                                        } else {
                                            echo '<a href="../' . htmlspecialchars($order['payment_receipt']) . '" target="_blank"><img src="../' . htmlspecialchars($order['payment_receipt']) . '" alt="ط¥ظٹطµط§ظ„ ط§ظ„ط¯ظپط¹" style="max-width: 200px; max-height: 200px; border-radius: 4px; border: 1px solid #ccc; margin-top: 5px;"></a>';
                                        }
                                        ?>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <h5>ط§ظ„ظ…ظ†طھط¬ط§طھ ط§ظ„ظ…ط·ظ„ظˆط¨ط©:</h5>
                                    <ul>
                                        <?php
                                        foreach ($orderItems as $item) {
                                            $name = $item['title'] ? htmlspecialchars($item['title']) : 'ظ…ظ†طھط¬ ظ…ط­ط°ظˆظپ';
                                            
                                            $item_price_display = '';
                                            if (!empty($order['currency']) && !empty($order['exchange_rate'])) {
                                                $hist_item_price = $item['price'] * $order['exchange_rate'];
                                                $symbol = get_currency_symbol($order['currency']);
                                                $item_price_display = ($order['currency'] === 'YER') ? number_format($hist_item_price, 0) . $symbol : number_format($hist_item_price, 2) . $symbol;
                                            } else {
                                                $item_price_display = format_price($item['price']); 
                                            }
                                            
                                            echo "<li>{$name} (ظ…ظ‚ط§ط³: {$item['size']}) - ط§ظ„ظƒظ…ظٹط©: {$item['quantity']} أ— {$item_price_display}</li>";
                                        }
                                        ?>
                                    </ul>

                                    <form action="" method="POST" style="margin-top: 15px; border-top: 1px solid #ccc; padding-top: 10px;">
                                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <label>طھط­ط¯ظٹط« ط§ظ„ط­ط§ظ„ط©:</label>
                                        <select name="status" style="padding: 5px;">
                                            <option value="pending" <?php echo $order['status']=='pending'?'selected':''; ?>>ظ‚ظٹط¯ ط§ظ„ظ…ط±ط§ط¬ط¹ط©</option>
                                            <option value="shipped" <?php echo $order['status']=='shipped'?'selected':''; ?>>طھظ… ط§ظ„ط´ط­ظ†</option>
                                            <option value="delivered" <?php echo $order['status']=='delivered'?'selected':''; ?>>ظ…ظƒطھظ…ظ„</option>
                                            <option value="cancelled" <?php echo $order['status']=='cancelled'?'selected':''; ?>>ظ…ظ„ط؛ظٹ</option>
                                        </select>
                                        <button type="submit" class="btn" style="padding: 5px 10px;">طھط­ط¯ظٹط«</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>ظ„ط§ طھظˆط¬ط¯ ط·ظ„ط¨ط§طھ ط­طھظ‰ ط§ظ„ط¢ظ†.</p>
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
