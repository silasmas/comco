<?php

/**
 * Force la synchronisation du menu depuis config/navigation.php.
 * Usage: php force-nav-sync.php /chemin/vers/projet
 */

declare(strict_types=1);

$root = $argv[1] ?? getcwd();
require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\NavigationItem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::call('config:clear');
config(['navigation' => require $root.'/config/navigation.php']);

$before = NavigationItem::query()->where('menu', 'main')->whereNull('parent_id')->pluck('label')->all();

DB::transaction(function () use ($root): void {
  NavigationItem::query()->where('menu', NavigationItem::MENU_MAIN)->whereNotNull('parent_id')->delete();
  NavigationItem::query()->where('menu', NavigationItem::MENU_MAIN)->delete();
  NavigationItem::query()->where('menu', NavigationItem::MENU_FOOTER_NAVIGATION)->delete();
  NavigationItem::query()->where('menu', NavigationItem::MENU_FOOTER_ESERVICES)->delete();
  NavigationItem::query()->where('menu', NavigationItem::MENU_FOOTER_QUICK)->delete();

  $exit = Artisan::call('db:seed', ['--class' => 'NavigationSeeder', '--force' => true]);
  if ($exit !== 0) {
    throw new RuntimeException('NavigationSeeder failed: '.Artisan::output());
  }
});

$after = NavigationItem::query()->where('menu', 'main')->whereNull('parent_id')->orderBy('sort_order')->pluck('label')->all();

$payload = [
  'before' => $before,
  'after' => $after,
  'config_first_group' => config('navigation.main.1.label') ?? null,
  'seed_output' => trim(Artisan::output()),
];

file_put_contents($root.'/public/navcheck.txt', json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo json_encode($payload, JSON_UNESCAPED_UNICODE)."\n";
