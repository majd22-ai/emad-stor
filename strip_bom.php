<?php
function remove_bom($file) {
    $content = file_get_contents($file);
    $bom = pack('H*','EFBBBF');
    if (preg_match("/^$bom/", $content)) {
        $content = preg_replace("/^$bom/", '', $content);
        file_put_contents($file, $content);
        echo "Removed BOM from $file\n";
    }
}

$files = glob('includes/*.php');
$files[] = 'index.php';
$files[] = 'header.php';
$files[] = 'functions.php';
$files[] = 'db_connect.php';
$files[] = 'set_currency.php';

foreach ($files as $f) {
    if (file_exists($f)) {
        remove_bom($f);
    }
}
echo "BOM check complete.\n";
?>
