<?php

namespace App\Filament\Resources\ForumReplies\Pages;

use App\Filament\Resources\ForumReplies\ForumReplyResource;
use App\Models\ForumReply;
use App\Filament\Resources\Pages\ComcoListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListForumReplies extends ComcoListRecords
{
    protected static string $resource = ForumReplyResource::class;

    /**
     * Onglets de filtrage rapide par statut de modération.
     *
     * @return array<string, Tab>
     */
    public function getTabs(): array
    {
        return [
            'pending' => Tab::make('En attente')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', 'pending'))
                ->badge(fn (): int => ForumReply::query()->where('status', 'pending')->count()),
            'approved' => Tab::make('Approuvées')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', 'approved')),
            'rejected' => Tab::make('Rejetées')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', 'rejected')),
            'all' => Tab::make('Toutes'),
        ];
    }
}
