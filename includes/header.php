<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
    session_start();
}
// require_once __DIR__ . '/db_connect.php'; // يمكن تفعيلها عند الحاجة للاتصال بالقاعدة في جميع الصفحات
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/functions.php';

// تحديد المسار الأساسي لضمان عمل الروابط بشكل صحيح (سواء على السيرفر المحلي أو الاستضافة)
$base_url = '/'; 
if (strpos($_SERVER['REQUEST_URI'], '/emad-stor/') !== false) {
    $base_url = '/emad-stor/';
}

// تحديث قاعدة البيانات تلقائياً لدعم الصور المحولة لـ Base64
try {
    global $pdo;
    $pdo->exec("CREATE TABLE IF NOT EXISTS coupons (
        id SERIAL PRIMARY KEY,
        code VARCHAR(50) UNIQUE NOT NULL,
        discount_percent INTEGER NOT NULL,
        usage_limit INTEGER DEFAULT 100,
        used_count INTEGER DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    $pdo->exec("ALTER TABLE products ALTER COLUMN image_url TYPE TEXT");
    $pdo->exec("ALTER TABLE orders ALTER COLUMN payment_receipt TYPE TEXT");
    $pdo->exec("ALTER TABLE orders ADD COLUMN IF NOT EXISTS coupon_code VARCHAR(50) NULL");
    $pdo->exec("ALTER TABLE orders ADD COLUMN IF NOT EXISTS discount_amount DECIMAL(10,2) DEFAULT 0.00");
} catch (Exception $e) {}

// إعداد متغيرات الـ SEO الافتراضية
$page_title = $page_title ?? 'فضيات ابو عماد | الفضة والعقيق اليماني';
$page_desc = $page_desc ?? 'متجر فضيات أبو عماد يقدم أرقى الخواتم والمسابح من الفضة الخالصة المرصعة بالعقيق اليماني الأصلي. تسوق الآن.';
$page_url = $page_url ?? (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
// Use absolute path for images in OG tags for WhatsApp to render them correctly
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
$full_base_url = $protocol . $_SERVER['HTTP_HOST'] . $base_url;
$page_image = $page_image ?? $full_base_url . 'assets/images/منتجات/p35.jpg'; // صورة افتراضية
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_desc); ?>">
    
    <!-- Open Graph Meta Tags (WhatsApp, Facebook) -->
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_desc); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($page_image); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($page_url); ?>">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <link href="https://fonts.googleapis.com/css2?family=Playpen+Sans+Arabic:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/style.css?v=<?php echo filemtime(__DIR__ . '/../assets/css/style.css'); ?>">
    <!-- متغيرات العملة للسكربت -->
    <script>
        const currencyRate = <?php echo get_currency_rate(); ?>;
        const currencySymbol = '<?php echo get_currency_symbol(); ?>';
        const currentCurrency = '<?php echo get_current_currency(); ?>';
    </script>
</head>
<body>

