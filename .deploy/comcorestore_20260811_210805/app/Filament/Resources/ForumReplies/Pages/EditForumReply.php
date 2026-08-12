<?php

namespace App\Filament\Resources\ForumReplies\Pages;

use App\Filament\Resources\ForumReplies\ForumReplyResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\Pages\ComcoEditRecord;

class EditForumReply extends ComcoEditRecord
{
    protected static string $resource = ForumReplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Approuver')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->visible(fn (): bool => $this->record->status !== 'approved')
                ->action(fn () => $this->record->update(['status' => 'approved']))
                ->after(fn () => $this->refreshFormData(['status'])),
            Action::make('reject')
                ->label('Rejeter')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->visible(fn (): bool => $this->record->status !== 'rejected')
                ->action(fn () => $this->record->update(['status' => 'rejected']))
                ->after(fn () => $this->refreshFormData(['status'])),
            DeleteAction::make(),
        ];
    }
}
