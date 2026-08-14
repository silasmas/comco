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
        'body' => "La coordination nationale assure le pilotage stratégique de la COMCO.\n\nElle oriente les priorités institutionnelles, supervise les representations provinciales et veille à la cohérence des actions de régulation sur l'ensemble du territoire.",
        'image' => 'portrait-1.jpg',
        'image_source' => 'theme',
        'link_label' => 'En détail',
        'sort_order' => 0,
      ],
      [
        'title' => 'Conseil technique',
        'summary' => 'Appui analytique et recommandations techniques pour les dossiers de concurrence.',
        'body' => "Le conseil technique apporte une expertise spécialisée sur les dossiers complexes.\n\nIl formule des recommandations destinées à éclairer les décisions de la Commission en matière de concurrence et de protection des consommateurs.",
        'image' => 'portrait-2.jpg',
        'image_source' => 'theme',
        'link_label' => 'En détail',
        'sort_order' => 1,
      ],
      [
        'title' => 'Collège des analystes',
        'summary' => 'Évaluation des marchés, concentrations et pratiques anticoncurrentielles.',
        'body' => "Le collège des analystes étudie les marchés et les opérations de concentration.\n\nSes analyses contribuent à détecter les pratiques restrictives et à proposer des mesures adaptées.",
        'image' => 'portrait-3.jpg',
        'image_source' => 'theme',
        'link_label' => 'En détail',
        'sort_order' => 2,
      ],
      [
        'title' => 'Corps des enquêteurs',
        'summary' => 'Investigations de terrain et suivi des signalements transmis à la COMCO.',
        'body' => "Le corps des enquêteurs mène les investigations nécessaires au traitement des signalements.\n\nIl collecte les éléments de preuve et assure le suivi opérationnel des dossiers ouverts.",
        'image' => 'portrait-4.jpg',
        'image_source' => 'theme',
        'link_label' => 'En détail',
        'sort_order' => 3,
      ],
      [
        'title' => 'Représentations provinciales',
        'summary' => 'Présence territoriale pour un traitement rapproché des dossiers.',
        'body' => "Les représentations provinciales rapprochent la COMCO des usagers et opérateurs économiques.\n\nElles facilitent le dépôt des plaintes et le suivi local des dossiers de concurrence.",
        'image' => 'portrait-5.jpg',
        'image_source' => 'theme',
        'link_label' => 'En détail',
        'sort_order' => 4,
      ],
      [
        'title' => 'Secrétariat administratif',
        'summary' => 'Organisation des procédures, archivage et suivi administratif des dossiers.',
        'body' => "Le secrétariat administratif organise le circuit des dossiers et assure leur traçabilité.\n\nIl appuie les services techniques dans la gestion documentaire et le respect des délais de traitement.",
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
          'body' => $member['body'] ?? null,
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
