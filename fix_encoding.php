<?php
$files = glob('c:/xampp/htdocs/emad-stor/pages/pages_footer/*.php');
foreach ($files as $file) {
    $content = file_get_contents($file);
    // The content is currently UTF-8 encoded, but the characters are mojibake.
    // This happens when Windows-1252 or Windows-1256 bytes are interpreted as ANSI and then saved as UTF-8.
    // Let's decode it from UTF-8 to bytes (ISO-8859-1), then treat those bytes as UTF-8.
    $bytes = utf8_decode($content); // Converts UTF-8 to ISO-8859-1 bytes
    file_put_contents($file, $bytes);
}
echo "Fixed";
?>
