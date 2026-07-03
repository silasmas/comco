<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasComcoResourceMeta;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;

/**
 * Tableau de bord principal du panneau d'administration COMCO.
 */
class Dashboard extends BaseDashboard
{
  use HasComcoResourceMeta;

  protected static string $resourceDescription = 'Vue d\'ensemble des activités du site : soumissions, contenus, forum et statistiques mensuelles.';

  protected static ?string $tourStepId = 'dashboard';

  protected static int $tourStepSort = 0;

  protected static array $tourStepFeatures = [
    'Consulter les indicateurs clés',
    'Suivre l\'évolution mensuelle par ressource',
    'Accéder rapidement aux modules de gestion',
  ];

  /**
   * Titre affiché dans la visite guidée.
   */
  public static function getTourStepTitle(): ?string
  {
    return 'Tableau de bord';
  }

  /**
   * Sous-titre pédagogique du tableau de bord.
   */
  public function getSubheading(): string|Htmlable|null
  {
    return static::getResourceDescription();
  }
}
