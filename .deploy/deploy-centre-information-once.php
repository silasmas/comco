<?php

/**
 * Déploie le contenu et l'image du Centre d'Information, puis met à jour la BDD.
 * Usage: php deploy-centre-information-once.php /chemin/vers/projet
 */

declare(strict_types=1);

$root = $argv[1] ?? getcwd();
if (! is_file($root.'/artisan')) {
  fwrite(STDERR, "Racine Laravel invalide: {$root}\n");
  exit(1);
}

$base = 'https://raw.githubusercontent.com/silasmas/comco/main/';
$files = [
  'resources/views/public/pages/templates/presentation-hub.blade.php',
  'config/page-templates.php',
  'config/pages-content.php',
  '.deploy/fix-centre-information-once.php',
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

passthru('php '.escapeshellarg($root.'/.deploy/fix-centre-information-once.php').' '.escapeshellarg($root), $code);
if ($code !== 0) {
  fwrite(STDERR, "FIX failed with code {$code}\n");
  exit(1);
}

passthru('php '.escapeshellarg($root.'/artisan').' view:clear', $viewCode);
passthru('php '.escapeshellarg($root.'/artisan').' config:clear', $configCode);
passthru('php '.escapeshellarg($root.'/artisan').' cache:clear', $cacheCode);

echo "CENTREDEPLOYOK view={$viewCode} config={$configCode} cache={$cacheCode}\n";
