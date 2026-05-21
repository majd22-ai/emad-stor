<?php
session_start();

if (isset($_GET['currency'])) {
    $allowed_currencies = ['USD', 'SAR', 'YER'];
    $selected_currency = strtoupper($_GET['currency']);
    
    if (in_array($selected_currency, $allowed_currencies)) {
        $_SESSION['currency'] = $selected_currency;
    }
}

// Redirect back to the previous page
if (isset($_SERVER['HTTP_REFERER'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    header('Location: index.php');
}
exit;
?>
