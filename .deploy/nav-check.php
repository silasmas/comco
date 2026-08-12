<?php

declare(strict_types=1);

$root = $argv[1] ?? getcwd();
require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$labels = App\Models\NavigationItem::query()
  ->where('menu', 'main')
  ->whereNull('parent_id')
  ->orderBy('sort_order')
  ->pluck('label')
  ->all();

$out = [
  'db' => $labels,
  'config_file_has_presentation' => str_contains((string) file_get_contents($root.'/config/navigation.php'), 'Présentation'),
  'main_count' => App\Models\NavigationItem::query()->where('menu', 'main')->count(),
];

file_put_contents($root.'/public/navcheck.txt', json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo json_encode($out, JSON_UNESCAPED_UNICODE)."\n";
