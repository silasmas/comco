<?php

namespace App\Http\Controllers\Admin;

use App\Filament\Pages\Dashboard;
use App\Http\Controllers\Controller;
use App\Support\SiteDeploymentState;
use App\Support\SiteInstaller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Exécute les actions d'installation hors requêtes Livewire (config:cache incompatible).
 */
class InstallationActionController extends Controller
{
  /**
   * Exécute les migrations de base de données.
   */
  public function migrate(): RedirectResponse
  {
    $this->ensureAccess();

    try {
      $message = SiteInstaller::runMigrations();
    } catch (\Throwable $exception) {
      return $this->backToInstallation('error', 'Migrations impossible', $exception->getMessage());
    }

    return $this->backToInstallation('success', 'Migrations exécutées', $message);
  }

  /**
   * Crée le lien symbolique public/storage.
   */
  public function storageLink(): RedirectResponse
  {
    $this->ensureAccess();

    try {
      $message = SiteInstaller::createStorageLink();
    } catch (\Throwable $exception) {
      return $this->backToInstallation('error', 'Lien storage impossible', $exception->getMessage());
    }

    return $this->backToInstallation('success', 'Lien storage', $message);
  }

  /**
   * Met en cache la configuration pour la production.
   */
  public function optimize(): RedirectResponse
  {
    $this->ensureAccess();

    try {
      $message = SiteInstaller::optimizeApplication(full: false);
    } catch (\Throwable $exception) {
      return $this->backToInstallation('error', 'Optimisation impossible', $exception->getMessage());
    }

    return $this->backToInstallation('success', 'Optimisation terminée', $message);
  }

  /**
   * Exécute migrations, lien storage et optimisation légère.
   */
  public function runAll(): RedirectResponse
  {
    $this->ensureAccess();

    try {
      $messages = [
        SiteInstaller::runMigrations(),
        SiteInstaller::createStorageLink(),
        SiteInstaller::optimizeApplication(full: false),
      ];
    } catch (\Throwable $exception) {
      return $this->backToInstallation('error', 'Installation impossible', $exception->getMessage());
    }

    return $this->backToInstallation('success', 'Installation terminée', implode(' | ', $messages));
  }

  /**
   * Finalise le déploiement et redirige vers le tableau de bord.
   */
  public function launch(): RedirectResponse
  {
    $this->ensureAccess();

    try {
      SiteInstaller::finalizeDeployment();
    } catch (\Throwable $exception) {
      return $this->backToInstallation('error', 'Mise en ligne impossible', $exception->getMessage());
    }

    return redirect()
      ->to(Dashboard::getUrl())
      ->with('installation_notice', [
        'type' => 'success',
        'title' => 'Site mis en ligne',
        'body' => 'Le site public est maintenant accessible.',
      ]);
  }

  /**
   * Vérifie que l'action d'installation est autorisée.
   */
  private function ensureAccess(): void
  {
    if (SiteDeploymentState::requiresInstallation()) {
      return;
    }

    $user = Auth::user();

    if ($user !== null && $user->is_super_admin) {
      return;
    }

    abort(403);
  }

  /**
   * Redirige vers la page d'installation avec un message flash.
   *
   * @param string $type Type de notification (success|error)
   * @param string $title Titre affiché
   * @param string $body Détail du message
   */
  private function backToInstallation(string $type, string $title, string $body): RedirectResponse
  {
    return redirect()
      ->to(SiteDeploymentState::installationPath())
      ->with('installation_notice', compact('type', 'title', 'body'));
  }
}
