<?php

/**
 * Corrige le contenu et l'image du Centre d'Information (hub + sous-pages).
 * Usage: php fix-centre-information-once.php /chemin/vers/projet
 */

declare(strict_types=1);

$root = $argv[1] ?? getcwd();
require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$hubBody = <<<'HTML'
<p>Le <strong>Centre d’information</strong> de la Commission de la Concurrence (COMCO) regroupe les ressources documentaires mises à disposition du public, des opérateurs économiques et des partenaires institutionnels.</p>
<p>Vous y trouverez notamment&nbsp;:</p>
<ul>
  <li>les <strong>rapports</strong> d’activités et bilans de la Commission&nbsp;;</li>
  <li>les <strong>publications</strong> et notes d’information sur la concurrence et la protection des consommateurs&nbsp;;</li>
  <li>les documents utiles à la compréhension du mandat et des actions de la COMCO.</li>
</ul>
<p>Utilisez le menu de gauche pour accéder aux rubriques <em>Rapports</em> et <em>Publications</em>.</p>
HTML;

$rapportBody = <<<'HTML'
<p>Cette rubrique présente les <strong>rapports</strong> produits par la COMCO&nbsp;: rapports d’activités, bilans périodiques et documents de suivi de la mission de régulation de la concurrence en République démocratique du Congo.</p>
<p>Les fichiers PDF disponibles sont listés ci-dessous lorsqu’ils ont été publiés par l’administration.</p>
HTML;

$publicationBody = <<<'HTML'
<p>Cette rubrique regroupe les <strong>publications</strong> de la COMCO&nbsp;: notes d’information, guides, communiqués documentaires et autres supports destinés à informer le public et les opérateurs économiques.</p>
<p>Les documents publiés apparaissent dans la liste PDF de cette page.</p>
HTML;

$updates = [
  'CentredInformation' => [
    'title' => "Centre d'Information",
    'excerpt' => "Accédez aux rapports, publications et ressources documentaires de la Commission de la Concurrence.",
    'body' => $hubBody,
    'template' => 'presentation-hub',
    'content_display' => 'content',
  ],
  'rapport' => [
    'title' => 'Rapports',
    'excerpt' => "Rapports d’activités et bilans de la COMCO.",
    'body' => $rapportBody,
    'template' => 'presentation-hub',
    'content_display' => 'both',
  ],
  'publication' => [
    'title' => 'Publications',
    'excerpt' => 'Publications et notes d’information de la COMCO.',
    'body' => $publicationBody,
    'template' => 'presentation-hub',
    'content_display' => 'both',
  ],
];

foreach ($updates as $slug => $data) {
  $updated = DB::table('pages')
    ->where('section', 'centre-information')
    ->where('slug', $slug)
    ->update(array_merge($data, ['updated_at' => now()]));
  echo "PAGE {$slug} updated={$updated}\n";
}

$hubId = DB::table('pages')
  ->where('section', 'centre-information')
  ->where('slug', 'CentredInformation')
  ->value('id');

if ($hubId) {
  DB::table('page_gallery_items')->where('page_id', $hubId)->delete();
  DB::table('page_gallery_items')->insert([
    'page_id' => $hubId,
    'image' => 'talo.jpg',
    'image_source' => 'comco',
    'caption' => "Centre d'information COMCO",
    'sort_order' => 0,
    'created_at' => now(),
    'updated_at' => now(),
  ]);
  echo "GALLERY hub set to comco/talo.jpg\n";
}

echo "CENTREINFOOK\n";
