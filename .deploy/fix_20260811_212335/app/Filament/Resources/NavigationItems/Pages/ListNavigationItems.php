<?php

namespace App\Filament\Resources\NavigationItems\Pages;

use App\Filament\Resources\NavigationItems\NavigationItemResource;
use App\Filament\Resources\Pages\ComcoListRecords;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;

/**
 * Page de liste des éléments de navigation.
 */
class ListNavigationItems extends ComcoListRecords
{
    protected static string $resource = NavigationItemResource::class;

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
