<?php

/**
 * Met à jour les fichiers maintenance/preview depuis GitHub sur le serveur.
 * Usage (racine Laravel): php deploy-maintenance-once.php
 */

declare(strict_types=1);

$root = $argv[1] ?? getcwd();
if (! is_file($root . '/artisan')) {
  fwrite(STDERR, "Racine Laravel invalide: {$root}\nUsage: php deploy-maintenance-once.php /chemin/vers/projet\n");
  exit(1);
}
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

foreach (['route:clear', 'view:clear', 'config:clear'] as $artisanCommand) {
  $descriptor = [1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
  $process = proc_open(
    'php artisan ' . $artisanCommand,
    $descriptor,
    $pipes,
    $root
  );
  if (is_resource($process)) {
    stream_get_contents($pipes[1]);
    stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $code = proc_close($process);
    echo $code === 0 ? "OK artisan {$artisanCommand}\n" : "WARN artisan {$artisanCommand}\n";
  } else {
    echo "WARN unable to run artisan {$artisanCommand}\n";
  }
}
echo "DONE\n";
