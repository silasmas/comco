<?php

namespace App\Filament\Resources\Pages;

use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

/**
 * Page d'édition COMCO avec description pédagogique pour l'administrateur.
 */
abstract class ComcoEditRecord extends EditRecord
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
