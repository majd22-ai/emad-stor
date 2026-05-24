<?php
session_start();
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// ط­ظ…ط§ظٹط© ط§ظ„طµظپط­ط©
check_admin();

$base_url = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) ? '/emad-stor/' : '/';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ظ„ظˆط­ط© ط§ظ„طھط­ظƒظ… | ظپط¶ظٹط§طھ ط§ط¨ظˆ ط¹ظ…ط§ط¯</title>
    <link href="https://fonts.googleapis.com/css2?family=Playpen+Sans+Arabic:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Playpen Sans Arabic', sans-serif; background-color: #f4f7f6; margin: 0; display: flex; }
        .sidebar { width: 250px; background-color: #0B1B2B; color: white; height: 100vh; padding-top: 20px; }
        .sidebar h2 { text-align: center; margin-bottom: 30px; font-size: 1.5rem; }
        .sidebar a { display: block; color: white; padding: 15px 20px; text-decoration: none; border-bottom: 1px solid #1a365d; }
        .sidebar a:hover { background-color: #1a365d; }
        .content { flex: 1; padding: 40px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 20px; }
        h1 { color: #0B1B2B; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .stat-card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; border-top: 4px solid #0B1B2B; }
        .stat-card h3 { margin: 0 0 10px; color: #666; font-size: 1rem; }
        .stat-card .num { font-size: 2rem; font-weight: bold; color: #0B1B2B; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>ظ„ظˆط­ط© ط§ظ„طھط­ظƒظ…</h2>
    <a href="index.php"><i class="fas fa-home"></i> ط§ظ„ط±ط¦ظٹط³ظٹط©</a>
    <a href="categories.php"><i class="fas fa-list"></i> ط¥ط¯ط§ط±ط© ط§ظ„ط£ظ‚ط³ط§ظ…</a>
    <a href="products.php"><i class="fas fa-box"></i> ط¥ط¯ط§ط±ط© ط§ظ„ظ…ظ†طھط¬ط§طھ</a>
    <a href="orders.php"><i class="fas fa-shopping-cart"></i> ط¥ط¯ط§ط±ط© ط§ظ„ط·ظ„ط¨ط§طھ</a>
    <a href="coupons.php"><i class="fas fa-tags"></i> ط¥ط¯ط§ط±ط© ط§ظ„ظƒظˆط¨ظˆظ†ط§طھ</a>
    <a href="users.php"><i class="fas fa-users"></i> ط¥ط¯ط§ط±ط© ط§ظ„ظ…ط³طھط®ط¯ظ…ظٹظ†</a>
    <a href="../index.php"><i class="fas fa-store"></i> ط§ظ„ط¹ظˆط¯ط© ظ„ظ„ظ…طھط¬ط±</a>
    <a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> طھط³ط¬ظٹظ„ ط§ظ„ط®ط±ظˆط¬</a>
</div>

<div class="content">
    <h1>ظ…ط±ط­ط¨ط§ظ‹ ط¨ظƒ ظٹط§ <?php echo htmlspecialchars(get_user_name()); ?></h1>
    <div class="card">
        <p>ظ…ظ† ظ‡ظ†ط§ ظٹظ…ظƒظ†ظƒ ط¥ط¯ط§ط±ط© ط¬ظ…ظٹط¹ ط§ظ„ط£ظ‚ط³ط§ظ… ظˆط§ظ„ظ…ظ†طھط¬ط§طھ ط§ظ„ظ…ط¹ط±ظˆط¶ط© ظپظٹ ط§ظ„ظ…طھط¬ط± ط¨ط³ظ‡ظˆظ„ط©.</p>
    </div>

    <?php
    $prodCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $catCount = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $ordersCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();

    // ط¬ظ„ط¨ ط§ظ„ظ…ط¨ظٹط¹ط§طھ ط§ظ„ط´ظ‡ط±ظٹط©
    // ط§ط³طھط®ط¯ط§ظ… STRFTIME ظ„ظ„ط¹ظ…ظ„ ظ…ط¹ SQLite (ظ„ط£ظ† DATE_FORMAT ظ„ط§ ظٹط¹ظ…ظ„ ظپظٹ SQLite ط¥ط°ط§ ظƒظ†طھ طھط³طھط®ط¯ظ…ظ‡ط§) ط£ظˆ DATE_FORMAT ظ„ظ„ظ€ MySQL. 
    // ط³ظ†ط³طھط®ط¯ظ… ط§ط³طھط¹ظ„ط§ظ… ظٹط¯ط¹ظ… MySQL ط¨ط´ظƒظ„ ط£ط³ط§ط³ظٹ
    $revenue_query = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_price) as revenue FROM orders WHERE status != 'cancelled' GROUP BY DATE_FORMAT(created_at, '%Y-%m') ORDER BY month ASC LIMIT 6";
    try {
        $revenue_stmt = $pdo->query($revenue_query);
        $months = [];
        $revenues = [];
        while ($row = $revenue_stmt->fetch(PDO::FETCH_ASSOC)) {
            $months[] = $row['month'];
            $revenues[] = $row['revenue'];
        }
    } catch (Exception $e) {
        $months = []; $revenues = []; // fallback in case of SQLite dialect differences
    }

    // ط¬ظ„ط¨ ط§ظ„ظ…ظ†طھط¬ط§طھ ط§ظ„ط£ظƒط«ط± ظ…ط¨ظٹط¹ط§ظ‹
    $top_products_query = "SELECT p.title, SUM(oi.quantity) as total_sold FROM order_items oi JOIN products p ON oi.product_id = p.id GROUP BY oi.product_id ORDER BY total_sold DESC LIMIT 5";
    try {
        $top_products_stmt = $pdo->query($top_products_query);
        $product_names = [];
        $product_sales = [];
        while ($row = $top_products_stmt->fetch(PDO::FETCH_ASSOC)) {
            $product_names[] = $row['title'];
            $product_sales[] = $row['total_sold'];
        }
    } catch (Exception $e) {
        $product_names = []; $product_sales = [];
    }
    ?>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>ط¥ط¬ظ…ط§ظ„ظٹ ط§ظ„ظ…ط¨ظٹط¹ط§طھ (ط·ظ„ط¨ط§طھ)</h3>
            <div class="num"><?php echo $ordersCount; ?></div>
        </div>
        <div class="stat-card">
            <h3>ط¥ط¬ظ…ط§ظ„ظٹ ط§ظ„ظ…ظ†طھط¬ط§طھ</h3>
            <div class="num"><?php echo $prodCount; ?></div>
        </div>
        <div class="stat-card">
            <h3>ط§ظ„ط¹ظ…ظ„ط§ط، ط§ظ„ظ…ط³ط¬ظ„ظٹظ†</h3>
            <div class="num"><?php echo $userCount; ?></div>
        </div>
    </div>

    <!-- Charts Section -->
    <div style="display: flex; gap: 20px; margin-top: 40px; flex-wrap: wrap;">
        <!-- Line Chart -->
        <div class="card" style="flex: 2; min-width: 400px;">
            <h3 style="margin-top: 0;">ط§ظ„ط£ط±ط¨ط§ط­ ط§ظ„ط´ظ‡ط±ظٹط© (ط¨ط§ظ„ط¯ظˆظ„ط§ط±)</h3>
            <canvas id="revenueChart"></canvas>
        </div>

        <!-- Pie Chart -->
        <div class="card" style="flex: 1; min-width: 300px;">
            <h3 style="margin-top: 0;">ط£ظƒط«ط± ط§ظ„ظ…ظ†طھط¬ط§طھ ظ…ط¨ظٹط¹ط§ظ‹</h3>
            <canvas id="topProductsChart"></canvas>
        </div>
    </div>

</div>

<script>
    // ط¥ط¹ط¯ط§ط¯ ظ…ط®ط·ط· ط§ظ„ط£ط±ط¨ط§ط­
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($months); ?>,
            datasets: [{
                label: 'ط¥ط¬ظ…ط§ظ„ظٹ ط§ظ„ظ…ط¨ظٹط¹ط§طھ ($)',
                data: <?php echo json_encode($revenues); ?>,
                borderColor: '#FFD966',
                backgroundColor: 'rgba(255, 217, 102, 0.2)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // ط¥ط¹ط¯ط§ط¯ ظ…ط®ط·ط· ط§ظ„ظ…ظ†طھط¬ط§طھ ط§ظ„ط£ظƒط«ط± ظ…ط¨ظٹط¹ط§ظ‹
    const ctxProducts = document.getElementById('topProductsChart').getContext('2d');
    new Chart(ctxProducts, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($product_names); ?>,
            datasets: [{
                data: <?php echo json_encode($product_sales); ?>,
                backgroundColor: ['#0B1B2B', '#FFD966', '#4A627A', '#F8FAFC', '#E2E8F0'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>

</body>
</html>
