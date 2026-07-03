<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Support\InstallationPageData;
use App\Support\SiteDeploymentState;
use BackedEnum;
use Filament\Pages\Concerns\CanAuthorizeAccess;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

/**
 * Page Filament d'installation production (accessible aux super administrateurs après mise en ligne).
 */
class SiteInstallation extends Page
{
  use CanAuthorizeAccess;

  protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRocketLaunch;

  protected static ?string $navigationLabel = 'Installation production';

  protected static ?string $title = 'Installation & production';

  protected static string|\UnitEnum|null $navigationGroup = 'Système';

  protected static ?int $navigationSort = 99;

  protected static ?string $slug = 'installation-production';

  protected string $view = 'filament.pages.site-installation';

  /**
   * Vérifie l'accès réservé aux super administrateurs.
   */
  public static function canAccess(): bool
  {
    $user = Auth::user();

    if ($user instanceof User && $user->is_super_admin) {
      return true;
    }

    return ! SiteDeploymentState::hasSuperAdmin();
  }

  /**
   * Affiche le menu uniquement une fois le site installé.
   */
  public static function shouldRegisterNavigation(): bool
  {
    if (SiteDeploymentState::requiresInstallation()) {
      return false;
    }

    $user = Auth::user();

    return $user instanceof User && $user->is_super_admin;
  }

  /**
   * @return array<string, mixed>
   */
  protected function getViewData(): array
  {
    return array_merge(parent::getViewData(), InstallationPageData::forView(), [
      'embeddedInFilament' => true,
    ]);
  }

  /**
   * @return string|Htmlable
   */
  public function getSubheading(): string|Htmlable|null
  {
    return 'Migrations, configuration, seeders d\'articles et maintenance du serveur de production.';
  }
}
