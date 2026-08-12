<?php

namespace App\Filament\Resources\SiteBlocks\Pages;

use App\Filament\Resources\SiteBlocks\SiteBlockResource;
use App\Models\SiteBlock;
use Filament\Resources\Pages\CreateRecord;

/**
 * Page de création d'un bloc dynamique de la page d'accueil.
 */
class CreateSiteBlock extends CreateRecord
{
    protected static string $resource = SiteBlockResource::class;

    /**
     * Prépare les données par défaut avant la création.
     *
     * @param  array<string, mixed>  $data  Données du formulaire
     * @return array<string, mixed> Données normalisées
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['page'] = SiteBlock::PAGE_HOME;
        $data['payload'] = $this->normalizePayload($data['payload'] ?? [], $data['block_type'] ?? null);

        if (empty($data['block_key'])) {
            $data['block_key'] = ($data['block_type'] ?? 'block').'-'.uniqid();
        }

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

        if ($blockType === SiteBlock::TYPE_FUN_FACT && isset($payload['value'])) {
            $payload['value'] = (int) $payload['value'];
        }

        return $payload;
    }
}
