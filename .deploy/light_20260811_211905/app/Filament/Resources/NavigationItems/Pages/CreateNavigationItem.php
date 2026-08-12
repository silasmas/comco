<?php

namespace App\Filament\Resources\NavigationItems\Pages;

use App\Filament\Resources\NavigationItems\NavigationItemResource;
use App\Support\SiteNavigation;
use Filament\Resources\Pages\CreateRecord;

/**
 * Page de création d'un élément de navigation.
 */
class CreateNavigationItem extends CreateRecord
{
    protected static string $resource = NavigationItemResource::class;

    /**
     * Applique la navigation dynamique après création.
     */
    protected function afterCreate(): void
    {
        SiteNavigation::applyToConfig();
    }
}
