<?php

require '/home/u911414181/domains/comcordc.cd/public_html/vendor/autoload.php';
$app = require '/home/u911414181/domains/comcordc.cd/public_html/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$cols = Schema::getColumnListing('pages');
echo "PAGE_COLS=".implode(',', $cols)."\n";

$rows = DB::table('pages')
  ->where('section', 'centre-information')
  ->orWhere('slug', 'like', '%Centred%')
  ->orWhere('slug', 'like', '%rapport%')
  ->orWhere('slug', 'like', '%publication%')
  ->get(['id', 'section', 'slug', 'title', 'excerpt', 'template', 'is_published', 'updated_at']);

foreach ($rows as $r) {
  echo 'PAGE='.json_encode($r, JSON_UNESCAPED_UNICODE)."\n";
  $body = DB::table('pages')->where('id', $r->id)->value('body');
  echo 'BODY_LEN='.strlen((string) $body)."\n";
  echo 'BODY_START='.mb_substr(strip_tags((string) $body), 0, 220)."\n";
  if (Schema::hasTable('page_gallery_items')) {
    $g = DB::table('page_gallery_items')->where('page_id', $r->id)->orderBy('sort_order')->get(['id', 'image', 'image_source', 'caption', 'sort_order']);
    echo 'GALLERY='.json_encode($g, JSON_UNESCAPED_UNICODE)."\n";
  }
}

echo "---NAV---\n";
if (Schema::hasTable('navigation_items')) {
  $nav = DB::table('navigation_items')
    ->where(function ($q) {
      $q->where('label', 'like', '%Information%')
        ->orWhere('slug', 'like', '%Centred%')
        ->orWhere('slug', 'like', '%rapport%')
        ->orWhere('slug', 'like', '%publication%')
        ->orWhere('section', 'centre-information');
    })
    ->orderBy('sort_order')
    ->get(['id', 'menu', 'label', 'link_type', 'section', 'slug', 'url', 'parent_id', 'is_active', 'sort_order']);
  foreach ($nav as $n) {
    echo 'NAV='.json_encode($n, JSON_UNESCAPED_UNICODE)."\n";
  }
}
echo "DONE\n";
