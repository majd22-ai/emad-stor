<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
session_start();
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

if (!isset($_GET['id']) || !isset($_GET['p'])) {
    die("رابط الفاتورة غير صحيح.");
}

$order_id = (int)$_GET['id'];
$phone = $_GET['p'];
$type = isset($_GET['type']) ? $_GET['type'] : 'purchase';
if ($type === 'delivery') {
    $invoice_title = 'فاتورة توصيل (Delivery Waybill)';
} else {
    $invoice_title = 'فاتورة شراء (Purchase Invoice)';
}

// جلب تفاصيل الطلب
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    die("الفاتورة غير موجودة.");
}

// Security Check: Match phone number (ignoring '+')
$db_phone = str_replace('+', '', $order['customer_phone']);
if ($phone !== $db_phone) {
    die("عذراً، ليس لديك صلاحية لعرض هذه الفاتورة.");
}

// جلب عناصر الطلب
$stmt_items = $pdo->prepare("
    SELECT oi.*, p.title, p.price as current_price 
    FROM order_items oi 
    LEFT JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmt_items->execute([$order_id]);
$items = $stmt_items->fetchAll();

// حساب الأسعار التاريخية
$is_historical = (!empty($order['currency']) && !empty($order['exchange_rate']));
$currency_symbol = $is_historical ? get_currency_symbol($order['currency']) : get_currency_symbol('YER');

function format_historical($amount, $order, $is_historical, $currency_symbol) {
    if ($is_historical) {
        $hist_amount = $amount * $order['exchange_rate'];
        return ($order['currency'] === 'YER') ? number_format($hist_amount, 0) . ' ' . $currency_symbol : number_format($hist_amount, 2) . ' ' . $currency_symbol;
    }
    return format_price($amount);
}

$subtotal = 0;
foreach ($items as $item) {
    $subtotal += ($item['price'] * $item['quantity']);
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $invoice_title . ' - #' . $order_id; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Playpen+Sans+Arabic:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Playpen Sans Arabic', sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); font-size: 16px; line-height: 24px; color: #555; background: #fff; border-radius: 8px; }
        .invoice-box table { width: 100%; line-height: inherit; text-align: right; border-collapse: collapse; }
        .invoice-box table td { padding: 8px; vertical-align: top; }
        .invoice-box table tr td:nth-child(1) { text-align: right; }
        .invoice-box table tr td:nth-child(2) { text-align: left; }
        .invoice-box table tr.top table td { padding-bottom: 20px; }
        .invoice-box table tr.top table td.title { font-size: 35px; line-height: 45px; color: #333; font-weight: bold; }
        .invoice-box table tr.information table td { padding-bottom: 40px; }
        .invoice-box table tr.heading td { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; color: #333; }
        .invoice-box table tr.details td { padding-bottom: 20px; }
        .invoice-box table tr.item td { border-bottom: 1px solid #eee; text-align: center; }
        .invoice-box table tr.item td:first-child { text-align: right; }
        .invoice-box table tr.item td:last-child { text-align: left; }
        .invoice-box table tr.item.last td { border-bottom: none; }
        .invoice-box table tr.total td { border-top: 2px solid #eee; font-weight: bold; text-align: left; }
        .invoice-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #0B1B2B; padding-bottom: 20px; margin-bottom: 20px; }
        .invoice-title { font-size: 24px; color: #0B1B2B; margin: 0; }
        .store-details { text-align: left; }
        .store-name { font-size: 20px; font-weight: bold; color: #0B1B2B; }
        .customer-details { background: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .customer-details p { margin: 5px 0; }
        .totals-box { width: 100%; max-width: 300px; margin-left: auto; margin-top: 20px; background: #f9f9f9; padding: 15px; border-radius: 8px; }
        .totals-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .totals-row.grand-total { font-weight: bold; font-size: 1.2em; color: #0B1B2B; border-top: 2px solid #ddd; padding-top: 10px; margin-top: 10px; }
        
        .print-btn { display: block; width: 200px; margin: 20px auto; padding: 10px; text-align: center; background: #0B1B2B; color: #fff; text-decoration: none; border-radius: 5px; font-weight: bold; cursor: pointer; border: none; font-family: inherit; font-size: 16px; }
        .print-btn:hover { background: #1a365d; }
        
        @media only screen and (max-width: 600px) {
            .invoice-box { padding: 15px; }
            .invoice-header { flex-direction: column; text-align: center; }
            .store-details { text-align: center; margin-top: 15px; }
            .totals-box { width: 100%; }
        }
        
        @media print {
            body { background: #fff; padding: 0; }
            .invoice-box { box-shadow: none; border: none; padding: 0; max-width: 100%; }
            .print-btn { display: none; }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨️ طباعة الفاتورة</button>
    
    <div class="invoice-box" id="invoiceArea">
        <div class="invoice-header">
            <div>
                <h1 class="invoice-title"><?php echo $invoice_title; ?></h1>
                <p style="margin: 5px 0 0;">رقم الفاتورة: <strong>INV-<?php echo $order_id; ?></strong></p>
                <p style="margin: 5px 0 0;">تاريخ الإصدار: <?php echo date('Y-m-d H:i', strtotime($order['created_at'])); ?></p>
            </div>
            <div class="store-details">
                <div class="store-name">فضيات أبو عماد</div>
                <p style="margin: 5px 0 0;">صنعاء - باب اليمن</p>
                <p style="margin: 5px 0 0;">هاتف: +967 772 885 397</p>
            </div>
        </div>

        <div class="customer-details">
            <h3 style="margin-top: 0; color: #0B1B2B; border-bottom: 1px solid #ddd; padding-bottom: 5px;">تفاصيل العميل</h3>
            <p><strong>الاسم:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
            <p><strong>رقم الهاتف:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
            <p><strong>العنوان:</strong> <?php echo htmlspecialchars($order['customer_address']); ?></p>
            <p><strong>طريقة الدفع:</strong> <?php echo htmlspecialchars($order['payment_method'] ?? 'الدفع عند الاستلام'); ?></p>
            <p><strong>طريقة التوصيل:</strong> <?php echo htmlspecialchars($order['shipping_method'] ?? 'توصيل'); ?></p>
        </div>

        <table>
            <tr class="heading">
                <td style="text-align: right;">المنتج</td>
                <td style="text-align: center;">المقاس</td>
                <td style="text-align: center;">الكمية</td>
                <td style="text-align: center;">سعر الوحدة</td>
                <td style="text-align: left;">الإجمالي</td>
            </tr>

            <?php foreach ($items as $index => $item): 
                $name = $item['title'] ? htmlspecialchars($item['title']) : 'منتج محذوف';
                $unit_price_display = format_historical($item['price'], $order, $is_historical, $currency_symbol);
                $line_total_display = format_historical($item['price'] * $item['quantity'], $order, $is_historical, $currency_symbol);
            ?>
            <tr class="item <?php echo ($index === count($items) - 1) ? 'last' : ''; ?>">
                <td style="text-align: right;"><?php echo $name; ?></td>
                <td style="text-align: center;"><?php echo htmlspecialchars($item['size'] ?? '-'); ?></td>
                <td style="text-align: center;"><?php echo $item['quantity']; ?></td>
                <td style="text-align: center;"><?php echo $unit_price_display; ?></td>
                <td style="text-align: left;"><?php echo $line_total_display; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <div class="totals-box">
            <div class="totals-row">
                <span>المجموع الفرعي:</span>
                <span><?php echo format_historical($subtotal, $order, $is_historical, $currency_symbol); ?></span>
            </div>
            
            <?php if (!empty($order['delivery_fee']) && $order['delivery_fee'] > 0): ?>
            <div class="totals-row">
                <span>رسوم التوصيل:</span>
                <span><?php echo format_historical($order['delivery_fee'], $order, $is_historical, $currency_symbol); ?></span>
            </div>
            <?php endif; ?>

            <?php if (!empty($order['discount_amount']) && $order['discount_amount'] > 0): ?>
            <div class="totals-row">
                <span>الخصم <?php echo $order['coupon_code'] ? '('.htmlspecialchars($order['coupon_code']).')' : ''; ?>:</span>
                <span style="color: red;">-<?php echo format_historical($order['discount_amount'], $order, $is_historical, $currency_symbol); ?></span>
            </div>
            <?php endif; ?>

            <div class="totals-row grand-total">
                <span>الإجمالي النهائي:</span>
                <span><?php echo format_historical($order['total_price'], $order, $is_historical, $currency_symbol); ?></span>
            </div>
        </div>


        <?php if ($type === 'delivery'): ?>
        <div style="margin-top: 30px; padding: 20px; border: 2px dashed #0B1B2B; border-radius: 8px; background-color: #fff9e6; text-align: center;">
            <h3 style="margin-top: 0; color: #d35400;">تعليمات للمندوب</h3>
            <?php 
                $payment = $order['payment_method'] ?? '';
                $is_cod = strpos($payment, 'استلام') !== false || empty($payment);
            ?>
            <p style="font-size: 1.3em; font-weight: bold; margin-bottom: 5px;">المبلغ المطلوب تحصيله من العميل:</p>
            <?php if ($is_cod): ?>
                <div style="font-size: 2em; font-weight: bold; color: #c0392b; background: #fadbd8; display: inline-block; padding: 10px 20px; border-radius: 8px; margin-bottom: 10px;">
                    <?php echo format_historical($order['total_price'], $order, $is_historical, $currency_symbol); ?>
                </div>
            <?php else: ?>
                <div style="font-size: 1.5em; font-weight: bold; color: #27ae60; background: #d5f5e3; display: inline-block; padding: 10px 20px; border-radius: 8px; margin-bottom: 10px;">
                    الطلب مدفوع مسبقاً (0)
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 40px; color: #777; font-size: 0.9em; border-top: 1px dashed #ccc; padding-top: 20px;">
            <p>شكراً لتسوقكم من متجر أبو عماد للفضيات.</p>
            <p>نأمل أن تحوز منتجاتنا على إعجابكم.</p>
        </div>
    </div>
</body>
</html>
