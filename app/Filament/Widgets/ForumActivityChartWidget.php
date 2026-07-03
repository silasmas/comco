<?php

namespace App\Filament\Widgets;

use App\Models\ForumTopic;
use App\Models\ForumReply;
use App\Filament\Widgets\Concerns\InteractsWithMonthlyTrend;
use Filament\Widgets\ChartWidget;

/**
 * Graphique mensuel de l'activité du forum (sujets et réponses).
 */
class ForumActivityChartWidget extends ChartWidget
{
  use InteractsWithMonthlyTrend;

  protected static ?int $sort = 5;

  protected ?string $heading = 'Forum';

  protected ?string $description = 'Sujets et réponses par mois';

  protected int|string|array $columnSpan = 1;

  protected function getType(): string
  {
    return 'line';
  }

  /**
   * @return array<string, mixed>
   */
  protected function getData(): array
  {
    $topics = $this->monthlyTrend(ForumTopic::class);
    $replies = $this->monthlyTrend(ForumReply::class);

    return [
      'datasets' => [
        [
          'label' => 'Sujets',
          'data' => $topics['data'],
          'borderColor' => '#003DA5',
          'backgroundColor' => 'rgba(0, 61, 165, 0.1)',
          'fill' => false,
        ],
        [
          'label' => 'Réponses',
          'data' => $replies['data'],
          'borderColor' => '#b33641',
          'backgroundColor' => 'rgba(179, 54, 65, 0.1)',
          'fill' => false,
        ],
      ],
      'labels' => $topics['labels'],
    ];
  }
}
