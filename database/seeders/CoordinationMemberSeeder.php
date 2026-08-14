<?php

namespace Database\Seeders;

use App\Models\CoordinationMember;
use App\Models\Page;
use App\Models\PageGalleryItem;
use Illuminate\Database\Seeder;

/**
 * Seeder des fiches Coordination et image de couverture Présentation.
 */
class CoordinationMemberSeeder extends Seeder
{
  /**
   * Importe les contenus initiaux de Coordination.
   */
  public function run(): void
  {
    $this->seedPresentationCover();
    $this->seedCoordinationMembers();
  }

  /**
   * Attache une image de couverture à la page Présentation.
   */
  private function seedPresentationCover(): void
  {
    $page = Page::query()
      ->where('section', 'qui-sommes-nous')
      ->where('slug', 'presentation')
      ->first();

    if ($page === null) {
      return;
    }

    PageGalleryItem::query()->updateOrCreate(
      [
        'page_id' => $page->id,
        'image' => 'background-2.jpg',
      ],
      [
        'image_source' => 'theme',
        'caption' => 'Présentation de la COMCO',
        'sort_order' => 0,
        'is_active' => true,
      ],
    );
  }

  /**
   * Crée les fiches Coordination par défaut.
   */
  private function seedCoordinationMembers(): void
  {
    $members = [
      [
        'title' => 'Coordination nationale',
        'summary' => 'Direction stratégique et pilotage opérationnel de la Commission de la Concurrence.',
        'image' => 'portrait-1.jpg',
        'image_source' => 'theme',
        'link_label' => 'En détail',
        'sort_order' => 0,
      ],
      [
        'title' => 'Conseil technique',
        'summary' => 'Appui analytique et recommandations techniques pour les dossiers de concurrence.',
        'image' => 'portrait-2.jpg',
        'image_source' => 'theme',
        'link_label' => 'En détail',
        'sort_order' => 1,
      ],
      [
        'title' => 'Collège des analystes',
        'summary' => 'Évaluation des marchés, concentrations et pratiques anticoncurrentielles.',
        'image' => 'portrait-3.jpg',
        'image_source' => 'theme',
        'link_label' => 'En détail',
        'sort_order' => 2,
      ],
      [
        'title' => 'Corps des enquêteurs',
        'summary' => 'Investigations de terrain et suivi des signalements transmis à la COMCO.',
        'image' => 'portrait-4.jpg',
        'image_source' => 'theme',
        'link_label' => 'En détail',
        'sort_order' => 3,
      ],
      [
        'title' => 'Représentations provinciales',
        'summary' => 'Présence territoriale pour un traitement rapproché des dossiers.',
        'image' => 'portrait-5.jpg',
        'image_source' => 'theme',
        'link_label' => 'En détail',
        'sort_order' => 4,
      ],
      [
        'title' => 'Secrétariat administratif',
        'summary' => 'Organisation des procédures, archivage et suivi administratif des dossiers.',
        'image' => 'portrait-6.jpg',
        'image_source' => 'theme',
        'link_label' => 'En détail',
        'sort_order' => 5,
      ],
    ];

    foreach ($members as $member) {
      CoordinationMember::query()->updateOrCreate(
        ['title' => $member['title']],
        [
          'summary' => $member['summary'],
          'image' => $member['image'],
          'image_source' => $member['image_source'],
          'link_url' => $member['link_url'] ?? null,
          'link_label' => $member['link_label'] ?? 'En détail',
          'sort_order' => $member['sort_order'],
          'is_active' => true,
        ],
      );
    }
  }
}
