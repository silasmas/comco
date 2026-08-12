<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

/**
 * Bandeau d'accueil du tableau de bord avec le logo COMCO.
 */
class ComcoWelcomeWidget extends Widget
{
  protected static bool $isDiscovered = false;

  protected static ?int $sort = -10;

  protected int|string|array $columnSpan = 'full';

  protected string $view = 'filament.widgets.comco-welcome';
}
