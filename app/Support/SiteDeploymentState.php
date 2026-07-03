<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Schema;

/**
 * Gère l'état de déploiement / installation initiale du site COMCO.
 */
class SiteDeploymentState
{
  private const MARKER_PATH = 'app/comco-installed.json';

  /**
   * Indique si le site a été finalisé après la première installation.
   */
  public static function isInstalled(): bool
  {
    $markerPath = storage_path(self::MARKER_PATH);

    if (! is_file($markerPath)) {
      return false;
    }

    $payload = json_decode((string) file_get_contents($markerPath), true);

    return is_array($payload) && ! empty($payload['installed_at']);
  }

  /**
   * Indique si le site public doit afficher la page de déploiement.
   */
  public static function isDeploying(): bool
  {
    if (self::isInstalled()) {
      return false;
    }

    if (app()->isLocal()) {
      return false;
    }

    return true;
  }

  /**
   * Indique si le panneau admin doit afficher le mode installation.
   */
  public static function requiresInstallation(): bool
  {
    if (self::isInstalled()) {
      return false;
    }

    if (app()->isLocal()) {
      return self::hasIncompleteSetup();
    }

    return true;
  }

  /**
   * Indique si la base de données est joignable et migrée (table users).
   */
  public static function databaseIsReady(): bool
  {
    try {
      return Schema::hasTable('users');
    } catch (\Throwable) {
      return false;
    }
  }

  /**
   * Exécute une requête utilisateur sans lever d'exception si la base n'est pas prête.
   *
   * @template T
   * @param callable(): T $callback Requête à exéuter
   * @param T $default Valeur par défaut en cas d'échec
   * @return T
   */
  public static function whenDatabaseReady(callable $callback, mixed $default = null): mixed
  {
    if (! self::databaseIsReady()) {
      return $default;
    }

    try {
      return $callback();
    } catch (\Throwable) {
      return $default;
    }
  }

  /**
   * Indique si au moins un super administrateur existe.
   */
  public static function hasSuperAdmin(): bool
  {
    return (bool) self::whenDatabaseReady(
      fn () => User::query()->where('is_super_admin', true)->exists(),
      false,
    );
  }

  /**
   * Marque le site comme installé et accessible au public.
   */
  public static function markAsInstalled(): void
  {
    $markerPath = storage_path(self::MARKER_PATH);
    $directory = dirname($markerPath);

    if (! is_dir($directory)) {
      mkdir($directory, 0755, true);
    }

    file_put_contents($markerPath, json_encode([
      'installed_at' => now()->toIso8601String(),
      'app_url' => config('app.url'),
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    EnvConfigurator::set([
      'COMCO_DEPLOYING' => 'false',
    ]);
  }

  /**
   * Vérifie si l'environnement local semble déjà configuré sans marqueur.
   */
  public static function hasIncompleteSetup(): bool
  {
    if (! self::databaseIsReady()) {
      return true;
    }

    return ! self::hasSuperAdmin();
  }

  /**
   * Marque automatiquement l'installation en local si le site est déjà prêt.
   */
  public static function syncLocalInstallationMarker(): void
  {
    if (! app()->isLocal() || self::isInstalled()) {
      return;
    }

    if (! self::hasIncompleteSetup()) {
      self::markAsInstalled();
    }
  }
}
