<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
session_start();
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// حماية الصفحة
check_admin();

$current_page = 'invoices.php';

// جلب الفواتير (الطلبات المكتملة)
$stmt = $pdo->query("SELECT id, user_id, customer_name, customer_phone, total_price, status, created_at, currency, exchange_rate FROM orders WHERE status = 'delivered' ORDER BY created_at DESC");
$invoices = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الفواتير | فضيات ابو عماد</title>
    <link href="https://fonts.googleapis.com/css2?family=Playpen+Sans+Arabic:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { font-family: 'Playpen Sans Arabic', sans-serif; background-color: #f4f7f6; margin: 0; display: flex; }
        .sidebar { width: 250px; background-color: #0B1B2B; color: white; height: 100vh; padding-top: 20px; position: fixed; right: 0; top: 0; }
        .sidebar h2 { text-align: center; margin-bottom: 30px; font-size: 1.5rem; }
        .sidebar a { display: block; color: white; padding: 15px 20px; text-decoration: none; border-bottom: 1px solid #1a365d; transition: 0.2s; }
        .sidebar a:hover, .sidebar a.active { background-color: #1a365d; border-right: 4px solid #FFD966; }
        .content { flex: 1; padding: 40px; margin-right: 250px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { color: #0B1B2B; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: right; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; color: #333; }
        tr:hover { background-color: #f1f1f1; }
        .btn { padding: 8px 12px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: bold; font-size: 0.9em; transition: 0.2s; color: white; }
        .btn-print { background-color: #0B1B2B; }
        .btn-print:hover { background-color: #1a365d; }
        .btn-whatsapp { background-color: #25D366; }
        .btn-whatsapp:hover { background-color: #1da851; }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="content">
        <h1><i class="fas fa-file-invoice-dollar"></i> إدارة الفواتير</h1>
        
        <?php if(isset($success)) echo "<div style='background:#d4edda; color:#155724; padding:15px; margin-bottom:20px; border-radius:5px;'>$success</div>"; ?>

        <div class="card">
            <?php if (count($invoices) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>رقم الفاتورة</th>
                            <th>تاريخ الإصدار</th>
                            <th>العميل</th>
                            <th>رقم الهاتف</th>
                            <th>الإجمالي</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($invoices as $inv): 
                            $hist_price_display = '';
                            if (!empty($inv['currency']) && !empty($inv['exchange_rate'])) {
                                $hist_price = $inv['total_price'] * $inv['exchange_rate'];
                                $symbol = get_currency_symbol($inv['currency']);
                                $hist_price_display = ($inv['currency'] === 'YER') ? number_format($hist_price, 0) . ' ' . $symbol : number_format($hist_price, 2) . ' ' . $symbol;
                            } else {
                                $hist_price_display = format_price($inv['total_price']);
                            }

                            // Generate WhatsApp Message
                            $wa_text = "مرحباً {$inv['customer_name']}\n";
                            $wa_text .= "تم إصدار فاتورة شراء لطلبك رقم #{$inv['id']} من متجر أبو عماد.\n";
                            $wa_text .= "الإجمالي: {$hist_price_display}\n";
                            $wa_text .= "شكراً لتسوقك معنا!";
                            $wa_url = "https://wa.me/" . (strpos($inv['customer_phone'], '+') === 0 ? str_replace('+', '', $inv['customer_phone']) : $inv['customer_phone']) . "?text=" . urlencode($wa_text);
                        ?>
                            <tr>
                                <td><strong>#INV-<?php echo $inv['id']; ?></strong></td>
                                <td><?php echo date('Y-m-d H:i', strtotime($inv['created_at'])); ?></td>
                                <td><?php echo htmlspecialchars($inv['customer_name']); ?></td>
                                <td><a href="https://wa.me/<?php echo strpos($inv['customer_phone'], '+') === 0 ? str_replace('+', '', $inv['customer_phone']) : $inv['customer_phone']; ?>" target="_blank" style="text-decoration: none; color: #25D366;"><i class="fab fa-whatsapp"></i> <?php echo htmlspecialchars($inv['customer_phone']); ?></a></td>
                                <td><strong style="color: #0B1B2B;"><?php echo $hist_price_display; ?></strong></td>
                                <td>
                                    <a href="view_invoice.php?id=<?php echo $inv['id']; ?>&type=sales" class="btn btn-print" target="_blank"><i class="fas fa-print"></i> طباعة (مبيعات)</a>
                                    <a href="view_invoice.php?id=<?php echo $inv['id']; ?>&type=purchase" class="btn btn-print" target="_blank" style="background-color: #6c757d;"><i class="fas fa-print"></i> طباعة (مشتري)</a>
                                    <a href="<?php echo htmlspecialchars($wa_url); ?>" class="btn btn-whatsapp" target="_blank"><i class="fab fa-whatsapp"></i> إرسال للعميل</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>لا توجد فواتير (طلبات مكتملة) حتى الآن.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
