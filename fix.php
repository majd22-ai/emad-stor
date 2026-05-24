<?php
$files = [
    'admin/coupons.php',
    'admin/index.php',
    'admin/orders.php',
    'admin/users.php',
    'fix_pages.php',
    'pages/forgot_password.php',
    'pages/login.php',
    'pages/pages_footer/aboutus.php',
    'pages/pages_footer/shipping-payment.php',
    'pages/register.php',
    'pages/reset_password.php',
    'pages/verify_code.php'
];

$bad = "\$base_url = '/emad-stor/';";
$good = "\$base_url = (strpos(\$_SERVER['HTTP_HOST'], 'localhost') !== false || strpos(\$_SERVER['HTTP_HOST'], '127.0.0.1') !== false) ? '/emad-stor/' : '/';";

foreach($files as $f) {
    $c = file_get_contents($f);
    if(strpos($c, $bad) !== false) {
        $c = str_replace($bad, $good, $c);
        file_put_contents($f, $c);
        echo "Fixed $f\n";
    }
}
?>
