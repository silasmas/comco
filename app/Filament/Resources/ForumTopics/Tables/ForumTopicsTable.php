<?php

namespace App\Filament\Resources\ForumTopics\Tables;

use App\Filament\Support\ModerationTableActions;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ForumTopicsTable
{
  public static function configure(Table $table): Table
  {
    return $table
      ->defaultSort('created_at', 'desc')
      ->columns([
        TextColumn::make('title')
          ->label('Titre')
          ->searchable()
          ->limit(50),
        TextColumn::make('category')
          ->label('Catégorie')
          ->formatStateUsing(fn (?string $state): string => config("forum.categories.{$state}", $state ?? '')),
        TextColumn::make('author_name')
          ->label('Auteur')
          ->searchable(),
        TextColumn::make('status')
          ->label('Statut')
          ->badge()
          ->formatStateUsing(fn (?string $state): string => config("forum.statuses.topic.{$state}", $state ?? ''))
          ->color(fn (?string $state): string => match ($state) {
            'approved' => 'success',
            'closed' => 'gray',
            'rejected' => 'danger',
            default => 'warning',
          }),
        TextColumn::make('approved_replies_count')
          ->label('Réponses')
          ->counts('approvedReplies'),
        TextColumn::make('views')
          ->label('Vues')
          ->sortable(),
        TextColumn::make('created_at')
          ->label('Créé le')
          ->dateTime('d/m/Y H:i')
          ->sortable(),
      ])
      ->filters([
        SelectFilter::make('status')
          ->label('Statut')
          ->options(config('forum.statuses.topic', [])),
        SelectFilter::make('category')
          ->label('Catégorie')
          ->options(config('forum.categories', [])),
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
