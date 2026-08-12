<?php

namespace App\Filament\Widgets\Concerns;

/**
 * Construit des séries mensuelles pour les graphiques du tableau de bord.
 */
trait InteractsWithMonthlyTrend
{
  /**
   * Compte les enregistrements créés par mois sur une période glissante.
   *
   * @param class-string<\Illuminate\Database\Eloquent\Model> $modelClass Classe Eloquent
   * @param int $months Nombre de mois affichés
   * @return array{labels: list<string>, data: list<int>}
   */
  protected function monthlyTrend(string $modelClass, int $months = 6): array
  {
    $labels = [];
    $data = [];

    for ($index = $months - 1; $index >= 0; $index--) {
      $date = now()->subMonths($index);
      $labels[] = $date->translatedFormat('M Y');
      $data[] = $modelClass::query()
        ->whereYear('created_at', $date->year)
        ->whereMonth('created_at', $date->month)
        ->count();
    }

    return [
      'labels' => $labels,
      'data' => $data,
    ];
  }
}
