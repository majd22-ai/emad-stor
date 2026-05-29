<?php
require_once 'includes/db_connect.php';
include 'includes/header.php';

// جلب أحدث 8 منتجات للصفحة الرئيسية
$stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC LIMIT 8");
$stmt->execute();
$products = $stmt->fetchAll();
?>

<!-- ========== HERO ========== -->
<section class="hero-section" id="home">
    <div class="hero-background" id="heroSlideshow">
        <!-- الصورة الأولى (يمكنك تغيير المسار) -->
        <img src="<?php echo $base_url; ?>assets/images/منتجات/p35.jpg" alt="فضيات ابو عماد" class="hero-image slide active">
        <!-- الصورة الثانية (ضع المسار هنا) -->
        <img src="<?php echo $base_url; ?>assets/images/خواتم نسائي/wr5.jpg" alt="فضيات ابو عماد" class="hero-image slide">
        <!-- الصورة الثالثة (ضع المسار هنا) -->
        <img src="<?php echo $base_url; ?>assets/images/منتجات/p25_hero.jpg" alt="فضيات ابو عماد" class="hero-image slide">
    </div>
    <div class="hero-content">
        <h1>  ابو عماد للفضة والعقيق  </h1>
        <p>تشكيلة مختارة من خواتم الفضة والعقيق  اليماني بصياغة دقيقة وجودة موثوقة.</p>
    </div>
</section>

<!-- ========== PRODUCTS ========== -->
<div class="additional-content">
    <div class="shop-header">
        <h1>منتجات حصرية</h1>
        <p>تشكيلة فاخرة من الفضيات المنوعة والعقيق | اضغط على <span class="info-badge">ⓘ</span> لقراءة الوصف التفصيلي</p>
    </div>
    <div class="products-container" id="rings">
        <?php foreach ($products as $prod): ?>
        <div class="product" data-id="<?php echo htmlspecialchars($prod['id']); ?>" data-name="<?php echo htmlspecialchars($prod['title']); ?>" data-price="<?php echo htmlspecialchars($prod['price']); ?>" data-desc="<?php echo htmlspecialchars($prod['description']); ?>" data-img="<?php echo htmlspecialchars($prod['image_url']); ?>">
            <div class="product-img-wrapper">
                <a href="#" onclick="return false;">
                    <img src="<?php echo htmlspecialchars($prod['image_url']); ?>" alt="<?php echo htmlspecialchars($prod['title']); ?>">
                </a>
            </div>
            <h3><?php echo htmlspecialchars($prod['title']); ?></h3>
            <div class="price-info-row">
                <span class="price"><?php echo format_price($prod['price']); ?></span>
                <button type="button" class="info-btn" style="text-decoration: none; display: flex; align-items: center; justify-content: center; width: 30px; height: 30px;" title="التفاصيل">ⓘ</button>
            </div>
            <?php if (!empty($prod['sizes'])): ?>
            <div class="size-selection">
                <?php if (strpos($prod['category_name'], 'خواتم') !== false): ?>
                <div class="size-help"><a href="#" class="size-guide-link">❓ كيف تعرف مقاسك؟</a></div>
                <?php endif; ?>
                <select class="ring-size-select">
                    <option value="">اختر المقاس</option>
                    <?php 
                    $sizes_array = explode(',', $prod['sizes']);
                    foreach ($sizes_array as $sz):
                        $sz = trim($sz);
                        if (!empty($sz)):
                    ?>
                    <option value="<?php echo htmlspecialchars($sz); ?>"><?php echo htmlspecialchars($sz); ?></option>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </select>
            </div>
            <?php endif; ?>
            <button class="add-cart">➕ أضف إلى السلة</button>
            <?php if (is_admin()): ?>
                <div style="margin-top:10px; text-align:center;">
                    <a href="admin/products.php" style="color: blue; text-decoration: underline; font-size: 0.9em;">تعديل من الإدارة</a>
                </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
        
        <?php if (count($products) === 0): ?>
            <p style="text-align:center; width:100%;">لا توجد منتجات حالياً. قم بإضافة منتجات من لوحة التحكم.</p>
        <?php endif; ?>
    </div>
</div>

