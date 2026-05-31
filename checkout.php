<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
    session_start();
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// التأكد من وجود منتجات في السلة
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit;
}

$cart = $_SESSION['cart'];
$original_total_price = 0;
foreach ($cart as $item) {
    $original_total_price += $item['price'] * $item['quantity'];
}

$discount_amount = 0;
$coupon_code = null;
if (isset($_SESSION['coupon'])) {
    $coupon_code = $_SESSION['coupon']['code'];
    $discount_percent = $_SESSION['coupon']['discount'];
    $discount_amount = $original_total_price * ($discount_percent / 100);
}

$total_price = $original_total_price - $discount_amount;

$error = '';
$success = false;

// معالجة إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $error = 'انتهت صلاحية الجلسة (CSRF). يرجى تحديث الصفحة والمحاولة مجدداً.';
    } else {
        $name = trim($_POST['customer_name'] ?? '');
        $phone = trim($_POST['customer_phone'] ?? '');
    $address = trim($_POST['customer_address'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? 'الدفع عند الاستلام');
    $shipping_method = trim($_POST['shipping_method'] ?? 'توصيل');
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // إذا كان مسجل دخول

    $receipt_path = null;

    if (empty($name) || empty($phone) || empty($address)) {
        $error = 'الرجاء تعبئة جميع الحقول المطلوبة.';
    } else {
        // معالجة رفع صورة الإيصال كـ Base64
        if ($payment_method !== 'الدفع عند الاستلام' && isset($_FILES['payment_receipt']) && $_FILES['payment_receipt']['error'] === UPLOAD_ERR_OK) {
            $file_extension = strtolower(pathinfo($_FILES['payment_receipt']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf', 'webp'];
            
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $_FILES['payment_receipt']['tmp_name']);
            finfo_close($finfo);
            $allowed_mimes = ['image/jpeg', 'image/png', 'application/pdf', 'image/webp'];
            
            if (in_array($file_extension, $allowed_extensions) && in_array($mime_type, $allowed_mimes)) {
                $fileContent = file_get_contents($_FILES['payment_receipt']['tmp_name']);
                if ($fileContent !== false) {
                    $base64 = base64_encode($fileContent);
                    $receipt_path = 'data:' . $mime_type . ';base64,' . $base64;
                } else {
                    $error = 'حدث خطأ أثناء قراءة صورة الإيصال.';
                }
            } else {
                $error = 'عذراً، يسمح فقط برفع الصور الحقيقية (JPG, PNG) أو ملفات PDF الصالحة.';
            }
        } elseif ($payment_method !== 'الدفع عند الاستلام') {
            $error = 'الرجاء إرفاق صورة الإيصال ليتم تأكيد طلبك.';
        }

        if (!$error) {
            try {
                $pdo->beginTransaction();

                $currency = get_current_currency();
                $exchange_rate = get_currency_rate($currency);

                $latitude = isset($_POST['latitude']) && $_POST['latitude'] !== '' ? $_POST['latitude'] : null;
                $longitude = isset($_POST['longitude']) && $_POST['longitude'] !== '' ? $_POST['longitude'] : null;
                $delivery_fee = isset($_POST['delivery_fee']) ? (float)$_POST['delivery_fee'] : 0;
                
                // Add delivery fee to the total price
                $total_price_with_delivery = $total_price + $delivery_fee;

                // إدخال الطلب الرئيسي
                $stmt = $pdo->prepare("INSERT INTO orders (user_id, customer_name, customer_phone, customer_address, payment_method, shipping_method, total_price, status, payment_receipt, currency, exchange_rate, coupon_code, discount_amount, latitude, longitude, delivery_fee) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', ?, ?, ?, ?, ?, ?, ?, ?) RETURNING id");
                $stmt->execute([$user_id, $name, $phone, $address, $payment_method, $shipping_method, $total_price_with_delivery, $receipt_path, $currency, $exchange_rate, $coupon_code, $discount_amount, $latitude, $longitude, $delivery_fee]);
                $order_id = $stmt->fetchColumn();

                // تحديث عداد الكوبون إذا تم استخدامه
                if ($coupon_code) {
                    $pdo->prepare("UPDATE coupons SET used_count = used_count + 1 WHERE code = ?")->execute([$coupon_code]);
                }

                // إدخال عناصر الطلب
                $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, size) VALUES (?, ?, ?, ?, ?)");
                foreach ($cart as $item) {
                    $prod_id = ($item['id'] > 0) ? $item['id'] : null;
                    $stmt_item->execute([$order_id, $prod_id, $item['quantity'], $item['price'], $item['size']]);
                }

                $pdo->commit();

                // إرسال بريد للإدارة بوجود طلب جديد
                try {
                    $stmt_admins = $pdo->query("SELECT email FROM users WHERE role = 'admin' AND email IS NOT NULL AND email != ''");
                    $admins = $stmt_admins->fetchAll();
                    if (count($admins) > 0) {
                        $subject = "طلب جديد #" . $order_id . " - متجر أبو عماد";
                        $message = "مرحباً،\n\nيوجد طلب جديد برقم #" . $order_id . ".\n";
                        $message .= "العميل: " . $name . "\n";
                        $message .= "الإجمالي: " . format_price($total_price, $currency) . "\n\n";
                        $message .= "يرجى الدخول للوحة التحكم لمراجعة الطلب.";
                        
                        $headers = "From: noreply@emad-stor.com\r\n";
                        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
                        
                        foreach ($admins as $admin) {
                            @mail($admin['email'], $subject, $message, $headers);
                        }
                    }
                } catch (Exception $e) {
                    // تجاهل أخطاء البريد
                }

                // إفراغ السلة بعد نجاح الطلب
                $_SESSION['cart'] = [];
                $success = true;

            } catch (Exception $e) {
                $pdo->rollBack();
                $error = 'حدث خطأ أثناء حفظ الطلب، الرجاء المحاولة لاحقاً.';
                // error_log($e->getMessage());
            }
        }
        }
    }
}

