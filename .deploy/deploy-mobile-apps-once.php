<?php

/**
 * Déploie la fonctionnalité Applications APK depuis GitHub main.
 * Usage: php deploy-mobile-apps-once.php /chemin/vers/projet
 * Ne dépend pas de passthru() (souvent désactivé chez Hostinger).
 */

declare(strict_types=1);

$root = $argv[1] ?? getcwd();
if (! is_file($root.'/artisan')) {
  fwrite(STDERR, "Racine Laravel invalide: {$root}\n");
  exit(1);
}

$base = 'https://raw.githubusercontent.com/silasmas/comco/main/';
$files = [
  'database/migrations/2026_09_09_000001_create_mobile_apps_table.php',
  'app/Models/MobileApp.php',
  'app/Http/Controllers/Public/MobileAppController.php',
  'app/Filament/Resources/MobileApps/MobileAppResource.php',
  'app/Filament/Resources/MobileApps/Schemas/MobileAppForm.php',
  'app/Filament/Resources/MobileApps/Tables/MobileAppsTable.php',
  'app/Filament/Resources/MobileApps/Pages/ListMobileApps.php',
  'app/Filament/Resources/MobileApps/Pages/CreateMobileApp.php',
  'app/Filament/Resources/MobileApps/Pages/EditMobileApp.php',
  'resources/views/filament/mobile-apps/share-panel.blade.php',
  'resources/views/public/mobile-apps/show.blade.php',
  'routes/web.php',
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

echo "FILES_OK\n";
