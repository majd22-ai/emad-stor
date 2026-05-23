<?php
function is_logged_in() {
    return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
}

function get_user_name() {
    return $_SESSION['user_name'] ?? '';
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function check_admin() {
    if (!is_logged_in() || !is_admin()) {
        $_SESSION['error'] = 'عذراً، لا تمتلك صلاحية للوصول إلى هذه الصفحة.';
        header('Location: ../pages/login.php');
        exit;
    }
}

// ================= CURRENCY FUNCTIONS =================
global $currencies;
$currencies = [
    'USD' => ['rate' => 1, 'symbol' => '$'],
    'SAR' => ['rate' => (533 / 140), 'symbol' => ' ر.س'],
    'YER' => ['rate' => 533, 'symbol' => ' ر.ي']
];

function get_current_currency() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return $_SESSION['currency'] ?? 'USD';
}

function get_currency_rate($currency_code = null) {
    global $currencies;
    $code = $currency_code ?? get_current_currency();
    return isset($currencies[$code]) ? $currencies[$code]['rate'] : 1;
}

function get_currency_symbol($currency_code = null) {
    global $currencies;
    $code = $currency_code ?? get_current_currency();
    return isset($currencies[$code]) ? $currencies[$code]['symbol'] : '$';
}

function format_price($base_price_usd, $currency_code = null) {
    $code = $currency_code ?? get_current_currency();
    $rate = get_currency_rate($code);
    $symbol = get_currency_symbol($code);
    
    $converted_price = $base_price_usd * $rate;
    
    // For YER, we usually don't need decimal places as the value is high
    if ($code === 'YER') {
        return number_format($converted_price, 0) . $symbol;
    }
    
    return number_format($converted_price, 2) . $symbol;
}

// ================= SECURITY FUNCTIONS =================
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}
?>
