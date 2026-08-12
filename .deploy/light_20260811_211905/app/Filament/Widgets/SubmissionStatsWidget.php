<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use App\Models\EServiceSubmission;
use App\Models\ForumReply;
use App\Models\ForumTopic;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SubmissionStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

  protected function getStats(): array
    {
        return [
            Stat::make('E-services en attente', EServiceSubmission::query()->where('status', 'pending')->count())
                ->description('Soumissions à traiter')
                ->color('warning'),
            Stat::make('Messages contact', ContactMessage::query()->where('status', 'pending')->count())
                ->description('Messages non traités')
                ->color('warning'),
            Stat::make('Sujets forum', ForumTopic::query()->where('status', 'pending')->count())
                ->description('Sujets en modération')
                ->color('info'),
            Stat::make('Réponses forum', ForumReply::query()->where('status', 'pending')->count())
                ->description('Réponses en modération')
                ->color('info'),
        ];
    }
}