<!-- ========== MODALS ========== -->
<!-- Product info modal -->
<div id="infoModal" class="modal-overlay">
    <div class="modal-card">
        <img id="modalImg" class="modal-img" src="" alt="صورة المنتج">
        <div class="modal-content">
            <h3 id="modalTitle"></h3>
            <div id="modalDesc" class="modal-description"></div>
            <div id="modalPrice" class="modal-price"></div>
            <div style="display: flex; gap: 10px; margin-top: 15px; width: 100%;">
                <button class="close-modal-btn" id="shareProductBtn" style="background: #25D366; flex: 1;"><i class="fas fa-share-alt"></i> مشاركة</button>
                <button class="close-modal-btn" id="closeModalBtn" style="flex: 1;">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<!-- دليل قياس المقاس (طريقة الخيط والمسطرة) -->
<div id="sizeGuideModal" class="modal-overlay">
    <div class="modal-card size-guide-card" style="max-width: 550px;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border-bottom: 1px solid #eee;">
            <h3>📏 كيف تعرف مقاس خاتمك؟ (الخيط والمسطرة)</h3>
            <span id="closeSizeGuide" style="font-size: 2rem; cursor: pointer;">&times;</span>
        </div>
        <div class="modal-body" style="padding: 1.5rem;">
            <div style="display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center; margin-bottom: 1.5rem;">
                <div style="text-align: center; flex: 1; min-width: 120px;">
                    <img src="assets/images/si1.png" style="width: 100%; border-radius: 16px;">
                    <h4>1. لف الخيط</h4>
                    <p style="font-size: 0.8rem;">لف خيطاً حول قاعدة الإصبع</p>
                </div>
                <div style="text-align: center; flex: 1; min-width: 120px;">
                    <img src="assets/images/si2.png" style="width: 100%; border-radius: 16px;">
                    <h4>2. حدد نقطة الالتقاء</h4>
                    <p style="font-size: 0.8rem;">ضع علامة بقلم</p>
                </div>
                <div style="text-align: center; flex: 1; min-width: 120px;">
                    <img src="assets/images/si3.png" style="width: 100%; border-radius: 16px;">
                    <h4>3. قس الطول</h4>
                    <p style="font-size: 0.8rem;">اقرأ الطول بالمليمتر</p>
                </div>
            </div>
            <div style="background: #F8FAFE; padding: 1rem; border-radius: 20px;">
                <h4>📊 جدول تحويل سريع (محيط الإصبع → المقاس)</h4>
                <table style="width:100%; font-size:0.8rem; text-align:center; border-collapse: collapse;">
                    <thead><tr><th>محيط الإصبع (مم)</th><th>المقاس الموصى به</th></tr></thead>
                    <tbody>
                        <tr><td>44-46</td><td>44-46 (≈19 ملم)</td></tr>
                        <tr><td>47-49</td><td>47-49 (≈20 ملم)</td></tr>
                        <tr><td>50-52</td><td>50-52 (≈21 ملم)</td></tr>
                        <tr><td>53-55</td><td>53-55 (≈22 ملم)</td></tr>
                        <tr><td>56-58</td><td>56-58 (≈23 ملم)</td></tr>
                        <tr><td>59-61</td><td>59-61 (≈24 ملم)</td></tr>
                        <tr><td>62-64</td><td>62-64 (≈25 ملم)</td></tr>
                        <tr><td>65-67</td><td>65-67 (≈26 ملم)</td></tr>
                    </tbody>
                </table>
                <p style="margin-top: 0.8rem;"><i class="fas fa-lightbulb"></i> نصيحة: أضف 1-2 مم للخواتم العريضة.</p>
            </div>
        </div>
        <div class="modal-footer" style="padding: 1rem; text-align: center;">
            <button id="closeSizeGuideBtn" class="close-modal-btn" style="background:#0B1B2B;">فهمت، شكراً</button>
        </div>
    </div>
</div>

<!-- Category modal -->
<div class="modal" id="categoryModal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>اختر القسم</h2>
        <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 20px;">
            <?php
            try {
                $stmt_cats = $pdo->query("SELECT * FROM categories ORDER BY id ASC");
                $modal_categories = $stmt_cats->fetchAll(PDO::FETCH_ASSOC);
                foreach ($modal_categories as $cat) {
                    echo '<a href="category.php?slug=' . htmlspecialchars($cat['slug']) . '" style="padding: 10px; background: #f4f7fa; border-radius: 8px; text-decoration: none; color: #0b1b2b; font-weight: bold; border: 1px solid #e2e8f0;">' . htmlspecialchars($cat['name']) . '</a>';
                }
            } catch (Exception $e) {}
            ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>