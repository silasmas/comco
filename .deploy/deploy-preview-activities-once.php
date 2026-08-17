<?php

/**
 * Déploie prévisualisation admin + onglet Activités (photo/vidéo).
 * Usage: php deploy-preview-activities-once.php /chemin/vers/projet
 */

declare(strict_types=1);

$root = $argv[1] ?? getcwd();
if (! is_file($root.'/artisan')) {
  fwrite(STDERR, "Racine Laravel invalide: {$root}\n");
  exit(1);
}

$base = 'https://raw.githubusercontent.com/silasmas/comco/main/';

$files = [
  'app/Models/Post.php',
  'app/Http/Controllers/Public/PostController.php',
  'app/Livewire/Public/LatestPosts.php',
  'app/Filament/Resources/Posts/PostResource.php',
  'app/Filament/Resources/Posts/Schemas/PostForm.php',
  'app/Filament/Resources/Posts/Tables/PostsTable.php',
  'app/Filament/Resources/Posts/Pages/EditPost.php',
  'database/migrations/2026_08_17_000001_add_content_type_to_posts_table.php',
  'routes/web.php',
  'resources/views/public/posts/show.blade.php',
  'resources/views/public/home/index.blade.php',
  'resources/views/livewire/public/latest-posts.blade.php',
  'public/theme/assets/css/comco-institutional.css',
  'tests/Feature/PostPreviewAndActivityTest.php',
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

runArtisan($root, 'migrate --force');
runArtisan($root, 'route:clear');
runArtisan($root, 'view:clear');
runArtisan($root, 'cache:clear');

echo "DONE\n";
