<?php

/**
 * Corrige layout Présentation (sidebar carte, image/texte, couverture éditable).
 * Usage: php deploy-presentation-layout-once.php /chemin/vers/projet
 */

declare(strict_types=1);

$root = $argv[1] ?? getcwd();
if (! is_file($root.'/artisan')) {
  fwrite(STDERR, "Racine Laravel invalide: {$root}\n");
  exit(1);
}

$base = 'https://raw.githubusercontent.com/silasmas/comco/main/';

$files = [
  'app/helpers.php',
  'app/Filament/Resources/Pages/PageResource.php',
  'app/Filament/Resources/Pages/Schemas/PageForm.php',
  'app/Filament/Resources/Pages/RelationManagers/GalleryItemsRelationManager.php',
  'config/cms-templates.php',
  'database/seeders/PageAttachmentsSeeder.php',
  'resources/views/public/pages/templates/presentation-hub.blade.php',
  'public/theme/assets/css/comco-institutional.css',
  'tests/Feature/PresentationHubTest.php',
];

/**
 * Télécharge un fichier distant.
 *
 * @param  string  $url  URL source
 * @param  string  $destination  Chemin local
 */
function downloadFile(string $url, string $destination): void
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
    throw new RuntimeException('Echec téléchargement: '.$url);
  }
  $dir = dirname($destination);
  if (! is_dir($dir)) {
    mkdir($dir, 0755, true);
  }
  file_put_contents($destination, $data);
  echo 'OK '.$destination.' ('.strlen($data).")\n";
}

/**
 * Exécute une commande artisan.
 *
 * @param  string  $root  Racine Laravel
 * @param  string  $artisanCommand  Commande artisan
 */
function runArtisan(string $root, string $artisanCommand): void
{
  $descriptor = [1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
  $process = proc_open('php artisan '.$artisanCommand, $descriptor, $pipes, $root);
  if (! is_resource($process)) {
    echo "WARN unable to run artisan {$artisanCommand}\n";

    return;
  }
  $stdout = stream_get_contents($pipes[1]);
  $stderr = stream_get_contents($pipes[2]);
  fclose($pipes[1]);
  fclose($pipes[2]);
  $code = proc_close($process);
  echo ($code === 0 ? 'OK' : 'WARN')." artisan {$artisanCommand}\n";
  if ($stdout) {
    echo trim($stdout)."\n";
  }
  if ($stderr && $code !== 0) {
    echo trim($stderr)."\n";
  }
}

foreach ($files as $relative) {
  downloadFile($base.$relative, $root.'/'.$relative);
}

runArtisan($root, 'config:clear');
runArtisan($root, 'db:seed --class=PageAttachmentsSeeder --force');
runArtisan($root, 'view:clear');
runArtisan($root, 'cache:clear');

echo "DONE\n";
