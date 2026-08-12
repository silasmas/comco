<?php

namespace App\Filament\Resources\EServiceDefinitions\Pages;

use App\Filament\Resources\EServiceDefinitions\EServiceDefinitionResource;
use App\Filament\Resources\Pages\ComcoListRecords;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;

/**
 * Page de liste des définitions e-services.
 */
class ListEServiceDefinitions extends ComcoListRecords
{
    protected static string $resource = EServiceDefinitionResource::class;

    /**
     * Retourne les actions disponibles dans l'en-tête de la liste.
     *
     * @return list<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nouveau formulaire'),
        ];
    }
}
