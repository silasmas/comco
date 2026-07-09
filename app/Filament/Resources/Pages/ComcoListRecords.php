<?php

namespace App\Filament\Resources\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

/**
 * Page de liste COMCO avec description pédagogique pour l'administrateur.
 */
abstract class ComcoListRecords extends ListRecords
{
  /**
   * Isole la pagination de chaque ressource pour éviter les conflits d'URL.
   *
   * @return Table Table Filament configurée
   */
  protected function makeTable(): Table
  {
    return parent::makeTable()
      ->queryStringIdentifier(Str::camel(static::getResource()::getSlug()))
      ->deferLoading(false);
  }

  /**
   * Charge le tableau et purge le cache des lignes pour forcer un rafraîchissement.
   */
  public function loadTable(): void
  {
    $this->isTableLoaded = true;
    $this->flushCachedTableRecords();
  }

  /**
   * Affiche l'utilité de la ressource sous le titre de la page.
   *
   * @return string|Htmlable|null Sous-titre pédagogique
   */
  public function getSubheading(): string|Htmlable|null
  {
    $resource = static::getResource();
    $description = method_exists($resource, 'getResourceDescription')
      ? $resource::getResourceDescription()
      : null;

    return filled($description) ? $description : parent::getSubheading();
  }
}
