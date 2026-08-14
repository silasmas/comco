<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$page = App\Models\Page::query()->where('slug', 'missions-services')->first();
if (! $page) {
  echo "MISSING\n";
  exit;
}
echo 'id='.$page->id."\n";
echo 'title='.$page->title."\n";
echo 'template='.$page->template."\n";
echo 'excerpt_len='.strlen((string) $page->excerpt)."\n";
echo 'body_len='.strlen((string) $page->body)."\n";
echo 'team='.$page->teamMembers()->count()."\n";
echo 'excerpt='.substr((string) $page->excerpt, 0, 100)."\n";
echo 'body='.substr(strip_tags((string) $page->body), 0, 150)."\n";
