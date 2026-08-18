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
     * Sous-titre d'aide affiché sous le titre de la page.
     *
     * @return string|null Texte d'aide
     */
    public function getSubheading(): ?string
    {
        return 'Créez d’abord la page dans « Pages », puis rattachez-la ici (même section + slug). Pour un menu latéral : parent type Groupe avec section et slug hub, puis ajoutez les enfants sous ce parent.';
    }

    /**
     * Applique la navigation dynamique après création.
     */
    protected function afterCreate(): void
    {
        SiteNavigation::applyToConfig();
    }
}
