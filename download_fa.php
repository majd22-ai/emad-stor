<?php
$url = "https://use.fontawesome.com/releases/v5.15.4/fontawesome-free-5.15.4-web.zip";
$zipFile = __DIR__ . '/assets/fa.zip';
$extractTo = __DIR__ . '/assets/fontawesome';

echo "Downloading...\n";
$content = file_get_contents($url);
file_put_contents($zipFile, $content);

echo "Extracting...\n";
$zip = new ZipArchive;
if ($zip->open($zipFile) === TRUE) {
    $zip->extractTo($extractTo);
    $zip->close();
    echo "Extracted successfully.\n";
    unlink($zipFile);
} else {
    echo "Failed to extract.\n";
}
?>
