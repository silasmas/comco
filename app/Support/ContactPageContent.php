<?php

namespace App\Support;

use App\Models\SiteBlock;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

/**
 * Agrège le contenu dynamique de la page contact.
 */
class ContactPageContent
{
    /**
     * @param  Collection<int, SiteBlock>  $blocks  Blocs actifs de la page contact
     */
    public function __construct(
        private readonly Collection $blocks,
    ) {}

    /**
     * Charge le contenu de la page contact depuis la base ou les valeurs par défaut.
     *
     * @return self Instance prête pour la vue publique
     */
    public static function resolve(): self
    {
        if (! Schema::hasTable('site_blocks')) {
            return new self(collect());
        }

        $blocks = SiteBlock::query()
            ->forPage(SiteBlock::PAGE_CONTACT)
            ->active()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return new self($blocks);
    }

    /**
     * Retourne le bloc « Représentations provinciales ».
     *
     * @return array<string, string> Titre et texte du bloc
     */
    public function provincialOffices(): array
    {
        return $this->card(
            SiteBlock::TYPE_INFO_CARD,
            'provincial_offices',
            [
                'title' => 'Représentations provinciales',
                'text' => 'La COMCO dispose de représentations provinciales pour assurer une couverture effective sur l\'ensemble du territoire national. Les coordonnées détaillées seront publiées prochainement.',
            ],
        );
    }

    /**
     * Retourne le bloc d'appel aux e-services.
     *
     * @return array<string, string> Titre et texte du bloc
     */
    public function eServicesCta(): array
    {
        return $this->card(
            SiteBlock::TYPE_CTA,
            'eservices_cta',
            [
                'title' => 'E-services',
                'text' => 'Déposez une fusion, une exemption ou une plainte directement en ligne.',
            ],
        );
    }

    /**
     * Retourne un bloc carte depuis la base ou une valeur par défaut.
     *
     * @param  string  $blockType  Type de bloc
     * @param  string  $blockKey  Clé métier du bloc
     * @param  array<string, string>  $fallback  Valeurs par défaut
     * @return array<string, string> Contenu normalisé
     */
    private function card(string $blockType, string $blockKey, array $fallback): array
    {
        $block = $this->blocks->first(
            fn (SiteBlock $item): bool => $item->block_type === $blockType
              && $item->block_key === $blockKey
        );

        if ($block === null) {
            return $fallback;
        }

        return [
            'title' => (string) ($block->payload['title'] ?? $fallback['title']),
            'text' => (string) ($block->payload['text'] ?? $fallback['text']),
        ];
    }
}
