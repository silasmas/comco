<?php

namespace App\Filament\Resources\ForumReplies\Tables;

use App\Filament\Support\ModerationTableActions;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ForumRepliesTable
{
  public static function configure(Table $table): Table
  {
    return $table
      ->defaultSort('created_at', 'desc')
      ->modifyQueryUsing(fn ($query) => $query->with('topic'))
      ->columns([
        TextColumn::make('topic.title')
          ->label('Sujet')
          ->searchable()
          ->limit(40)
          ->url(fn ($record): ?string => $record->topic
            ? route('forum.show', $record->topic->slug)
            : null)
          ->openUrlInNewTab(),
        TextColumn::make('author_name')
          ->label('Auteur')
          ->searchable(),
        TextColumn::make('body')
          ->label('Réponse')
          ->limit(80)
          ->wrap(),
        TextColumn::make('status')
          ->label('Statut')
          ->badge()
          ->formatStateUsing(fn (?string $state): string => config("forum.statuses.reply.{$state}", $state ?? ''))
          ->color(fn (?string $state): string => match ($state) {
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'warning',
          }),
        TextColumn::make('created_at')
          ->label('Reçue le')
          ->dateTime('d/m/Y H:i')
          ->sortable(),
      ])
      ->filters([
        SelectFilter::make('status')
          ->label('Statut')
          ->options(config('forum.statuses.reply', [])),
      ])
      ->recordActions([
        ModerationTableActions::approveRecord(),
        ModerationTableActions::rejectRecord(),
        EditAction::make(),
      ])
      ->toolbarActions([
        BulkActionGroup::make(ModerationTableActions::forumBulkActions()),
      ]);
  }
}
