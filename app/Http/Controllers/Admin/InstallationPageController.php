<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\InstallationPageData;
use App\Support\SiteDeploymentState;
use App\Support\SiteInstaller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

/**
 * Affiche la page d'installation sans composants Filament (compatible avant migrations).
 */
class InstallationPageController extends Controller
{
  /**
   * Affiche le formulaire d'installation production.
   */
  public function show(): View|RedirectResponse
  {
    if (! $this->canAccess()) {
      abort(403);
    }

    return view('admin.site-installation', InstallationPageData::forView());
  }

  /**
   * Enregistre la configuration de base dans le fichier .env.
   */
  public function saveConfiguration(Request $request): RedirectResponse
  {
    if (! $this->canAccess()) {
      abort(403);
    }

    $data = $request->validate([
      'app_url' => ['required', 'url'],
      'app_env' => ['required', 'string', 'max:50'],
      'app_debug' => ['required', 'in:true,false,0,1'],
      'institution_full_name' => ['required', 'string', 'max:255'],
      'institution_name' => ['required', 'string', 'max:100'],
      'institution_email' => ['required', 'email', 'max:255'],
      'mail_from_address' => ['required', 'email', 'max:255'],
      'mail_from_name' => ['required', 'string', 'max:255'],
    ]);

    try {
      $updated = SiteInstaller::applyBaseConfiguration($data);
    } catch (\Throwable $exception) {
      return $this->backWithInput('error', 'Configuration impossible', $exception->getMessage());
    }

    return $this->back('success', 'Configuration enregistrée', count($updated) . ' variable(s) mise(s) à jour dans le fichier .env.');
  }

  /**
   * Crée le compte super administrateur.
   */
  public function createSuperAdmin(Request $request): RedirectResponse
  {
    if (! $this->canAccess()) {
      abort(403);
    }

    $data = $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'email', 'max:255'],
      'password' => ['required', 'confirmed', Password::defaults()],
    ]);

    try {
      SiteInstaller::createSuperAdmin(
        name: $data['name'],
        email: $data['email'],
        password: $data['password'],
      );
    } catch (\Throwable $exception) {
      return $this->backWithInput('error', 'Création impossible', $exception->getMessage(), onlyAdmin: true);
    }

    return $this->back('success', 'Super administrateur créé', 'Le compte ' . $data['email'] . ' peut accéder au panneau d\'administration.');
  }

  /**
   * Vérifie l'accès à la page d'installation.
   */
  private function canAccess(): bool
  {
    if (SiteDeploymentState::requiresInstallation()) {
      return true;
    }

    $user = Auth::user();

    return $user !== null && $user->is_super_admin;
  }

  /**
   * Redirige vers la page d'installation avec un message flash.
   */
  private function back(string $type, string $title, string $body): RedirectResponse
  {
    $url = Auth::check() && ! SiteDeploymentState::requiresInstallation()
      ? \App\Filament\Pages\SiteInstallation::getUrl()
      : SiteDeploymentState::installationPath();

    return redirect()
      ->to($url)
      ->with('installation_notice', compact('type', 'title', 'body'));
  }

  /**
   * Redirige en conservant les champs saisis.
   *
   * @param array<int, string> $onlyAdmin
   */
  private function backWithInput(string $type, string $title, string $body, bool $onlyAdmin = false): RedirectResponse
  {
    $url = Auth::check() && ! SiteDeploymentState::requiresInstallation()
      ? \App\Filament\Pages\SiteInstallation::getUrl()
      : SiteDeploymentState::installationPath();

    return redirect()
      ->to($url)
      ->with('installation_notice', compact('type', 'title', 'body'))
      ->withInput();
  }
}
