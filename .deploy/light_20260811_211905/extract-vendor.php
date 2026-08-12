<?php
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');
$root = __DIR__;
$zipPath = $root . DIRECTORY_SEPARATOR . 'vendor.zip';
$target = $root . DIRECTORY_SEPARATOR . 'vendor';
if (!is_file($zipPath)) {
    http_response_code(500);
    echo "vendor.zip missing\n";
    exit(1);
}
if (!is_dir($target)) {
    mkdir($target, 0755, true);
}
$zip = new ZipArchive();
if ($zip->open($zipPath) !== true) {
    http_response_code(500);
    echo "cannot open vendor.zip\n";
    exit(1);
}
if (!$zip->extractTo($target)) {
    $zip->close();
    http_response_code(500);
    echo "extract failed\n";
    exit(1);
}
$zip->close();
@unlink($zipPath);
@unlink(__FILE__);
echo "VENDOR_OK\n";