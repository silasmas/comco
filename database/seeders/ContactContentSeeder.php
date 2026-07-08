<?php

namespace Database\Seeders;

use App\Models\SiteBlock;
use Illuminate\Database\Seeder;

/**
 * Seeder du contenu dynamique de la page contact.
 */
class ContactContentSeeder extends Seeder
{
    /**
     * Importe les blocs initiaux de la page contact.
     */
    public function run(): void
    {
        $blocks = [
            [
                'block_key' => 'provincial_offices',
                'block_type' => SiteBlock::TYPE_INFO_CARD,
                'label' => 'Représentations provinciales',
                'payload' => [
                    'title' => 'Représentations provinciales',
                    'text' => 'La COMCO dispose de représentations provinciales pour assurer une couverture effective sur l\'ensemble du territoire national. Les coordonnées détaillées seront publiées prochainement.',
                ],
            ],
            [
                'block_key' => 'eservices_cta',
                'block_type' => SiteBlock::TYPE_CTA,
                'label' => 'Bloc e-services',
                'payload' => [
                    'title' => 'E-services',
                    'text' => 'Déposez une fusion, une exemption ou une plainte directement en ligne.',
                ],
            ],
        ];

        foreach ($blocks as $index => $block) {
            SiteBlock::query()->updateOrCreate(
                [
                    'page' => SiteBlock::PAGE_CONTACT,
                    'block_key' => $block['block_key'],
                ],
                [
                    'block_type' => $block['block_type'],
                    'label' => $block['label'],
                    'payload' => $block['payload'],
                    'sort_order' => $index,
                    'is_active' => true,
                ],
            );
        }
    }
}