include 'includes/header.php';

// Store configuration for delivery
// Store configuration for delivery
$store_lat = 15.3519835; // إحداثيات المتجر
$store_lon = 44.2158552;
$delivery_rate_per_km = 500; // تكلفة التوصيل لكل كيلومتر
?>

<!-- خريطة Leaflet تم إزالتها بناءً على طلب النظام الجديد -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
<style>
    /* تصحيح اتجاه حقل الهاتف ومربع الأعلام ليناسب الصفحة العربية */
    .iti { width: 100%; direction: ltr; }
    .iti__country-list { text-align: left; direction: ltr; }
    .iti__flag-container { border-radius: 50px 0 0 50px; overflow: hidden; }
    .input-group.phone-group input { padding-left: 55px !important; }
</style>
<div class="checkout-container">
    <?php if ($success): ?>
        <div class="success-message" style="text-align: center; padding: 3rem 1rem;">
            <i class="fas fa-check-circle" style="font-size: 4rem; color: #4CAF50; margin-bottom: 1rem;"></i>
            <h2 style="color: #0B1B2B; margin-bottom: 1rem;">تم تأكيد طلبك بنجاح!</h2>
            <h3 style="color: #0d47a1; margin-bottom: 1rem; font-size: 1.5rem;">رقم الطلب الخاص بك: #<?php echo isset($order_id) ? $order_id : ''; ?></h3>
            <p style="color: #666; margin-bottom: 1rem;">يرجى الاحتفاظ برقم الطلب لتتمكن من متابعته لاحقاً عبر زر "تتبع الطلبات".</p>
            <a href="track_orders.php" class="login-btn" style="display: inline-block; width: auto; padding: 0.8rem 2rem; text-decoration: none; background: #FF9800; margin-bottom: 1rem;">تتبع طلبك الآن</a><br>
            <a href="index.php" style="color: #666; text-decoration: underline;">العودة للرئيسية</a>
        </div>
    <?php else: ?>
        <h2 style="text-align: center; margin-bottom: 2rem; color: #0B1B2B;">إتمام الطلب</h2>
        
        <?php if ($error): ?>
            <div style="background-color: #ffebee; color: #c62828; padding: 10px; border-radius: 8px; text-align: center; margin-bottom: 15px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
            <!-- تفاصيل الطلب -->
            <div style="flex: 1; min-width: 300px; background: #F8FAFE; padding: 1.5rem; border-radius: 12px;">
                <h3 style="margin-bottom: 1rem; border-bottom: 1px solid #E2E8F0; padding-bottom: 0.5rem;">ملخص الطلب</h3>
                <ul style="list-style: none; padding: 0; margin-bottom: 1rem;">
                    <?php foreach ($cart as $item): ?>
                    <li style="display: flex; justify-content: space-between; margin-bottom: 0.8rem; font-size: 0.9rem;">
                        <span><?php echo htmlspecialchars($item['name']); ?> (<?php echo htmlspecialchars($item['size']); ?>) × <?php echo $item['quantity']; ?></span>
                        <span style="font-weight: bold;"><?php echo format_price($item['price'] * $item['quantity']); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                
                <div style="border-top: 2px solid #E2E8F0; padding-top: 1rem;">
                    <?php if (isset($_SESSION['coupon'])): ?>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 1rem; color: #718096;">
                            <span>المجموع الفرعي:</span>
                            <span><?php echo format_price($original_total_price); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 1rem; color: #48BB78;">
                            <span>خصم (<?php echo $_SESSION['coupon']['code']; ?>):</span>
                            <span>-<?php echo format_price($discount_amount); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <div id="deliveryFeeRow" style="display: none; justify-content: space-between; margin-bottom: 0.5rem; font-size: 1rem; color: #4A5568;">
                        <span>رسوم التوصيل:</span>
                        <span id="deliveryFeeDisplay">0</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: bold; margin-top: 0.5rem;">
                        <span>الإجمالي:</span>
                        <span style="color: #0B1B2B;" id="finalTotalDisplay" data-base-total="<?php echo $total_price; ?>"><?php echo format_price($total_price); ?></span>
                    </div>
                </div>

                <!-- إدخال كود الخصم -->
                <div style="margin-top: 1.5rem; border-top: 1px dashed #CBD5E0; padding-top: 1rem;">
                    <label style="display: block; font-size: 0.9rem; color: #4A5568; margin-bottom: 0.5rem;">هل لديك كود خصم؟</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" id="promoCodeInput" placeholder="أدخل الكود هنا" value="<?php echo isset($_SESSION['coupon']) ? htmlspecialchars($_SESSION['coupon']['code']) : ''; ?>" style="flex: 1; padding: 10px; border: 1px solid #CBD5E0; border-radius: 8px; font-family: inherit; outline: none; text-transform: uppercase;">
                        <?php if (isset($_SESSION['coupon'])): ?>
                            <button type="button" id="removePromoBtn" style="background: #F56565; color: white; border: none; padding: 10px 15px; border-radius: 8px; cursor: pointer; font-family: inherit; font-weight: bold;">إزالة</button>
                        <?php else: ?>
                            <button type="button" id="applyPromoBtn" style="background: #0B1B2B; color: #FFD966; border: none; padding: 10px 15px; border-radius: 8px; cursor: pointer; font-family: inherit; font-weight: bold;">تطبيق</button>
                        <?php endif; ?>
                    </div>
                    <div id="promoMessage" style="margin-top: 8px; font-size: 0.85rem; font-weight: bold;"></div>
                </div>

            </div>

            <!-- نموذج بيانات العميل متدرج -->
            <div style="flex: 1; min-width: 300px;">
                <form action="checkout.php" method="POST" class="login-form" id="checkoutForm" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    <input type="hidden" name="latitude" id="latitude" value="">
                    <input type="hidden" name="longitude" id="longitude" value="">
                    <input type="hidden" name="delivery_fee" id="delivery_fee" value="0">
                    
                    <!-- الخطوة 1: بيانات العميل -->
                    <div id="step-1">
                        <h4 style="margin-bottom: 1rem; color: #0B1B2B;">الخطوة 1: بيانات العميل</h4>
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" name="customer_name" id="customer_name" placeholder="الاسم الكامل" required minlength="3" title="الرجاء إدخال اسمك الكامل (3 أحرف على الأقل)" value="<?php echo isset($_POST['customer_name']) ? htmlspecialchars($_POST['customer_name']) : ''; ?>">
                        </div>
                        <div class="input-group phone-group" style="direction: ltr;">
                            <input type="tel" name="customer_phone" id="customer_phone" placeholder="رقم الهاتف" required minlength="9" maxlength="15" title="الرقم اليمني يتكون من 9 أرقام، ويجب إدخال رقم صحيح حسب الدولة" value="<?php echo isset($_POST['customer_phone']) ? htmlspecialchars($_POST['customer_phone']) : ''; ?>" style="width: 100%; padding-top: 0.9rem; padding-bottom: 0.9rem; border: 1.5px solid #E2E8F0; border-radius: 50px; font-size: 1rem; font-family: inherit; background: #F9FBFD; outline: none; text-align: left;">
                        </div>
                        <div class="input-group">
                            <i class="fas fa-map-marker-alt" style="top: 20px;"></i>
                            <textarea name="customer_address" id="customer_address" placeholder="العنوان التفصيلي للتوصيل (مثال: صنعاء، شارع تعز، جوار كذا)" required minlength="10" title="الرجاء كتابة عنوان وصفي دقيق (10 أحرف على الأقل)" style="width: 100%; padding: 0.9rem 2.8rem 0.9rem 1rem; border: 1.5px solid #E2E8F0; border-radius: 20px; font-size: 1rem; font-family: inherit; transition: 0.2s; background: #F9FBFD; min-height: 100px; resize: vertical; outline: none;"><?php echo isset($_POST['customer_address']) ? htmlspecialchars($_POST['customer_address']) : ''; ?></textarea>
                        </div>
                        <button type="button" class="login-btn" onclick="nextStep(2)" style="margin-top: 1.5rem;">التالي: طريقة الدفع</button>
                    </div>

                    <!-- الخطوة 2: طريقة الدفع -->
                    <div id="step-2" style="display: none;">
                        <h4 style="margin-bottom: 1rem; color: #0B1B2B;">الخطوة 2: طريقة الدفع</h4>
                        <div class="input-group" style="margin-top: 1rem;">
                            <i class="fas fa-money-check-alt"></i>
                            <select name="payment_method" id="payment_method" onchange="updatePaymentInstructions()" required style="width: 100%; padding: 0.9rem 2.8rem 0.9rem 1rem; border: 1.5px solid #E2E8F0; border-radius: 50px; font-size: 1rem; font-family: inherit; transition: 0.2s; background: #F9FBFD; cursor: pointer; outline: none;">
                                <option value="الدفع عند الاستلام">1. الدفع عند الاستلام</option>
                                <optgroup label="2. محافظ إلكترونية محلية">
                                    <option value="محفظة جيب">جيب</option>
                                    <option value="محفظة جوالي">جوالي</option>
                                    <option value="محفظة ون كاش">ون كاش</option>
                                    <option value="محفظة فلوسك">فلوسك</option>
                                </optgroup>
                                <optgroup label="3. عبر شبكات الصرافة">
                                    <option value="أي شبكة صرافة">عبر أي شبكة صرافة (النجم، العامري، إلخ...)</option>
                                </optgroup>
                                <optgroup label="4. تحويل بنكي محلي">
                                    <option value="بنك الكريمي">بنك الكريمي</option>
                                    <option value="بنك اليمن">بنك اليمن</option>
                                </optgroup>
                            </select>
                        </div>
                        <div id="payment-instructions" style="margin-top: 1rem; padding: 1rem; background-color: #e3f2fd; border-radius: 8px; color: #0d47a1; display: none; line-height: 1.6;">
                            <!-- سيتم إدراج تعليمات الدفع هنا -->
                        </div>
                        <div id="receipt-upload" style="margin-top: 1rem; display: none;">
                            <label style="display: block; margin-bottom: 0.5rem; color: #0B1B2B; font-weight: bold;">إرفاق صورة الإيصال أو الإيداع (المعرض أو الكاميرا):</label>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <div style="flex: 1;">
                                    <input type="file" name="payment_receipt" id="payment_receipt" accept="image/*" style="width: 100%; padding: 0.5rem; border: 1.5px solid #E2E8F0; border-radius: 8px; background: #F9FBFD;" onchange="handleFileSelect(this)">
                                </div>
                                <div>
                                    <button type="button" onclick="document.getElementById('camera_receipt').click()" style="background: #0d47a1; color: white; border: none; border-radius: 8px; padding: 0.7rem 1rem; cursor: pointer; height: 100%; font-family: inherit;">
                                        <i class="fas fa-camera"></i> التقاط
                                    </button>
                                    <input type="file" id="camera_receipt" accept="image/*" capture="environment" style="display: none;" onchange="handleFileSelect(this)">
                                </div>
                            </div>
                            <div id="file-preview-name" style="margin-top: 5px; font-size: 0.85rem; color: #4CAF50;"></div>
                        </div>
                        <div style="display: flex; gap: 10px; margin-top: 1.5rem;">
                            <button type="button" class="login-btn" onclick="prevStep(1)" style="background: #94a3b8; width: 40%;">السابق</button>
                            <button type="button" class="login-btn" onclick="nextStep(3)" style="width: 60%;">التالي: طريقة الشحن</button>
                        </div>
                    </div>

                    <!-- الخطوة 3: طريقة الشحن -->
                    <div id="step-3" style="display: none;">
                        <h4 style="margin-bottom: 1rem; color: #0B1B2B;">الخطوة 3: طريقة الشحن والتوصيل</h4>
                        <div class="input-group" style="margin-top: 1rem;">
                            <i class="fas fa-motorcycle"></i>
                            <select name="shipping_method" id="shipping_method" onchange="resetCalculation()" required style="width: 100%; padding: 0.9rem 2.8rem 0.9rem 1rem; border: 1.5px solid #E2E8F0; border-radius: 50px; font-size: 1rem; font-family: inherit; transition: 0.2s; background: #F9FBFD; cursor: pointer; outline: none;">
                                <option value="توصيل داخل صنعاء">طريقة الشحن: توصيل داخل صنعاء (بحسب النطاقات حول المتجر)</option>
                                <option value="شحن خارج صنعاء">طريقة الشحن: شحن خارج صنعاء</option>
                            </select>
                        </div>
                        
                        <div id="shippingCalculation" style="margin-top: 1.5rem; background: #F8FAFE; padding: 1.5rem; border-radius: 12px; border: 1px solid #E2E8F0;">
                            <p style="font-size: 0.95rem; color: #4A5568; margin-bottom: 1rem;">سيتم حساب رسوم التوصيل آلياً بناءً على عنوانك: <strong id="displayAddress" style="color: #0B1B2B;"></strong></p>
                            <button type="button" class="login-btn" onclick="calculateShipping()" style="width: 100%; background: #0d47a1; margin-bottom: 1rem;"><i class="fas fa-calculator"></i> حساب رسوم التوصيل</button>
                            <div id="distanceInfo" style="font-size: 1rem; font-weight: bold; text-align: center;"></div>
                        </div>

                        <div id="outsideShippingInfo" style="display: none; margin-top: 1.5rem; background: #e8f5e9; padding: 1.5rem; border-radius: 12px; border: 1px solid #c8e6c9; text-align: center;">
                            <p style="color: #2e7d32; font-weight: bold; margin-bottom: 10px;">سيتم التنسيق معك من خلال خدمة العملاء بعد إتمام الطلب لتحديد رسوم وتفاصيل الشحن.</p>
                            <a href="https://wa.me/967771771814" target="_blank" style="color: #25D366; font-weight: bold; text-decoration: none; font-size: 1.1rem;"><i class="fab fa-whatsapp"></i> تواصل عبر واتساب: 771771814</a>
                        </div>

                        <div style="display: flex; gap: 10px; margin-top: 1.5rem;">
                            <button type="button" class="login-btn" onclick="prevStep(2)" style="background: #94a3b8; width: 40%;">السابق</button>
                            <button type="submit" id="submitBtn" class="login-btn" style="width: 60%;" disabled>تأكيد وإرسال الطلب</button>
                        </div>
                    </div>

                </form>
            </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
        <script>
            // تهيئة حقل الهاتف الدولي بدون انتظار DOMContentLoaded لأنه في أسفل الصفحة
            (function() {
                const phoneInputField = document.querySelector("#customer_phone");
                if (typeof window.intlTelInput === 'function') {
                    window.phoneInputITI = window.intlTelInput(phoneInputField, {
                        preferredCountries: ["ye", "sa", "ae", "eg"],
                        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                        separateDialCode: true,
                        initialCountry: "auto",
                        geoIpLookup: function(success, failure) {
                            fetch("https://ipapi.co/json")
                                .then(function(res) { return res.json(); })
                                .then(function(data) { success(data.country_code); })
                                .catch(function() { success("ye"); });
                        }
                    });
                }
            })();
            function handleFileSelect(inputElement) {
                if (inputElement.files && inputElement.files.length > 0) {
                    var camInput = document.getElementById('camera_receipt');
                    var galInput = document.getElementById('payment_receipt');
                    
                    if (inputElement.id === 'camera_receipt') {
                        camInput.setAttribute('name', 'payment_receipt');
                        if(galInput) {
                            galInput.removeAttribute('name');
                            galInput.value = '';
                        }
                    } else {
                        if(galInput) galInput.setAttribute('name', 'payment_receipt');
                        if(camInput) {
                            camInput.removeAttribute('name');
                            camInput.value = '';
                        }
                    }
                    document.getElementById('file-preview-name').innerText = 'تم اختيار الملف: ' + inputElement.files[0].name;
                }
            }

            function nextStep(step) {
                if(step === 2) {
                    var nameInput = document.getElementById('customer_name');
                    var phoneInput = document.getElementById('customer_phone');
                    var addressInput = document.getElementById('customer_address');
                    
                    if (!nameInput.checkValidity()) {
                        nameInput.reportValidity();
                        return;
                    }
                    if (!phoneInput.checkValidity()) {
                        phoneInput.reportValidity();
                        return;
                    }
                    if (window.phoneInputITI) {
                        if (!window.phoneInputITI.isValidNumber()) {
                            alert("رقم الهاتف غير صحيح أو لا يطابق الدولة المختارة. يرجى التأكد من اختيار رمز الدولة وإدخال الرقم بشكل صحيح.");
                            return;
                        }
                        phoneInput.value = window.phoneInputITI.getNumber();
                    } else {
                        // Fallback validation if plugin failed to load
                        var phoneVal = phoneInput.value.trim();
                        if(!/^(\+|00)?[0-9]{9,15}$/.test(phoneVal)) {
                            alert("الرجاء إدخال رقم هاتف صحيح (9 إلى 15 رقماً).");
                            return;
                        }
                    }
                    
                    if (!addressInput.checkValidity()) {
                        addressInput.reportValidity();
                        return;
                    }
                    var addressVal = addressInput.value.trim();
                    var words = addressVal.split(/\s+/).filter(function(w){ return w.length > 0; });
                    if (words.length < 4) {
                        alert("وصف العنوان غير مكتمل أو غير دقيق. يرجى كتابة عنوان تفصيلي يتكون من 4 كلمات على الأقل (مثال: مدينة صنعاء شارع حدة جوار...).");
                        return;
                    }
                    
                    // منع إدخال الحروف المكررة بشكل غير منطقي (مثل: اااا أو gggg)
                    if (/(.)\1{4,}/.test(addressVal)) {
                        alert("عذراً، وصف العنوان غير صحيح. يرجى كتابة عنوان حقيقي ومفهوم.");
                        return;
                    }
                    
                    // فحص إذا كانت الكلمات قصيرة جداً (أقل من حرفين) لمعظم الكلمات
                    var shortWords = words.filter(function(w){ return w.length < 2; }).length;
                    if (shortWords > (words.length / 2)) {
                        alert("وصف العنوان غير صحيح أو غير مفهوم. يرجى كتابة عنوان دقيق للتوصيل.");
                        return;
                    }
                } else if(step === 3) {
                    var method = document.getElementById('payment_method').value;
                    if(method !== 'الدفع عند الاستلام') {
                        var receipt = document.getElementById('payment_receipt') ? document.getElementById('payment_receipt').value : '';
                        var camReceipt = document.getElementById('camera_receipt') ? document.getElementById('camera_receipt').value : '';
                        if(!receipt && !camReceipt) {
                            alert('الرجاء إرفاق صورة الإيصال أو إثبات الدفع للتحقق من الحوالة قبل الانتقال للخطوة التالية.');
                            return;
                        }
                    }
                    var address = document.getElementById('customer_address').value.trim();
                    document.getElementById('displayAddress').innerText = address;
                    // Reset calculation status when entering step 3
                    resetCalculation();
                }
                
                document.getElementById('step-1').style.display = 'none';
                document.getElementById('step-2').style.display = 'none';
                document.getElementById('step-3').style.display = 'none';
                
                document.getElementById('step-' + step).style.display = 'block';
            }

            function prevStep(step) {
                document.getElementById('step-1').style.display = 'none';
                document.getElementById('step-2').style.display = 'none';
                document.getElementById('step-3').style.display = 'none';
                
                document.getElementById('step-' + step).style.display = 'block';
            }

            var storeLat = <?php echo $store_lat; ?>;
            var storeLon = <?php echo $store_lon; ?>;
            
            const shippingCompanies = {
                'توصيل داخل صنعاء': { eta: 'حسب النطاق' }
            };

            function resetCalculation() {
                var method = document.getElementById('shipping_method').value;
                if (method === 'شحن خارج صنعاء') {
                    document.getElementById('shippingCalculation').style.display = 'none';
                    if (document.getElementById('outsideShippingInfo')) {
                        document.getElementById('outsideShippingInfo').style.display = 'block';
                    }
                    document.getElementById('distanceInfo').innerText = '';
                    document.getElementById('delivery_fee').value = 0;
                    document.getElementById('submitBtn').disabled = false;
                    document.getElementById('deliveryFeeRow').style.display = 'none';
                    document.getElementById('latitude').value = "0";
                    document.getElementById('longitude').value = "0";
                } else {
                    document.getElementById('shippingCalculation').style.display = 'block';
                    if (document.getElementById('outsideShippingInfo')) {
                        document.getElementById('outsideShippingInfo').style.display = 'none';
                    }
                    document.getElementById('distanceInfo').innerText = '';
                    document.getElementById('delivery_fee').value = 0;
                    document.getElementById('submitBtn').disabled = true;
                    document.getElementById('deliveryFeeRow').style.display = 'none';
                }
                
                // Reset Total
                var baseTotal = parseFloat(document.getElementById('finalTotalDisplay').getAttribute('data-base-total'));
                var exchangeRate = <?php echo get_currency_rate(get_current_currency()); ?>;
                var currentCurrency = '<?php echo get_current_currency(); ?>';
                var convertedTotal = baseTotal * exchangeRate;
                
                if (currentCurrency === 'YER') {
                    document.getElementById('finalTotalDisplay').innerText = Math.round(convertedTotal).toLocaleString('en-US') + ' <?php echo get_currency_symbol(get_current_currency()); ?>';
                } else {
                    document.getElementById('finalTotalDisplay').innerText = convertedTotal.toFixed(2) + ' <?php echo get_currency_symbol(get_current_currency()); ?>';
                }
            }

            function formatPriceJS(amount) {
                var currentCurrency = '<?php echo get_current_currency(); ?>';
                if (currentCurrency === 'YER') {
                    return Math.round(amount).toLocaleString('en-US') + ' <?php echo get_currency_symbol(get_current_currency()); ?>';
                } else {
                    return amount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' <?php echo get_currency_symbol(get_current_currency()); ?>';
                }
            }

            function calculateShipping() {
                var method = document.getElementById('shipping_method').value;
                if(!shippingCompanies[method]) {
                    document.getElementById('deliveryFeeRow').style.display = 'none';
                    return;
                }

                var address = document.getElementById('customer_address').value.trim();
                var query = address + ', صنعاء, اليمن';

                document.getElementById('distanceInfo').innerText = 'جاري البحث عن الموقع وحساب المسافة...';
                document.getElementById('distanceInfo').style.color = '#FF9800';
                document.getElementById('submitBtn').disabled = true;

                // 1. Geocoding using backend proxy to bypass CORS
                fetch('cart_action.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'geocode', query: query })
                })
                .then(res => res.json())
                .then(resData => {
                    if (resData.status === 'success' && resData.data && resData.data.length > 0) {
                        var lat = parseFloat(resData.data[0].lat);
                        var lon = parseFloat(resData.data[0].lon);
                        
                        document.getElementById('latitude').value = lat;
                        document.getElementById('longitude').value = lon;
                        
                        // 2. Distance calculation using OSRM
                        var osrmUrl = `https://router.project-osrm.org/route/v1/driving/${storeLon},${storeLat};${lon},${lat}?overview=false`;
                        return fetch(osrmUrl).then(res => res.json()).then(osrmData => { return { osrm: osrmData, resData: resData }; });
                    } else {
                        throw new Error('لم نتمكن من تحديد الموقع بدقة بناءً على العنوان المدخل. يرجى تعديل العنوان ليكون أكثر وضوحاً.');
                    }
                })
                .then(data => {
                    var osrmData = data.osrm;
                    var resData = data.resData;
                    if (osrmData && osrmData.code === 'Ok' && osrmData.routes && osrmData.routes.length > 0) {
                        var distanceKm = osrmData.routes[0].distance / 1000;
                        
                        var feeYer = 700; // النطاق الأول: قريب (0 - 4 كم)
                        if (distanceKm > 8) {
                            feeYer = 1500; // النطاق الثالث: بعيد (أكثر من 8 كم)
                        } else if (distanceKm > 4) {
                            feeYer = 1000; // النطاق الثاني: متوسط (من 4 إلى 8 كم)
                        }
                        
                        var yerRate = <?php echo get_currency_rate('YER'); ?>;
                        var feeUsd = feeYer / yerRate;
                        
                        document.getElementById('delivery_fee').value = feeUsd; // Send to backend in USD
                        
                        // Update total display
                        var baseTotal = parseFloat(document.getElementById('finalTotalDisplay').getAttribute('data-base-total'));
                        var currentCurrency = '<?php echo get_current_currency(); ?>';
                        var exchangeRate = <?php echo get_currency_rate(get_current_currency()); ?>;
                        
                        var convertedFee = feeUsd * exchangeRate;
                        var convertedTotal = (baseTotal + feeUsd) * exchangeRate;
                        
                        var warningMsg = resData.warning ? `<br><span style="color:#FF9800; font-size:0.85rem;">(ملاحظة: ${resData.warning})</span>` : '';
                        document.getElementById('distanceInfo').innerHTML = `المسافة التقريبية: <span style="color:#0B1B2B">${distanceKm.toFixed(2)} كم</span><br>رسوم التوصيل: <span style="color:#0B1B2B">${formatPriceJS(convertedFee)}</span>${warningMsg}`;
                        document.getElementById('distanceInfo').style.color = '#4CAF50';
                        
                        document.getElementById('deliveryFeeRow').style.display = 'flex';
                        if (currentCurrency === 'YER') {
                            document.getElementById('deliveryFeeDisplay').innerText = Math.round(convertedFee).toLocaleString('en-US') + ' <?php echo get_currency_symbol(get_current_currency()); ?>';
                            document.getElementById('finalTotalDisplay').innerText = Math.round(convertedTotal).toLocaleString('en-US') + ' <?php echo get_currency_symbol(get_current_currency()); ?>';
                        } else {
                            document.getElementById('deliveryFeeDisplay').innerText = convertedFee.toFixed(2) + ' <?php echo get_currency_symbol(get_current_currency()); ?>';
                            document.getElementById('finalTotalDisplay').innerText = convertedTotal.toFixed(2) + ' <?php echo get_currency_symbol(get_current_currency()); ?>';
                        }
                        
                        document.getElementById('submitBtn').disabled = false;
                    } else {
                        throw new Error('تعذر حساب المسافة لهذا العنوان.');
                    }
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('distanceInfo').innerText = err.message || 'حدث خطأ غير متوقع أثناء الحساب.';
                    document.getElementById('distanceInfo').style.color = '#F56565';
                    document.getElementById('delivery_fee').value = 0;
                    document.getElementById('submitBtn').disabled = true;
                });
            }

            document.getElementById('checkoutForm').addEventListener('submit', function(e) {
                var method = document.getElementById('shipping_method').value;
                if(shippingCompanies[method]) {
                    var lat = document.getElementById('latitude').value;
                    if(!lat || document.getElementById('submitBtn').disabled) {
                        e.preventDefault();
                        alert('يرجى التأكد من الضغط على زر حساب رسوم التوصيل أولاً لتحديد الموقع والتكلفة.');
                        return;
                    }
                }
                
                var payMethod = document.getElementById('payment_method').value;
                if(payMethod !== 'الدفع عند الاستلام') {
                    var receipt = document.getElementById('payment_receipt') ? document.getElementById('payment_receipt').value : '';
                    var camReceipt = document.getElementById('camera_receipt') ? document.getElementById('camera_receipt').value : '';
                    if(!receipt && !camReceipt) {
                        e.preventDefault();
                        alert('الرجاء إرفاق صورة الإشعار أو إثبات الدفع لتأكيد الطلب.');
                        prevStep(2); // العودة لخطوة الدفع
                        return;
                    }
                }
            });

            function updatePaymentInstructions() {
                var method = document.getElementById('payment_method').value;
                var instDiv = document.getElementById('payment-instructions');
                var text = '';
                
                if(method === 'الدفع عند الاستلام') {
                    instDiv.style.display = 'none';
                    document.getElementById('receipt-upload').style.display = 'none';
                    document.getElementById('payment_receipt').removeAttribute('required');
                    if(document.getElementById('camera_receipt')) document.getElementById('camera_receipt').removeAttribute('required');
                    return;
                }
                
                instDiv.style.display = 'block';
                document.getElementById('receipt-upload').style.display = 'block';
                // Remove native HTML required to allow JS validation to handle it nicely without conflicting on two inputs
                document.getElementById('payment_receipt').removeAttribute('required');
                if(document.getElementById('camera_receipt')) document.getElementById('camera_receipt').removeAttribute('required');
                
                if(['محفظة جيب', 'محفظة جوالي', 'محفظة ون كاش', 'محفظة فلوسك'].includes(method)) {
                    text = 'يرجى تحويل المبلغ على رقم نقطة المتجر: <strong style="font-size: 1.2rem;">560570</strong><br>ثم قم بإرفاق صورة الإشعار من الزر المخصص بالأسفل.';
                } else if(method === 'أي شبكة صرافة') {
                    text = 'يرجى التوجه إلى أقرب محل صرافة والتحويل عبر أي شبكة محلية (مثل النجم، العامري، إلخ...)<br>باسم: <strong>عماد عادل يحي القرماني</strong><br>على الأرقام التالية:<br><strong style="font-size: 1.2rem;">771771815</strong><br><strong style="font-size: 1.2rem;">771771813</strong><br>ثم قم بإرفاق صورة سند الحوالة من الزر المخصص بالأسفل.';
                } else if(['بنك الكريمي', 'بنك اليمن'].includes(method)) {
                    text = 'يرجى تحويل المبلغ على أرقام الحسابات التالية:<br><strong>3018901659</strong> (دولار)<br><strong>3025607607</strong> (سعودي)<br><strong>3011379418</strong> (ريال يمني)<br>ثم قم بإرفاق صورة الإيداع أو السند من الزر المخصص بالأسفل.';
                }
                
                instDiv.innerHTML = text;
            }
            
            // تهيئة التعليمات عند تحميل الصفحة
            document.addEventListener('DOMContentLoaded', function() {
                updatePaymentInstructions();
                
                // Coupon Logic
                const applyBtn = document.getElementById('applyPromoBtn');
                const removeBtn = document.getElementById('removePromoBtn');
                const promoInput = document.getElementById('promoCodeInput');
                const promoMsg = document.getElementById('promoMessage');

                if (applyBtn) {
                    applyBtn.addEventListener('click', function() {
                        const code = promoInput.value.trim();
                        if (!code) return;
                        
                        applyBtn.disabled = true;
                        fetch('cart_action.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ action: 'apply_coupon', code: code })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'success') {
                                promoMsg.style.color = '#48BB78';
                                promoMsg.innerText = data.message;
                                setTimeout(() => window.location.reload(), 1000);
                            } else {
                                promoMsg.style.color = '#F56565';
                                promoMsg.innerText = data.message;
                                applyBtn.disabled = false;
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            applyBtn.disabled = false;
                        });
                    });
                }

                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        removeBtn.disabled = true;
                        fetch('cart_action.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ action: 'remove_coupon' })
                        })
                        .then(res => res.json())
                        .then(data => {
                            window.location.reload();
                        });
                    });
                }
            });
            </script>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
