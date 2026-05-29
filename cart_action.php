<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
    session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input && isset($_POST['action'])) {
    $input = $_POST;
}

if (!isset($input['action'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    exit;
}

$action = $input['action'];

if ($action === 'add') {
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
        echo json_encode(['status' => 'error', 'message' => 'يجب تسجيل الدخول أولاً لإضافة منتجات إلى السلة.', 'redirect' => 'login']);
        exit;
    }

    $id = isset($input['id']) ? (int)$input['id'] : 0;
    $name = isset($input['name']) ? $input['name'] : '';
    $price = isset($input['price']) ? (float)$input['price'] : 0.0;
    $img = isset($input['img']) ? $input['img'] : '';
    $size = isset($input['size']) ? $input['size'] : '';

    if ($id <= 0 || empty($name)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing product details']);
        exit;
    }

    $cart_id = $id . '_' . $size; // Unique identifier for product + size combo

    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['cart_id'] === $cart_id) {
            $item['quantity'] += 1;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = [
            'cart_id' => $cart_id,
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'img' => $img,
            'size' => $size,
            'quantity' => 1
        ];
    }

    echo json_encode(['status' => 'success', 'message' => 'Product added to cart', 'cart' => array_values($_SESSION['cart'])]);
    exit;
}

if ($action === 'remove') {
    $cart_id = isset($input['cart_id']) ? $input['cart_id'] : '';
    
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($cart_id) {
        return $item['cart_id'] !== $cart_id;
    });
    
    $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex array
    
    echo json_encode(['status' => 'success', 'message' => 'Product removed from cart', 'cart' => $_SESSION['cart']]);
    exit;
}

if ($action === 'get') {
    echo json_encode(['status' => 'success', 'cart' => array_values($_SESSION['cart'])]);
    exit;
}

if ($action === 'update_quantity') {
    $cart_id = isset($input['cart_id']) ? $input['cart_id'] : '';
    $quantity = isset($input['quantity']) ? (int)$input['quantity'] : 1;

    if ($quantity > 0) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['cart_id'] === $cart_id) {
                $item['quantity'] = $quantity;
                break;
            }
        }
    } else {
        // إذا كانت الكمية 0 أو أقل، نحذف المنتج
        $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($cart_id) {
            return $item['cart_id'] !== $cart_id;
        });
    }
    
    $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex
    echo json_encode(['status' => 'success', 'message' => 'Quantity updated', 'cart' => $_SESSION['cart']]);
    exit;
}

if ($action === 'clear') {
    $_SESSION['cart'] = [];
    unset($_SESSION['coupon']);
    echo json_encode(['status' => 'success', 'message' => 'Cart cleared', 'cart' => []]);
    exit;
}

