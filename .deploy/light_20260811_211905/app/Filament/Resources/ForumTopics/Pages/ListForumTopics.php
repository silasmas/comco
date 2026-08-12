<?php

namespace App\Filament\Resources\ForumTopics\Pages;

use App\Filament\Resources\ForumTopics\ForumTopicResource;
use Filament\Actions\CreateAction;
use App\Filament\Resources\Pages\ComcoListRecords;

class ListForumTopics extends ComcoListRecords
{
    protected static string $resource = ForumTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
