<?php
require_once '../includes/db_connect.php';
include '../includes/header.php';

// جلب المنتجات الخاصة بقسم "أساور نسائية" عن طريق slug = wo-bracelets
$stmt = $pdo->prepare("SELECT p.* FROM products p JOIN categories c ON p.category_id = c.id WHERE c.slug = 'wo-bracelets' ORDER BY p.id DESC");
$stmt->execute();
$products = $stmt->fetchAll();
?>

    <!-- ==================== PRODUCTS SECTION ==================== -->
    <div class="additional-content">
        <div class="shop-header">
            <h1>أساور نسائية</h1>
            <p>تشكيلة فاخرة من الأساور | اضغط على <span class="info-badge">ⓘ</span> لقراءة الوصف التفصيلي</p>
        </div>

        <div class="products-container" id="rings">
            <?php foreach ($products as $prod): ?>
            <div class="product" data-name="<?php echo htmlspecialchars($prod['title']); ?>" data-price="<?php echo htmlspecialchars($prod['price']); ?>" data-desc="<?php echo htmlspecialchars($prod['description']); ?>" data-img="../<?php echo htmlspecialchars($prod['image_url']); ?>">
                <div class="product-img-wrapper">
                    <img src="../<?php echo htmlspecialchars($prod['image_url']); ?>" alt="<?php echo htmlspecialchars($prod['title']); ?>">
                </div>
                <h3><?php echo htmlspecialchars($prod['title']); ?></h3>
                <div class="price-info-row">
                    <span class="price"><?php echo format_price($prod['price']); ?></span>
                    <button class="info-btn" aria-label="معلومات المنتج">ⓘ</button>
                </div>
                <button class="add-cart">➕ أضف إلى السلة</button>
                <?php if (is_admin()): ?>
                    <div style="margin-top:10px; text-align:center;">
                        <a href="../admin/products.php" style="color: blue; text-decoration: underline; font-size: 0.9em;">تعديل من الإدارة</a>
                    </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
            
            <?php if (count($products) === 0): ?>
                <p style="text-align:center; width:100%;">لا توجد منتجات حالياً في هذا القسم.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- ==================== MODALS ==================== -->
    <!-- Product Info Modal -->
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

    <!-- Category Modal -->
    <div class="modal" id="categoryModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>اختر المجموعة</h2>
            <div class="dropdown women">
                <a href="#" class="main-link">المجموعة النسائية</a>
                <ul class="dropdown-menu">
                    <li><a href="wo-rings.php">خواتم</a></li>
                    <li><a href="wo-necklaces.php">قلائد</a></li>
                    <li><a href="wo-bracelets.php">أساور</a></li>
                    <li><a href="wo-earrings.php">أقراط</a></li>
                </ul>
            </div>
            <div class="dropdown men">
                <a href="#" class="main-link">المجموعة الرجالية</a>
                <ul class="dropdown-menu">
                    <li><a href="me-rings.php">خواتم</a></li>
                    <li><a href="men-beads.php">مسابح</a></li>
                </ul>
            </div>
        </div>
    </div>

<?php include '../includes/footer.php'; ?>