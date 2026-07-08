<?php

namespace App\Filament\Resources\NavigationItems\Pages;

use App\Filament\Resources\NavigationItems\NavigationItemResource;
use App\Filament\Resources\Pages\ComcoEditRecord;
use App\Support\SiteNavigation;

/**
 * Page d'édition d'un élément de navigation.
 */
class EditNavigationItem extends ComcoEditRecord
{
    protected static string $resource = NavigationItemResource::class;

    /**
     * Applique la navigation dynamique après sauvegarde.
     */
    protected function afterSave(): void
    {
        SiteNavigation::applyToConfig();
    }
}
