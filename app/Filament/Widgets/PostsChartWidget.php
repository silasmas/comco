<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Filament\Widgets\Concerns\InteractsWithMonthlyTrend;
use Filament\Widgets\ChartWidget;

/**
 * Graphique mensuel des articles publiés.
 */
class PostsChartWidget extends ChartWidget
{
  use InteractsWithMonthlyTrend;

  protected static ?int $sort = 2;

  protected ?string $heading = 'Actualités';

  protected ?string $description = 'Articles créés par mois';

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
    $trend = $this->monthlyTrend(Post::class);

    return [
      'datasets' => [
        [
          'label' => 'Articles',
          'data' => $trend['data'],
          'backgroundColor' => '#003DA5',
        ],
      ],
      'labels' => $trend['labels'],
    ];
  }
}
