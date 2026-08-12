<?php
set_time_limit(0);
ini_set('memory_limit', '512M');
header('Content-Type: text/plain');
$r = __DIR__;
$zipPath = $r . '/vendor.zip';
$target = $r . '/vendor';
if (!is_file($zipPath)) { http_response_code(500); echo "NO_ZIP\n"; exit(1); }
if (is_dir($target)) {
  $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($target, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
  foreach ($it as $f) { $f->isDir() ? @rmdir($f->getPathname()) : @unlink($f->getPathname()); }
  @rmdir($target);
}
mkdir($target, 0755, true);
$z = new ZipArchive();
if ($z->open($zipPath) !== true) { echo "OPEN_FAIL\n"; exit(1); }
if (!$z->extractTo($target)) { $z->close(); echo "EXTRACT_FAIL\n"; exit(1); }
$z->close();
@unlink($zipPath);
$ok = is_file($target . '/symfony/var-dumper/Resources/functions/dump.php') && is_file($target . '/autoload.php');
echo $ok ? "OK\n" : "FAIL\n";