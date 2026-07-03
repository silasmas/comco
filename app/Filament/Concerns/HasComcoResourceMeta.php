<?php

namespace App\Filament\Concerns;

use YacoubAlhaidari\FilamentTour\Concerns\HasTourSteps;

/**
 * Métadonnées communes aux ressources Filament COMCO (description et visite guidée).
 */
trait HasComcoResourceMeta
{
  use HasTourSteps;

  /**
   * Retourne la description affichée en sous-titre des pages de la ressource.
   */
  public static function getResourceDescription(): string
  {
    if (property_exists(static::class, 'resourceDescription')) {
      return static::$resourceDescription;
    }

    return '';
  }

  /**
   * Retourne l'identifiant unique de l'étape de visite guidée.
   */
  public static function getTourStepId(): ?string
  {
    if (property_exists(static::class, 'tourStepId') && static::$tourStepId !== null) {
      return static::$tourStepId;
    }

    return str(class_basename(static::class))->before('Resource')->kebab()->toString();
  }

  /**
   * Retourne le titre de l'étape de visite guidée.
   */
  public static function getTourStepTitle(): ?string
  {
    return static::getNavigationLabel() ?? (method_exists(static::class, 'getModelLabel') ? static::getModelLabel() : null);
  }

  /**
   * Retourne la description de l'étape de visite guidée.
   */
  public static function getTourStepDescription(): ?string
  {
    $description = static::getResourceDescription();

    return $description !== '' ? $description : null;
  }

  /**
   * Retourne les fonctionnalités listées dans la visite guidée.
   *
   * @return list<string>
   */
  public static function getTourStepFeatures(): array
  {
    if (property_exists(static::class, 'tourStepFeatures')) {
      return static::$tourStepFeatures;
    }

    return [];
  }

  /**
   * Retourne l'ordre de l'étape dans la visite guidée.
   */
  public static function getTourStepSort(): int
  {
    if (property_exists(static::class, 'tourStepSort')) {
      return static::$tourStepSort;
    }

    return 100;
  }
}
