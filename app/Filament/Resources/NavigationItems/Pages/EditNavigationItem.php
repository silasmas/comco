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
     * Sous-titre d'aide affiché sous le titre de la page.
     *
     * @return string|null Texte d'aide
     */
    public function getSubheading(): ?string
    {
        return 'Menu principal = barre du haut. Groupe + slug hub = menu latéral. Page CMS = même section/slug que dans « Pages ».';
    }

    /**
     * Applique la navigation dynamique après sauvegarde.
     */
    protected function afterSave(): void
    {
        SiteNavigation::applyToConfig();
    }
}
