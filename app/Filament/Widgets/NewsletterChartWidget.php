<?php

namespace App\Filament\Widgets;

use App\Models\NewsletterSubscriber;
use App\Filament\Widgets\Concerns\InteractsWithMonthlyTrend;
use Filament\Widgets\ChartWidget;

/**
 * Graphique mensuel des inscriptions newsletter.
 */
class NewsletterChartWidget extends ChartWidget
{
  use InteractsWithMonthlyTrend;

  protected static ?int $sort = 6;

  protected ?string $heading = 'Newsletter';

  protected ?string $description = 'Inscriptions par mois';

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
    $trend = $this->monthlyTrend(NewsletterSubscriber::class);

    return [
      'datasets' => [
        [
          'label' => 'Abonnés',
          'data' => $trend['data'],
          'backgroundColor' => '#36b36a',
        ],
      ],
      'labels' => $trend['labels'],
    ];
  }
}
