<?php
session_start();
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

check_admin();

$base_url = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) ? '/emad-stor/' : '/';
$message = '';

// ط¥ط¶ط§ظپط© ظƒظˆط¯ ط¬ط¯ظٹط¯
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_coupon'])) {
    $code = strtoupper(trim($_POST['code'] ?? ''));
    $discount = (int)($_POST['discount'] ?? 0);
    $limit = (int)($_POST['limit'] ?? 100);

    if (empty($code) || $discount <= 0 || $discount > 100) {
        $message = '<div class="alert alert-danger">ط§ظ„ط±ط¬ط§ط، ط¥ط¯ط®ط§ظ„ ط¨ظٹط§ظ†ط§طھ طµط­ظٹط­ط© (ظ†ط³ط¨ط© ط§ظ„ط®طµظ… ط¨ظٹظ† 1 ظˆ 100).</div>';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO coupons (code, discount_percent, usage_limit) VALUES (?, ?, ?)');
            $stmt->execute([$code, $discount, $limit]);
            $message = '<div class="alert alert-success">طھظ…طھ ط¥ط¶ط§ظپط© ظƒظˆط¯ ط§ظ„ط®طµظ… ط¨ظ†ط¬ط§ط­.</div>';
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">ط§ظ„ظƒظˆط¯ ظ…ظˆط¬ظˆط¯ ظ…ط³ط¨ظ‚ط§ظ‹ ط£ظˆ ط­ط¯ط« ط®ط·ط£.</div>';
        }
    }
}

// ط­ط°ظپ ظƒظˆط¯
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare('DELETE FROM coupons WHERE id = ?')->execute([$id]);
    header('Location: coupons.php');
    exit;
}

// ط¬ظ„ط¨ ط¬ظ…ظٹط¹ ط§ظ„ظƒظˆط¨ظˆظ†ط§طھ
$stmt = $pdo->query('SELECT * FROM coupons ORDER BY id DESC');
$coupons = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ط¥ط¯ط§ط±ط© ط§ظ„ظƒظˆط¨ظˆظ†ط§طھ | ظپط¶ظٹط§طھ ط§ط¨ظˆ ط¹ظ…ط§ط¯</title>
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
        h1, h2 { color: #0B1B2B; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: right; border-bottom: 1px solid #ddd; }
        th { background-color: #0B1B2B; color: white; }
        .btn { padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; color: white; font-family: inherit; font-weight: bold; }
        .btn-primary { background-color: #FFD966; color: #0B1B2B; }
        .btn-danger { background-color: #dc3545; }
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
        .alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
        .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .input-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-family: inherit; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>ظ„ظˆط­ط© ط§ظ„طھط­ظƒظ…</h2>
    <a href="index.php"><i class="fas fa-home"></i> ط§ظ„ط±ط¦ظٹط³ظٹط©</a>
    <a href="categories.php"><i class="fas fa-list"></i> ط¥ط¯ط§ط±ط© ط§ظ„ط£ظ‚ط³ط§ظ…</a>
    <a href="products.php"><i class="fas fa-box"></i> ط¥ط¯ط§ط±ط© ط§ظ„ظ…ظ†طھط¬ط§طھ</a>
    <a href="orders.php"><i class="fas fa-shopping-cart"></i> ط¥ط¯ط§ط±ط© ط§ظ„ط·ظ„ط¨ط§طھ</a>
    <a href="coupons.php" class="active"><i class="fas fa-tags"></i> ط¥ط¯ط§ط±ط© ط§ظ„ظƒظˆط¨ظˆظ†ط§طھ</a>
    <a href="users.php"><i class="fas fa-users"></i> ط¥ط¯ط§ط±ط© ط§ظ„ظ…ط³طھط®ط¯ظ…ظٹظ†</a>
    <a href="../index.php"><i class="fas fa-store"></i> ط§ظ„ط¹ظˆط¯ط© ظ„ظ„ظ…طھط¬ط±</a>
    <a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> طھط³ط¬ظٹظ„ ط§ظ„ط®ط±ظˆط¬</a>
</div>

<div class="content">
    <h1>ط¥ط¯ط§ط±ط© ط§ظ„ظƒظˆط¨ظˆظ†ط§طھ (Promo Codes)</h1>
    
    <?php echo $message; ?>

    <div style="display: flex; gap: 20px; align-items: flex-start;">
        <div class="card" style="flex: 1;">
            <h2>ط¥ط¶ط§ظپط© ظƒظˆط¯ ط¬ط¯ظٹط¯</h2>
            <form action="coupons.php" method="POST">
                <div class="input-group">
                    <label>ط±ظ…ط² ط§ظ„ظƒظˆط¯ (ظ…ط«ط§ظ„: EMAD20)</label>
                    <input type="text" name="code" required style="text-transform: uppercase;">
                </div>
                <div class="input-group">
                    <label>ظ†ط³ط¨ط© ط§ظ„ط®طµظ… (%)</label>
                    <input type="number" name="discount" min="1" max="100" required>
                </div>
                <div class="input-group">
                    <label>ط§ظ„ط­ط¯ ط§ظ„ط£ظ‚طµظ‰ ظ„ظ„ط§ط³طھط®ط¯ط§ظ… (ط¹ط¯ط¯ ط§ظ„ظ…ط±ط§طھ)</label>
                    <input type="number" name="limit" value="100" min="1" required>
                </div>
                <button type="submit" name="add_coupon" class="btn btn-primary" style="width: 100%;">ط¥ط¶ط§ظپط© ط§ظ„ظƒظˆط¯</button>
            </form>
        </div>

        <div class="card" style="flex: 2;">
            <h2>ط§ظ„ط£ظƒظˆط§ط¯ ط§ظ„ط­ط§ظ„ظٹط©</h2>
            <?php if (empty($coupons)): ?>
                <p>ظ„ط§ طھظˆط¬ط¯ ط£ظƒظˆط§ط¯ ط®طµظ… ط­ط§ظ„ظٹط§ظ‹.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ط§ظ„ظƒظˆط¯</th>
                            <th>ط§ظ„ط®طµظ…</th>
                            <th>ط§ظ„ط§ط³طھط®ط¯ط§ظ…ط§طھ</th>
                            <th>طھط§ط±ظٹط® ط§ظ„ط¥ط¶ط§ظپط©</th>
                            <th>ط¥ط¬ط±ط§ط،</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($coupons as $c): ?>
                            <tr>
                                <td><span style="background: #e2e8f0; padding: 5px 10px; border-radius: 4px; font-weight: bold;"><?php echo htmlspecialchars($c['code']); ?></span></td>
                                <td><span style="color: #28a745; font-weight: bold;"><?php echo $c['discount_percent']; ?>%</span></td>
                                <td><?php echo $c['used_count'] . ' / ' . $c['usage_limit']; ?></td>
                                <td><?php echo date('Y-m-d', strtotime($c['created_at'])); ?></td>
                                <td>
                                    <a href="coupons.php?delete=<?php echo $c['id']; ?>" class="btn btn-danger" onclick="return confirm('ظ‡ظ„ ط£ظ†طھ ظ…طھط£ظƒط¯ ظ…ظ† ط­ط°ظپ ظ‡ط°ط§ ط§ظ„ظƒظˆط¯طں');"><i class="fas fa-trash"></i> ط­ط°ظپ</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
