<?php
require "/home/u911414181/domains/comcordc.cd/public_html/vendor/autoload.php";
$app = require "/home/u911414181/domains/comcordc.cd/public_html/bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$rows = DB::table("pages")->where("section","centre-information")->orWhere("slug","like","%Centred%")->orWhere("slug","like","%rapport%")->orWhere("slug","like","%publication%")->get(["id","section","slug","title","excerpt","template","featured_image","image","hero_image","updated_at"]);
foreach ($rows as $r) {
  echo json_encode($r, JSON_UNESCAPED_UNICODE)."\n";
}
echo "---NAV---\n";
if (Schema::hasTable("navigation_items")) {
  $nav = DB::table("navigation_items")->where("label","like","%Information%")->orWhere("slug","like","%Centred%")->orWhere("section","centre-information")->get(["id","menu","label","link_type","section","slug","url","parent_id","is_active","sort_order"]);
  foreach ($nav as $n) { echo json_encode($n, JSON_UNESCAPED_UNICODE)."\n"; }
}
