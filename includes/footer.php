<!-- ========== CART SIDEBAR ========== -->
<div class="cart-overlay" id="cartOverlay"></div>
<div class="cart-sidebar" id="cartSidebar">
    <div class="cart-header"><h2>سلة التسوق</h2><span id="closeCart">&times;</span></div>
    <div class="cart-content"><p>السلة فارغة</p></div>
    <div class="cart-footer">
        <div class="cart-total">الإجمالي: <span id="cartTotal">0</span> $</div>
        <button class="checkout-btn" id="checkoutBtn">تأكيد الطلب</button>
    </div>
</div>

<!-- ========== FOOTER ========== -->
<footer class="footer" id="contact">
    <div class="footer-container">
        <div class="footer-col">
            <h4>روابط مهمة</h4>
            <a href="<?php echo isset($base_url) ? $base_url : '/emad-stor/'; ?>pages/pages_footer/privacy.php">سياسة الخصوصية</a>
            <a href="<?php echo isset($base_url) ? $base_url : '/emad-stor/'; ?>pages/pages_footer/aboutus.php">من نحن ؟</a>
            <a href="<?php echo isset($base_url) ? $base_url : '/emad-stor/'; ?>pages/pages_footer/returns.php">سياسة الاستبدال والاسترجاع</a>
            <a href="<?php echo isset($base_url) ? $base_url : '/emad-stor/'; ?>pages/pages_footer/rsize.php"> كيف تعرف مقاسك؟</a>
            <a href="<?php echo isset($base_url) ? $base_url : '/emad-stor/'; ?>pages/pages_footer/blog.php">المدونة</a>
            <a href="<?php echo isset($base_url) ? $base_url : '/emad-stor/'; ?>pages/pages_footer/shipping-payment.php"> طرق الدفع والشحن </a>
        </div>
        <div class="footer-col">
            <h4>تواصل معنا</h4>
            <a href="https://www.instagram.com/invites/contact/?igsh=dxkhsk0mwkwl&utm_content=1kekc60"><i class="fab fa-instagram"></i> إنستغرام</a>
            <a href="https://www.facebook.com/share/19kHK1iN32/"><i class="fab fa-facebook"></i> فيسبوك</a>
            <a href="https://wa.me/message/IPW2DJPDADWYP1"><i class="fab fa-whatsapp"></i> واتساب</a>
            <a href="https://www.snapchat.com/add/aqiiqalyemeni?share_id=ell_Injy9hk&locale=en-US"><i class="fab fa-snapchat"></i> سناب شات</a>
        </div>
        <data value=""></data>
    </div>
    <div class="copyright"><p>© 2025 فضيات ابو عماد للفضة والأحجار الكريمة. جميع الحقوق محفوظة.</p></div>
</footer>

<!-- ========== COOKIES BANNER ========== -->
<div id="cookieConsentBanner" class="cookie-consent-banner">
    <div class="cookie-content">
        <i class="fas fa-cookie-bite cookie-icon"></i>
        <p>نحن نستخدم ملفات تعريف الارتباط (Cookies) لتحسين تجربة استخدامك لموقعنا. بالاستمرار في تصفح الموقع، فإنك توافق على استخدامنا لملفات تعريف الارتباط. <a href="<?php echo isset($base_url) ? $base_url : '/emad-stor/'; ?>pages/pages_footer/privacy.php">قراءة سياسة الخصوصية</a></p>
    </div>
    <div class="cookie-buttons">
        <button id="acceptCookies" class="cookie-btn accept-btn">موافق</button>
        <button id="declineCookies" class="cookie-btn decline-btn">رفض</button>
    </div>
</div>

<script src="<?php echo isset($base_url) ? $base_url : '/emad-stor/'; ?>assets/js/script.js"></script>
</body>
</html>
