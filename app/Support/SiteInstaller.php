<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;

/**
 * Opérations d'installation et de mise en production du site COMCO.
 */
class SiteInstaller
{
  /**
   * Exécute les migrations de base de données.
   *
   * @return string Sortie console de la commande migrate
   */
  public static function runMigrations(): string
  {
    Artisan::call('migrate', ['--force' => true]);

    return trim(Artisan::output()) ?: 'Migrations exécutées avec succès.';
  }

  /**
   * Crée le lien symbolique public/storage.
   *
   * @return string Message décrivant le résultat
   */
  public static function createStorageLink(): string
  {
    if (file_exists(public_path('storage'))) {
      return 'Le lien symbolique public/storage existe déjà.';
    }

    Artisan::call('storage:link');

    return trim(Artisan::output()) ?: 'Lien storage créé avec succès.';
  }

  /**
   * Met en cache la configuration, les routes et les vues.
   *
   * @return string Message de confirmation
   */
  public static function optimizeApplication(): string
  {
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');

    return 'Configuration, routes et vues mises en cache pour la production.';
  }

  /**
   * Crée ou met à jour un super administrateur Filament.
   *
   * @param string $name Nom affiché
   * @param string $email Adresse email de connexion
   * @param string $password Mot de passe en clair (sera hashé)
   * @return User Utilisateur créé ou mis à jour
   */
  public static function createSuperAdmin(string $name, string $email, string $password): User
  {
    return User::query()->updateOrCreate(
      ['email' => $email],
      [
        'name' => $name,
        'password' => $password,
        'is_super_admin' => true,
        'email_verified_at' => now(),
      ],
    );
  }

  /**
   * Applique la configuration de base dans le fichier .env.
   *
   * @param array<string, string> $values Variables à enregistrer
   * @return array<string, string> Clés mises à jour
   */
  public static function applyBaseConfiguration(array $values): array
  {
    $envValues = [
      'APP_URL' => $values['app_url'] ?? null,
      'APP_ENV' => $values['app_env'] ?? null,
      'APP_DEBUG' => $values['app_debug'] ?? null,
      'INSTITUTION_EMAIL' => $values['institution_email'] ?? null,
      'INSTITUTION_FULL_NAME' => $values['institution_full_name'] ?? null,
      'INSTITUTION_NAME' => $values['institution_name'] ?? null,
      'MAIL_FROM_ADDRESS' => $values['mail_from_address'] ?? null,
      'MAIL_FROM_NAME' => $values['mail_from_name'] ?? null,
    ];

    $updated = EnvConfigurator::set(array_filter($envValues, fn ($value) => $value !== null && $value !== ''));

    if ($updated !== []) {
      Artisan::call('config:clear');
    }

    return $updated;
  }
}
