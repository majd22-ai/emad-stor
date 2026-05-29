<?php
// تحديد الصفحة الحالية لتفعيل الرابط المناسب (اختياري، يمكن تطويره لاحقاً)
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <h2>لوحة التحكم</h2>
    <a href="index.php" class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>"><i class="fas fa-home"></i> الرئيسية</a>
    <a href="categories.php" class="<?php echo $current_page == 'categories.php' ? 'active' : ''; ?>"><i class="fas fa-list"></i> إدارة الأقسام</a>
    <a href="products.php" class="<?php echo $current_page == 'products.php' ? 'active' : ''; ?>"><i class="fas fa-box"></i> إدارة المنتجات</a>
    <a href="orders.php" class="<?php echo $current_page == 'orders.php' ? 'active' : ''; ?>"><i class="fas fa-shopping-cart"></i> إدارة الطلبات</a>
    <a href="sales.php" class="<?php echo $current_page == 'sales.php' ? 'active' : ''; ?>"><i class="fas fa-chart-line"></i> المبيعات</a>
    <a href="coupons.php" class="<?php echo $current_page == 'coupons.php' ? 'active' : ''; ?>"><i class="fas fa-tags"></i> إدارة الكوبونات</a>
    <a href="users.php" class="<?php echo $current_page == 'users.php' ? 'active' : ''; ?>"><i class="fas fa-users"></i> إدارة المستخدمين</a>
    <a href="../index.php"><i class="fas fa-store"></i> العودة للمتجر</a>
    <a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
</div>
