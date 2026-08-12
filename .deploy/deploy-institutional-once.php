<?php

/**
 * Déploie la refonte institutionnelle (menus, sobriété, contact) depuis GitHub.
 * Usage: php deploy-institutional-once.php /chemin/vers/projet
 */

declare(strict_types=1);

$root = $argv[1] ?? getcwd();
if (! is_file($root.'/artisan')) {
  fwrite(STDERR, "Racine Laravel invalide: {$root}\n");
  exit(1);
}

$base = 'https://raw.githubusercontent.com/silasmas/comco/main/';

$files = [
  'config/navigation.php',
  'config/pages-content.php',
  'config/page-templates.php',
  'config/institution.php',
  'database/seeders/NavigationSeeder.php',
  'database/seeders/HomeContentSeeder.php',
  'app/Support/SiteNavigation.php',
  'app/Http/Controllers/Public/SitemapController.php',
  'routes/web.php',
  'resources/views/layouts/elixir.blade.php',
  'resources/views/components/elixir/page-header.blade.php',
  'resources/views/components/elixir/footer.blade.php',
  'resources/views/public/home/index.blade.php',
  'resources/views/public/pages/sitemap.blade.php',
  'resources/views/public/pages/templates/about.blade.php',
  'resources/views/public/pages/templates/alumni.blade.php',
  'resources/views/public/pages/templates/newsroom.blade.php',
  'resources/views/public/pages/templates/service.blade.php',
  'resources/views/public/posts/show.blade.php',
  'resources/views/livewire/public/latest-posts.blade.php',
  'public/theme/assets/css/comco-institutional.css',
  'tests/Feature/SiteSettingsPhaseThreeTest.php',
];

/**
 * Télécharge un fichier distant vers un chemin local.
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
 * Exécute une commande artisan dans la racine du projet.
 *
 * @param  string  $root  Racine Laravel
 * @param  string  $artisanCommand  Commande artisan sans préfixe php
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

require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$address = 'Avenue Ouganda, n°4216, Quartier des Cliniques, Kinshasa-Gombe, République démocratique du Congo';
App\Models\SiteSetting::store('institution.contact.address', $address);
echo "OK address updated\n";

runArtisan($root, 'db:seed --class=NavigationSeeder --force');
runArtisan($root, 'db:seed --class=InstitutionSeeder --force');
runArtisan($root, 'db:seed --class=HomeContentSeeder --force');
runArtisan($root, 'route:clear');
runArtisan($root, 'view:clear');
runArtisan($root, 'config:clear');
runArtisan($root, 'cache:clear');

echo "DONE\n";
