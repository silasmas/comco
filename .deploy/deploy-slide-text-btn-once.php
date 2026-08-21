<?php

/**
 * Déploie le pack tailles/couleurs de slides depuis GitHub.
 * Usage: php deploy-slide-text-btn-once.php /chemin/vers/projet
 */

declare(strict_types=1);

$root = $argv[1] ?? getcwd();
if (! is_file($root.'/artisan')) {
  fwrite(STDERR, "Racine Laravel invalide: {$root}\n");
  exit(1);
}

$base = 'https://raw.githubusercontent.com/silasmas/comco/main/';
$files = [
  'app/Support/HomeSlideStyle.php',
  'app/Filament/Resources/SiteBlocks/Schemas/SiteBlockForm.php',
  'app/Filament/Resources/SiteBlocks/Pages/EditSiteBlock.php',
  'app/Filament/Resources/SiteBlocks/Pages/CreateSiteBlock.php',
  'resources/views/public/home/index.blade.php',
  'resources/views/components/home-slide-actions.blade.php',
  'resources/views/filament/site-blocks/partials/slide-preview-actions.blade.php',
  'resources/views/filament/site-blocks/partials/slide-preview-frame.blade.php',
  'resources/views/filament/site-blocks/slide-preview-inline.blade.php',
  'resources/views/filament/site-blocks/slide-preview-panel.blade.php',
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

passthru('php artisan view:clear', $code);
echo $code === 0 ? "VIEW_CLEAR_OK\n" : "VIEW_CLEAR_WARN\n";
echo "STBOK\n";
