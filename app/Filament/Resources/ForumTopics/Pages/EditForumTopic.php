<?php

namespace App\Filament\Resources\ForumTopics\Pages;

use App\Filament\Resources\ForumTopics\ForumTopicResource;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\Pages\ComcoEditRecord;

class EditForumTopic extends ComcoEditRecord
{
    protected static string $resource = ForumTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
