<?php
$files = glob('c:/xampp/htdocs/emad-stor/pages/pages_footer/*.php');
foreach ($files as $file) {
    if (strpos($file, 'fixed') !== false) continue;
    $content = file_get_contents($file);
    $ansi = utf8_decode($content);
    $fixed = iconv('Windows-1256', 'UTF-8', $ansi);
    if ($fixed !== false) {
        file_put_contents($file, $fixed);
    }
}
echo 'Done';
?>