<!-- ========== HEADER ========== -->
<header class="header" id="header">
    <div class="header-container">
        <div class="menu-toggle" id="menuToggle" style="cursor: pointer; font-size: 2.2rem; color: #E2E8F0;">
            <!-- استخدام رمز HTML كبديل أساسي لكي لا يختفي في حال لم يحمل خط FontAwesome -->
            &#9776;
        </div>
        <div class="logo">
            <img src="<?php echo $base_url; ?>assets/images/logo.svg" alt="فضيات ابو عماد">
            <span>فضيات ابو عماد</span>
        </div>
        <div class="icon-nav">
            <div class="search-box" style="position: relative;">
                <form action="<?php echo $base_url; ?>search.php" method="GET" style="display: flex; width: 100%; margin: 0;">
                    <input type="text" name="q" id="searchInput" placeholder="بحث عن خاتم، مسبحة..." autocomplete="off" required style="width: 100%; border: none; outline: none; padding: 0 10px; border-radius: 0 20px 20px 0;">
                    <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0 15px; color: #0B1B2B;"><i class="fas fa-search"></i></button>
                </form>
                <div id="searchResults" class="search-dropdown" style="display: none;"></div>
            </div>
            <a href="<?php echo $base_url; ?>track_orders.php" class="cart" style="margin-left: 10px;">
                <i class="fas fa-truck"></i>
                <span>تتبع الطلبات</span>
            </a>
            
            <!-- Currency Selector -->
            <div class="user-dropdown" style="position: relative; display: inline-block; margin-left: 10px;">
                <a href="#" class="cart">
                    <i class="fas fa-money-bill-wave"></i>
                    <span><?php echo get_current_currency(); ?></span>
                </a>
                <div class="dropdown-content" style="display: none; position: absolute; background-color: #f9f9f9; min-width: 120px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); z-index: 1;">
                    <a href="<?php echo $base_url; ?>set_currency.php?currency=USD" style="color: black; padding: 12px 16px; text-decoration: none; display: block;">دولار أمريكي ($)</a>
                    <a href="<?php echo $base_url; ?>set_currency.php?currency=SAR" style="color: black; padding: 12px 16px; text-decoration: none; display: block;">ريال سعودي (ر.س)</a>
                    <a href="<?php echo $base_url; ?>set_currency.php?currency=YER" style="color: black; padding: 12px 16px; text-decoration: none; display: block;">ريال يمني (ر.ي)</a>
                </div>
            </div>
            <a href="#" class="cart" id="cartBtn">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count">0</span>
                <span>سلة</span>
            </a>
            <?php if (is_logged_in()): ?>
                <div class="user-dropdown" style="position: relative; display: inline-block;">
                    <a href="#" class="user">
                        <i class="fas fa-user"></i>
                        <span><?php echo htmlspecialchars(get_user_name()); ?></span>
                    </a>
                    <div class="dropdown-content" style="display: none; position: absolute; background-color: #f9f9f9; min-width: 120px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); z-index: 1;">
                        <?php if (is_admin()): ?>
                            <?php
                            $pending_count = 0;
                            try {
                                global $pdo;
                                $stmt_pending = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'");
                                $pending_count = $stmt_pending->fetchColumn();
                            } catch (Exception $e) {}
                            ?>
                            <a href="<?php echo $base_url; ?>admin/index.php" style="color: black; padding: 12px 16px; text-decoration: none; display: block; border-bottom: 1px solid #eee;">لوحة التحكم</a>
                            <a href="<?php echo $base_url; ?>admin/orders.php" style="color: black; padding: 12px 16px; text-decoration: none; display: block; border-bottom: 1px solid #eee;">
                                إدارة الطلبات
                                <?php if ($pending_count > 0): ?>
                                    <span style="background: #ffcc00; color: #000; padding: 2px 8px; border-radius: 50%; font-size: 12px; margin-right: 5px; font-weight: bold;"><?php echo $pending_count; ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo $base_url; ?>profile.php" style="color: black; padding: 12px 16px; text-decoration: none; display: block; border-bottom: 1px solid #eee;">حسابي / طلباتي</a>
                        <a href="<?php echo $base_url; ?>auth/logout.php" style="color: #c62828; padding: 12px 16px; text-decoration: none; display: block; font-weight: bold;">تسجيل الخروج</a>
                    </div>
                </div>
                <style>
                    .user-dropdown:hover .dropdown-content { display: block !important; }
                    .dropdown-content a:hover { background-color: #f1f1f1; }
                </style>
            <?php else: ?>
                <a href="<?php echo $base_url; ?>pages/login.php" class="user">
                    <i class="fas fa-user"></i>
                    <span>حساب</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>

<!-- ========== SIDE MENU (هامبرغر) ========== -->
<div id="sideMenu" class="side-menu">
    <div class="side-menu-header">
        <h3>أقسام المتجر</h3>
        <button class="close-menu" id="closeMenu">&times;</button>
    </div>
    <ul class="side-menu-links">
        <li><a href="<?php echo $base_url; ?>index.php"><i class="fas fa-home"></i> الرئيسية</a></li>
        <?php
        try {
            global $pdo;
            $stmt_cats = $pdo->query("SELECT * FROM categories ORDER BY id ASC");
            $all_categories = $stmt_cats->fetchAll(PDO::FETCH_ASSOC);
            foreach ($all_categories as $cat) {
                // Determine icon
                $icon = 'fa-list';
                if (strpos($cat['slug'], 'ring') !== false) $icon = 'fa-ring';
                elseif (strpos($cat['slug'], 'bead') !== false) $icon = 'fa-praying-hands';
                elseif (strpos($cat['slug'], 'necklace') !== false) $icon = 'fa-layer-group';
                elseif (strpos($cat['slug'], 'bracelet') !== false) $icon = 'fa-hand-sparkles';
                elseif (strpos($cat['slug'], 'earring') !== false) $icon = 'fa-ear-listen';
                
                echo '<li><a href="' . $base_url . 'category.php?slug=' . htmlspecialchars($cat['slug']) . '"><i class="fas ' . $icon . '"></i> ' . htmlspecialchars($cat['name']) . '</a></li>';
            }
        } catch (Exception $e) {}
        ?>
        <li class="contact-link"><a href="#contact"><i class="fas fa-headset"></i> تواصل معنا</a></li>
    </ul>
</div>
<div id="menuOverlay" class="menu-overlay"></div>
