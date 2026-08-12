<?php

namespace App\Filament\Resources\SiteBlocks\Pages;

use App\Filament\Resources\Pages\ComcoListRecords;
use App\Filament\Resources\SiteBlocks\SiteBlockResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;

/**
 * Page de liste des blocs dynamiques de la page d'accueil.
 */
class ListSiteBlocks extends ComcoListRecords
{
    protected static string $resource = SiteBlockResource::class;

    /**
     * Retourne les actions disponibles dans l'en-tête de la liste.
     *
     * @return list<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
