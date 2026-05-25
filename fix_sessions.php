<?php
$directory = new RecursiveDirectoryIterator(__DIR__);
$iterator = new RecursiveIteratorIterator($directory);
$regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

$search = "session_start();";
$replace = "session_set_cookie_params(['lifetime' => 60 * 60 * 24 * 30, 'path' => '/', 'samesite' => 'Lax']);\n    session_start();";

$count = 0;
foreach ($regex as $file) {
    $filePath = $file[0];
    
    // Skip this script itself
    if (basename($filePath) === 'fix_sessions.php') continue;
    
    $content = file_get_contents($filePath);
    
    // Check if it already has session_set_cookie_params
    if (strpos($content, 'session_set_cookie_params') !== false) {
        continue;
    }

    if (strpos($content, 'session_start();') !== false) {
        $newContent = str_replace('session_start();', $replace, $content);
        file_put_contents($filePath, $newContent);
        $count++;
    }
}
echo "Updated $count files.\n";
?>
