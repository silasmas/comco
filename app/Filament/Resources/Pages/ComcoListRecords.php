<?php

namespace App\Filament\Resources\Pages;

use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

/**
 * Page de liste COMCO avec description pédagogique pour l'administrateur.
 */
abstract class ComcoListRecords extends ListRecords
{
  /**
   * Affiche l'utilité de la ressource sous le titre de la page.
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
