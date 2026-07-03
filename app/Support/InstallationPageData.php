<?php

namespace App\Support;

/**
 * Prépare les données affichées sur la page d'installation production.
 */
class InstallationPageData
{
  /**
   * Retourne les variables pour la vue d'installation.
   *
   * @return array<string, mixed>
   */
  public static function forView(): array
  {
    return [
      'adminPrefix' => SiteDeploymentState::adminPathPrefix(),
      'requiresInstallation' => SiteDeploymentState::requiresInstallation(),
      'notice' => session()->pull('installation_notice'),
      'configValues' => [
        'app_url' => old('app_url', config('app.url')),
        'app_env' => old('app_env', config('app.env')),
        'app_debug' => old('app_debug', config('app.debug') ? 'true' : 'false'),
        'institution_full_name' => old('institution_full_name', config('institution.fullName')),
        'institution_name' => old('institution_name', config('institution.name')),
        'institution_email' => old('institution_email', config('institution.contact.email')),
        'mail_from_address' => old('mail_from_address', config('mail.from.address')),
        'mail_from_name' => old('mail_from_name', config('mail.from.name')),
      ],
      'adminDefaults' => [
        'name' => old('name', 'Super Admin'),
        'email' => old('email', 'superadmin@comco.gouv.cd'),
      ],
    ];
  }
}
