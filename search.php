<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
    session_start();
require_once 'includes/db_connect.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$searchTerm = '%' . $q . '%';
$products = [];

if (!empty($q)) {
    $stmt_prod = $pdo->prepare("SELECT * FROM products WHERE title LIKE ? OR description LIKE ? ORDER BY id DESC");
    $stmt_prod->execute([$searchTerm, $searchTerm]);
    $products = $stmt_prod->fetchAll();
}

include 'includes/header.php';
?>

<!-- ========== HERO FOR SEARCH ========== -->
<section class="hero-section" id="category-hero" style="height: 40vh; min-height: 300px;">
    <div class="hero-background">
        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #0B1B2B 0%, #1A2A3A 100%);"></div>
    </div>
    <div class="hero-content">
        <h1>نتائج البحث</h1>
        <p>
            <?php if (!empty($q)): ?>
                نتائج البحث عن: "<?php echo htmlspecialchars($q); ?>"
            <?php else: ?>
                الرجاء إدخال كلمة للبحث
            <?php endif; ?>
        </p>
    </div>
</section>

<!-- ========== PRODUCTS ========== -->
<div class="additional-content">
    <div class="shop-header" style="margin-bottom: 2rem;">
        <h2>المنتجات المطابقة</h2>
    </div>
    
    <div class="products-container" id="category-products">
        <?php foreach ($products as $prod): ?>
        <div class="product" data-id="<?php echo htmlspecialchars($prod['id']); ?>" data-name="<?php echo htmlspecialchars($prod['title']); ?>" data-price="<?php echo htmlspecialchars($prod['price']); ?>" data-desc="<?php echo htmlspecialchars($prod['description']); ?>" data-img="<?php echo htmlspecialchars($prod['image_url']); ?>">
            <div class="product-img-wrapper">
                <img src="<?php echo htmlspecialchars($prod['image_url']); ?>" alt="<?php echo htmlspecialchars($prod['title']); ?>">
            </div>
            <h3><?php echo htmlspecialchars($prod['title']); ?></h3>
            <div class="price-info-row">
                <span class="price"><?php echo format_price($prod['price']); ?></span>
                <button class="info-btn">ⓘ</button>
            </div>
            <div class="size-selection">
                <div class="size-help"><a href="#" class="size-guide-link">❓ كيف تعرف مقاسك؟</a></div>
                <select class="ring-size-select">
                    <option value="">اختر المقاس</option>
                    <option value="44-46 (≈19ملم)">مقاس 44-46 (≈19 ملم)</option>
                    <option value="47-49 (≈20ملم)">مقاس 47-49 (≈20 ملم)</option>
                    <option value="50-52 (≈21ملم)">مقاس 50-52 (≈21 ملم)</option>
                    <option value="53-55 (≈22ملم)">مقاس 53-55 (≈22 ملم)</option>
                    <option value="56-58 (≈23ملم)">مقاس 56-58 (≈23 ملم)</option>
                    <option value="59-61 (≈24ملم)">مقاس 59-61 (≈24 ملم)</option>
                    <option value="62-64 (≈25ملم)">مقاس 62-64 (≈25 ملم)</option>
                    <option value="65-67 (≈26ملم)">مقاس 65-67 (≈26 ملم)</option>
                </select>
            </div>
            <button class="add-cart">➕ أضف إلى السلة</button>
            <?php if (is_admin()): ?>
                <div style="margin-top:10px; text-align:center;">
                    <a href="admin/edit_product.php?id=<?php echo $prod['id']; ?>" style="color: blue; text-decoration: underline; font-size: 0.9em;">تعديل</a>
                </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
        
        <?php if (count($products) === 0 && !empty($q)): ?>
            <p style="text-align:center; width:100%; padding: 2rem;">عذراً، لم نتمكن من العثور على أي منتجات تطابق "<?php echo htmlspecialchars($q); ?>"</p>
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
                    <img src="assets/images/si1.png" style="width: 100%; border-radius: 16px;" onerror="this.src='https://via.placeholder.com/150'">
                    <h4>1. لف الخيط</h4>
                    <p style="font-size: 0.8rem;">لف خيطاً حول قاعدة الإصبع</p>
                </div>
                <div style="text-align: center; flex: 1; min-width: 120px;">
                    <img src="assets/images/si2.png" style="width: 100%; border-radius: 16px;" onerror="this.src='https://via.placeholder.com/150'">
                    <h4>2. حدد نقطة الالتقاء</h4>
                    <p style="font-size: 0.8rem;">ضع علامة بقلم</p>
                </div>
                <div style="text-align: center; flex: 1; min-width: 120px;">
                    <img src="assets/images/si3.png" style="width: 100%; border-radius: 16px;" onerror="this.src='https://via.placeholder.com/150'">
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
            </div>
        </div>
        <div class="modal-footer" style="padding: 1rem; text-align: center;">
            <button id="closeSizeGuideBtn" class="close-modal-btn" style="background:#0B1B2B;">فهمت، شكراً</button>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
