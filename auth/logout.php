<?php
session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);
    session_start();
session_unset();
session_destroy();
header('Location: ../index.php');
exit;
?>
