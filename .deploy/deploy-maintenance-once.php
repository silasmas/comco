<?php

/**
 * Met à jour les fichiers maintenance/preview depuis GitHub sur le serveur.
 * Usage (racine Laravel): php deploy-maintenance-once.php
 */

declare(strict_types=1);

$root = is_file(__DIR__ . '/artisan') ? __DIR__ : dirname(__DIR__);
$base = 'https://raw.githubusercontent.com/silasmas/comco/main/';

$files = [
  'app/Http/Middleware/ShowMaintenancePage.php',
  'app/Support/MaintenanceMode.php',
  'app/Filament/Pages/ManageMaintenanceMode.php',
  'routes/web.php',
  'resources/views/layouts/elixir.blade.php',
  'resources/views/public/maintenance.blade.php',
  'bootstrap/app.php',
];

/**
 * Télécharge un fichier distant vers un chemin local.
 *
 * @param  string  $url  URL source
 * @param  string  $destination  Chemin local
 */
function download(string $url, string $destination): void
{
  $context = stream_context_create([
    'http' => [
      'follow_location' => 1,
      'timeout' => 120,
      'header' => "User-Agent: COMCO-Deploy\r\n",
    ],
  ]);
  $data = @file_get_contents($url, false, $context);
  if ($data === false || strlen($data) < 20) {
    throw new RuntimeException('Echec téléchargement: ' . $url);
  }
  $dir = dirname($destination);
  if (! is_dir($dir)) {
    mkdir($dir, 0755, true);
  }
  file_put_contents($destination, $data);
  echo 'OK ' . $destination . ' (' . strlen($data) . ")\n";
}

foreach ($files as $relative) {
  download($base . $relative, $root . '/' . $relative);
}

passthru('cd ' . escapeshellarg($root) . ' && php artisan route:clear && php artisan view:clear && php artisan config:clear', $code);
echo $code === 0 ? "Caches cleared\n" : "Cache clear warning\n";
echo "DONE\n";
