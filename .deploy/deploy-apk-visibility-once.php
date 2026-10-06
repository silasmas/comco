<?php

/**
 * Déploie la visibilité publique APK (menu + index).
 * Usage: php deploy-apk-visibility-once.php /chemin/vers/projet
 */

declare(strict_types=1);

$root = $argv[1] ?? getcwd();
if (! is_file($root.'/artisan')) {
  fwrite(STDERR, "Racine Laravel invalide: {$root}\n");
  exit(1);
}

$base = 'https://raw.githubusercontent.com/silasmas/comco/main/';
$files = [
  'app/Http/Controllers/Public/MobileAppController.php',
  'routes/web.php',
  'resources/views/public/mobile-apps/index.blade.php',
  'resources/views/components/elixir/navbar.blade.php',
  'resources/views/components/elixir/footer.blade.php',
];

$context = stream_context_create([
  'http' => [
    'follow_location' => 1,
    'timeout' => 120,
    'header' => "User-Agent: COMCO-Deploy\r\n",
  ],
]);

foreach ($files as $file) {
  $url = $base.$file;
  $destination = $root.'/'.$file;
  $data = @file_get_contents($url, false, $context);
  if ($data === false || strlen($data) < 20) {
    fwrite(STDERR, "FAIL {$file}\n");
    exit(1);
  }
  $dir = dirname($destination);
  if (! is_dir($dir)) {
    mkdir($dir, 0755, true);
  }
  file_put_contents($destination, $data);
  echo 'OK '.$file.' ('.strlen($data).")\n";
}

echo "APKVISOK\n";