if ($action === 'apply_coupon') {
    require_once 'includes/db_connect.php';
    $code = isset($input['code']) ? strtoupper(trim($input['code'])) : '';
    
    if (empty($code)) {
        echo json_encode(['status' => 'error', 'message' => 'الرجاء إدخال كود الخصم']);
        exit;
    }
    
    $stmt = $pdo->prepare('SELECT * FROM coupons WHERE code = ?');
    $stmt->execute([$code]);
    $coupon = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($coupon) {
        if ($coupon['used_count'] < $coupon['usage_limit']) {
            $_SESSION['coupon'] = [
                'code' => $coupon['code'],
                'discount' => $coupon['discount_percent']
            ];
            echo json_encode(['status' => 'success', 'discount' => $coupon['discount_percent'], 'message' => 'تم تطبيق الكود بنجاح!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'عذراً، هذا الكود وصل للحد الأقصى من الاستخدام']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'كود الخصم غير صحيح أو منتهي الصلاحية']);
    }
    exit;
}

if ($action === 'remove_coupon') {
    unset($_SESSION['coupon']);
    echo json_encode(['status' => 'success', 'message' => 'تم إزالة الكود']);
    exit;
}

if ($action === 'geocode') {
    $query = isset($input['query']) ? trim($input['query']) : '';
    if (empty($query)) {
        echo json_encode(['status' => 'error', 'message' => 'Empty query']);
        exit;
    }
    
    // قاموس الأماكن المعروفة في صنعاء بدقة عالية (خط العرض، خط الطول)
    $known_locations = [
        'ابو عادل' => ['lat' => 15.335, 'lon' => 44.230], // جولة ابو عادل
        'خولان' => ['lat' => 15.332, 'lon' => 44.227], // شارع خولان
        'تعز' => ['lat' => 15.318, 'lon' => 44.208], // شارع تعز
        'حده' => ['lat' => 15.320, 'lon' => 44.190], // حدة
        'الاصبحي' => ['lat' => 15.290, 'lon' => 44.195], // الأصبحي
        'دار سلم' => ['lat' => 15.280, 'lon' => 44.210], // دار سلم
        'الستين' => ['lat' => 15.345, 'lon' => 44.175], // شارع الستين
        'الزبيري' => ['lat' => 15.350, 'lon' => 44.195], // الزبيري
        'الدائري' => ['lat' => 15.350, 'lon' => 44.195], // الدائري
        'شملان' => ['lat' => 15.390, 'lon' => 44.160], // شملان
        'مذبح' => ['lat' => 15.380, 'lon' => 44.175], // مذبح
        'السنينه' => ['lat' => 15.365, 'lon' => 44.180], // السنينة
        'السنينة' => ['lat' => 15.365, 'lon' => 44.180],
        'الجراف' => ['lat' => 15.385, 'lon' => 44.215], // الجراف
        'الحصبه' => ['lat' => 15.370, 'lon' => 44.205], // الحصبة
        'الحصبة' => ['lat' => 15.370, 'lon' => 44.205],
        'نقم' => ['lat' => 15.355, 'lon' => 44.230], // نقم
        'شعوب' => ['lat' => 15.360, 'lon' => 44.215], // شعوب
        'الصافية' => ['lat' => 15.340, 'lon' => 44.210], // الصافية
        'الصافيه' => ['lat' => 15.340, 'lon' => 44.210],
        'المطار' => ['lat' => 15.460, 'lon' => 44.215], // المطار الجديد
        'سعوان' => ['lat' => 15.365, 'lon' => 44.240], // سعوان
        'شيراتون' => ['lat' => 15.360, 'lon' => 44.235], // شيراتون
        'نادي الضباط' => ['lat' => 15.352, 'lon' => 44.195],
        'مذبح' => ['lat' => 15.385, 'lon' => 44.175]
    ];

    $data = null;
    foreach ($known_locations as $key => $coords) {
        if (mb_strpos($query, $key) !== false) {
            $data = [['lat' => $coords['lat'], 'lon' => $coords['lon']]];
            break;
        }
    }

    if (!$data) {
        // إزالة الكلمات الزائدة لتنظيف البحث (بدون حذف كلمة شارع أو جولة لأنها مهمة للبحث الدقيق)
        $replacements = [
            'صنعاء' => '', 'اليمن' => '', '،' => '', ',' => '', 'جوار' => '', 'بجوار' => '', 'بجانب' => '', 'قريب' => '',
            'خمسه واربعين' => '45', 'خمسة واربعين' => '45', 'خمسه و اربعين' => '45', 'خمسة و اربعين' => '45',
            'واحد' => '1', 'اثنين' => '2', 'ثلاثه' => '3', 'ثلاثة' => '3', 'اربع' => '4', 'اربعه' => '4', 'أربعه' => '4', 'أربعة' => '4', 'خمسه' => '5', 'خمسة' => '5', 'ست' => '6', 'سته' => '6', 'ستة' => '6', 'سبع' => '7', 'سبعه' => '7', 'سبعة' => '7', 'ثمان' => '8', 'ثمانيه' => '8', 'ثمانية' => '8', 'تسع' => '9', 'تسعه' => '9', 'تسعة' => '9', 'عشر' => '10', 'عشره' => '10', 'عشرة' => '10',
            'عشرين' => '20', 'ثلاثين' => '30', 'اربعين' => '40', 'أربعين' => '40', 'خمسين' => '50', 'ستين' => '60', 'سبعين' => '70', 'ثمانين' => '80', 'تسعين' => '90'
        ];
        $query = strtr($query, $replacements);
        $query = trim(preg_replace('/\s+/', ' ', $query));
    
    $words = explode(' ', $query);
    $data = null;
    
    // Fallback mechanism: Try full string, if empty, remove last word and retry.
    while (count($words) > 0) {
        $attemptQuery = implode(' ', $words) . ' صنعاء';
        // Using Photon Komoot API with location bias towards Sanaa
        $url = "https://photon.komoot.io/api/?q=" . urlencode($attemptQuery) . "&lat=15.3519835&lon=44.2158552&limit=1";
        
        $options = [
            "http" => [
                "header" => "User-Agent: EmadStor/1.0 (info@emad-stor.com)\r\n" .
                            "Accept-Language: ar\r\n"
            ],
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        ];
        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);
        
        if ($response) {
            $parsed = json_decode($response, true);
            if (isset($parsed['features']) && count($parsed['features']) > 0) {
                // Found a match!
                $lon = $parsed['features'][0]['geometry']['coordinates'][0];
                $lat = $parsed['features'][0]['geometry']['coordinates'][1];
                $data = [['lat' => $lat, 'lon' => $lon]];
                break;
            }
        }
        
        // Remove the last word and try again
        array_pop($words);
    }
    } // Close the if (!$data) block
    
    if ($data) {
        echo json_encode(['status' => 'success', 'data' => $data]);
    } else {
        // Ultimate Fail-safe: Return default Sanaa coordinates (Store location) so user is never blocked.
        // It will calculate distance as 0km and apply the base fee (e.g. 700 riyals).
        $data = [['lat' => 15.3519835, 'lon' => 44.2158552]];
        echo json_encode(['status' => 'success', 'data' => $data, 'warning' => 'استخدمنا الموقع التقريبي للمدينة لتعذر تحديد العنوان بدقة.']);
    }
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Unknown action']);
exit;
