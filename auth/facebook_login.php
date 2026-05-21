<?php
require_once '../includes/oauth_config.php';

$authUrl = "https://www.facebook.com/v16.0/dialog/oauth?" . http_build_query([
    'client_id' => FACEBOOK_APP_ID,
    'redirect_uri' => FACEBOOK_REDIRECT_URL,
    'response_type' => 'code',
    'scope' => 'email,public_profile'
]);

header("Location: $authUrl");
exit;
?>
