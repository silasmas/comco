<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use App\Filament\Widgets\Concerns\InteractsWithMonthlyTrend;
use Filament\Widgets\ChartWidget;

/**
 * Graphique mensuel des messages de contact reçus.
 */
class ContactMessagesChartWidget extends ChartWidget
{
  use InteractsWithMonthlyTrend;

  protected static ?int $sort = 3;

  protected ?string $heading = 'Messages contact';

  protected ?string $description = 'Messages reçus par mois';

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
    $trend = $this->monthlyTrend(ContactMessage::class);

    return [
      'datasets' => [
        [
          'label' => 'Messages',
          'data' => $trend['data'],
          'borderColor' => '#3680b3',
          'backgroundColor' => 'rgba(54, 128, 179, 0.15)',
          'fill' => true,
        ],
      ],
      'labels' => $trend['labels'],
    ];
  }
}
