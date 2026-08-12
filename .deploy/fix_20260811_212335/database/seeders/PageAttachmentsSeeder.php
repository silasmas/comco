<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageGalleryItem;
use App\Models\PageLegalDocument;
use App\Models\PageTeamMember;
use Illuminate\Database\Seeder;

/**
 * Seeder des contenus attachés aux pages CMS (galerie, équipe, documents juridiques).
 */
class PageAttachmentsSeeder extends Seeder
{
    /**
     * Importe les contenus initiaux depuis la configuration et les gabarits existants.
     */
    public function run(): void
    {
        $this->seedGallery();
        $this->seedTeamMembers();
        $this->seedLegalDocuments();
    }

    /**
     * Crée les images de la galerie photo institutionnelle.
     */
    private function seedGallery(): void
    {
        $page = Page::query()
            ->where('section', 'medias')
            ->where('slug', 'galerie-photo')
            ->first();

        if ($page === null) {
            return;
        }

        $images = [
            'gallery/06-f.jpg',
            'gallery/07-f.jpg',
            'gallery/09-f.jpg',
            'gallery/10-f.jpg',
            'portfolio-1.jpg',
            'portfolio-2.jpg',
            'portfolio-3.jpg',
            'portfolio-4.jpg',
            'interior-1.jpg',
        ];

        foreach ($images as $index => $image) {
            PageGalleryItem::query()->updateOrCreate(
                [
                    'page_id' => $page->id,
                    'image' => $image,
                ],
                [
                    'image_source' => 'theme',
                    'caption' => 'COMCO — Activités institutionnelles',
                    'sort_order' => $index,
                    'is_active' => true,
                ],
            );
        }
    }

    /**
     * Crée les profils d'équipe des pages partenaires et coordination.
     */
    private function seedTeamMembers(): void
    {
        $members = [
            'partenaires' => [
                [
                    'name' => 'Coordination nationale',
                    'role' => 'Direction stratégique',
                    'image' => 'portrait-1.jpg',
                    'text' => 'Pilotage institutionnel et coordination des actions de la COMCO.',
                ],
                [
                    'name' => 'Collège des analystes',
                    'role' => 'Analyse économique',
                    'image' => 'portrait-4.jpg',
                    'text' => 'Évaluation des concentrations et des pratiques anticoncurrentielles.',
                ],
                [
                    'name' => 'Corps des enquêteurs',
                    'role' => 'Enquêtes & contrôles',
                    'image' => 'portrait-6.jpg',
                    'text' => 'Missions de terrain et collecte des preuves.',
                ],
            ],
            'coordination' => [
                [
                    'name' => 'Coordination nationale',
                    'role' => 'Direction stratégique',
                    'image' => 'portrait-1.jpg',
                    'text' => 'Pilotage institutionnel et coordination des actions de la COMCO.',
                ],
                [
                    'name' => 'Collège des analystes',
                    'role' => 'Analyse économique',
                    'image' => 'portrait-4.jpg',
                    'text' => 'Évaluation des concentrations et des pratiques anticoncurrentielles.',
                ],
                [
                    'name' => 'Corps des enquêteurs',
                    'role' => 'Enquêtes & contrôles',
                    'image' => 'portrait-6.jpg',
                    'text' => 'Missions de terrain et collecte des preuves.',
                ],
            ],
        ];

        foreach ($members as $slug => $profiles) {
            $page = Page::query()
                ->where('section', 'qui-sommes-nous')
                ->where('slug', $slug)
                ->first();

            if ($page === null) {
                continue;
            }

            foreach ($profiles as $index => $profile) {
                PageTeamMember::query()->updateOrCreate(
                    [
                        'page_id' => $page->id,
                        'name' => $profile['name'],
                    ],
                    [
                        'role' => $profile['role'],
                        'text' => $profile['text'],
                        'image' => $profile['image'],
                        'image_source' => 'theme',
                        'sort_order' => $index,
                        'is_active' => true,
                    ],
                );
            }
        }
    }

    /**
     * Crée les documents juridiques de la page cadre juridique.
     */
    private function seedLegalDocuments(): void
    {
        $page = Page::query()
            ->where('section', 'centre-information')
            ->where('slug', 'cadre-juridique')
            ->first();

        if ($page === null) {
            return;
        }

        foreach (config('legal.documents', []) as $index => $document) {
            PageLegalDocument::query()->updateOrCreate(
                [
                    'page_id' => $page->id,
                    'filename' => $document['filename'],
                ],
                [
                    'title' => $document['title'],
                    'description' => $document['description'],
                    'sort_order' => $index,
                    'is_active' => true,
                ],
            );
        }
    }
}
