<?php

/**
 * Déploie Présentation (sidebar), Coordination, titres sans bannière, switch nav.
 * Usage: php deploy-presentation-once.php /chemin/vers/projet
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
  'config/page-templates.php',
  'app/Models/NavigationItem.php',
  'app/Models/PageTeamMember.php',
  'app/Models/CoordinationMember.php',
  'app/Filament/Resources/NavigationItems/Tables/NavigationItemsTable.php',
  'app/Filament/Resources/Pages/RelationManagers/TeamMembersRelationManager.php',
  'app/Filament/Resources/CoordinationMembers/CoordinationMemberResource.php',
  'app/Filament/Resources/CoordinationMembers/Schemas/CoordinationMemberForm.php',
  'app/Filament/Resources/CoordinationMembers/Tables/CoordinationMembersTable.php',
  'app/Filament/Resources/CoordinationMembers/Pages/ListCoordinationMembers.php',
  'app/Filament/Resources/CoordinationMembers/Pages/CreateCoordinationMember.php',
  'app/Filament/Resources/CoordinationMembers/Pages/EditCoordinationMember.php',
  'database/migrations/2026_08_14_000001_create_coordination_members_table.php',
  'database/seeders/NavigationSeeder.php',
  'database/seeders/CoordinationMemberSeeder.php',
  'database/seeders/SiteContentSeeder.php',
  'resources/views/components/elixir/navbar.blade.php',
  'resources/views/components/elixir/page-title.blade.php',
  'resources/views/public/pages/show.blade.php',
  'resources/views/public/pages/contact.blade.php',
  'resources/views/public/pages/sitemap.blade.php',
  'resources/views/public/pages/templates/presentation-hub.blade.php',
  'resources/views/public/posts/show.blade.php',
  'resources/views/public/forum/index.blade.php',
  'resources/views/public/forum/show.blade.php',
  'public/theme/assets/css/comco-institutional.css',
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
runArtisan($root, 'cache:clear');
runArtisan($root, 'migrate --force');
runArtisan($root, 'db:seed --class=NavigationSeeder --force');
runArtisan($root, 'db:seed --class=InstitutionSeeder --force');
runArtisan($root, 'db:seed --class=CoordinationMemberSeeder --force');
runArtisan($root, 'view:clear');
runArtisan($root, 'route:clear');

echo "DONE\n";
