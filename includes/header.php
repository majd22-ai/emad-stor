<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// require_once __DIR__ . '/db_connect.php'; // يمكن تفعيلها عند الحاجة للاتصال بالقاعدة في جميع الصفحات
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/functions.php';

// تحديد المسار الأساسي لضمان عمل الروابط بشكل صحيح
$base_url = '/emad-stor/';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/style.css?v=<?php echo time(); ?>">
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
        <li><a href="<?php echo $base_url; ?>category.php?slug=me-rings"><i class="fas fa-ring"></i> خواتم رجالية</a></li>
        <li><a href="<?php echo $base_url; ?>category.php?slug=men-beads"><i class="fas fa-praying-hands"></i> مسابح</a></li>
        <li><a href="<?php echo $base_url; ?>category.php?slug=wo-rings"><i class="fas fa-gem"></i> خواتم نسائية</a></li>
        <li><a href="<?php echo $base_url; ?>category.php?slug=wo-necklaces"><i class="fas fa-layer-group"></i> قلائد</a></li>
        <li><a href="<?php echo $base_url; ?>category.php?slug=wo-bracelets"><i class="fas fa-hand-sparkles"></i> أساور</a></li>
        <li><a href="<?php echo $base_url; ?>category.php?slug=wo-earrings"><i class="fas fa-ear-listen"></i> أقراط</a></li>
        <li class="contact-link"><a href="#contact"><i class="fas fa-headset"></i> تواصل معنا</a></li>
    </ul>
</div>
<div id="menuOverlay" class="menu-overlay"></div>
