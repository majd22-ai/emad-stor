<?php
$files = ['aboutus.php', 'shipping-payment.php'];
foreach ($files as $file) {
    $path = 'c:/xampp/htdocs/emad-stor/pages/pages_footer/' . $file;
    $content = file_get_contents($path);
    
    // Remove everything from <!DOCTYPE html> to </header>
    // And remove side menu
    $content = preg_replace('/<!DOCTYPE html>.*<!-- ==================== (محتوى من نحن|المحتوى الرئيسي) ==================== -->/is', '<?php $base_url = \'/emad-stor/\'; include \'../../includes/header.php\'; ?>' . "\n", $content);
    
    // Remove footer and everything below
    $content = preg_replace('/<!-- ========== FOOTER ========== -->.*/is', '<?php include \'../../includes/footer.php\'; ?>' . "\n", $content);
    
    file_put_contents($path, $content);
}
echo 'Done';
?>
