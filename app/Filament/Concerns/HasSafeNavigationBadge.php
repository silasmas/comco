<?php

namespace App\Filament\Concerns;

use App\Support\SiteDeploymentState;
use Illuminate\Database\Eloquent\Model;

/**
 * Badge de navigation Filament sans requête SQL si la table n'existe pas encore.
 */
trait HasSafeNavigationBadge
{
  /**
   * Compte les enregistrements pour le badge de navigation.
   *
   * @param class-string<Model> $modelClass Classe Eloquent du modèle
   * @param array<string, mixed> $conditions Conditions where supplémentaires
   */
  protected static function countNavigationBadge(string $modelClass, array $conditions = []): ?string
  {
    $count = SiteDeploymentState::whenModelTableReady($modelClass, function () use ($modelClass, $conditions): int {
      $query = $modelClass::query();

      foreach ($conditions as $column => $value) {
        $query->where($column, $value);
      }

      return $query->count();
    }, 0);

    return $count > 0 ? (string) $count : null;
  }
}
