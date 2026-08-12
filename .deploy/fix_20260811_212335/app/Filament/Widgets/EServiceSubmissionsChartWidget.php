<?php

namespace App\Filament\Widgets;

use App\Models\EServiceSubmission;
use App\Filament\Widgets\Concerns\InteractsWithMonthlyTrend;
use Filament\Widgets\ChartWidget;

/**
 * Graphique mensuel des soumissions e-services.
 */
class EServiceSubmissionsChartWidget extends ChartWidget
{
  use InteractsWithMonthlyTrend;

  protected static ?int $sort = 4;

  protected ?string $heading = 'E-services';

  protected ?string $description = 'Dossiers transmis par mois';

  protected int|string|array $columnSpan = 1;

  protected function getType(): string
  {
    return 'bar';
  }

  /**
   * @return array<string, mixed>
   */
  protected function getData(): array
  {
    $trend = $this->monthlyTrend(EServiceSubmission::class);

    return [
      'datasets' => [
        [
          'label' => 'Soumissions',
          'data' => $trend['data'],
          'backgroundColor' => '#fdd428',
        ],
      ],
      'labels' => $trend['labels'],
    ];
  }
}
