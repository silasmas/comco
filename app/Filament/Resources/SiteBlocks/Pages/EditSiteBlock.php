<?php

namespace App\Filament\Resources\SiteBlocks\Pages;

use App\Filament\Resources\Pages\ComcoEditRecord;
use App\Filament\Resources\SiteBlocks\Concerns\HasSlidePreviewAction;
use App\Filament\Resources\SiteBlocks\SiteBlockResource;
use App\Models\SiteBlock;
use App\Support\HomeSlideStyle;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;

/**
 * Page d'édition d'un bloc dynamique de la page d'accueil.
 */
class EditSiteBlock extends ComcoEditRecord
{
    use HasSlidePreviewAction;

    protected static string $resource = SiteBlockResource::class;

    /**
     * Actions d'en-tête, dont l'aperçu latéral du slide.
     *
     * @return list<Action|DeleteAction>
     */
    protected function getHeaderActions(): array
    {
        return [
            $this->makeHeaderSlidePreviewAction(),
            DeleteAction::make(),
        ];
    }

    /**
     * Hydrate les clés page de destination avant affichage du formulaire.
     *
     * @param  array<string, mixed>  $data  Données modèle
     * @return array<string, mixed> Données formulaire
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $payload = $data['payload'] ?? [];
        if (is_array($payload)) {
            $payload = HomeSlideStyle::hydrateButtonPageField($payload, 'btn_primary');
            $payload = HomeSlideStyle::hydrateButtonPageField($payload, 'btn_secondary');
            $data['payload'] = $payload;
        }

        return $data;
    }

    /**
     * Normalise le payload avant la sauvegarde.
     *
     * @param  array<string, mixed>  $data  Données du formulaire
     * @return array<string, mixed> Données normalisées
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['page'] = SiteBlock::PAGE_HOME;
        $data['payload'] = $this->normalizePayload($data['payload'] ?? [], $data['block_type'] ?? null);

        return $data;
    }

    /**
     * Nettoie le payload et applique l'image téléversée si présente.
     *
     * @param  array<string, mixed>  $payload  Données saisies
     * @param  string|null  $blockType  Type de bloc
     * @return array<string, mixed> Payload normalisé
     */
    private function normalizePayload(array $payload, ?string $blockType): array
    {
        if (! empty($payload['uploaded_image'])) {
            $payload['image'] = $payload['uploaded_image'];
            $payload['image_source'] = 'comco';
        }

        unset($payload['uploaded_image']);

        if (! empty($payload['uploaded_video'])) {
            $payload['video'] = $payload['uploaded_video'];
            $payload['source'] = 'upload';
        }

        unset($payload['uploaded_video']);

        $payload = HomeSlideStyle::syncButtonPageFields($payload, 'btn_primary');
        $payload = HomeSlideStyle::syncButtonPageFields($payload, 'btn_secondary');

        if ($blockType === SiteBlock::TYPE_FUN_FACT && isset($payload['value'])) {
            $payload['value'] = (int) $payload['value'];
        }

        return $payload;
    }
}
