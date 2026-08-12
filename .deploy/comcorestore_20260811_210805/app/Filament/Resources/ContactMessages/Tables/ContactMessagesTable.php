<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use App\Filament\Support\ModerationTableActions;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContactMessagesTable
{
  public static function configure(Table $table): Table
  {
    return $table
      ->defaultSort('created_at', 'desc')
      ->columns([
        TextColumn::make('name')
          ->label('Nom')
          ->searchable(),
        TextColumn::make('email')
          ->label('Email')
          ->searchable(),
        TextColumn::make('message')
          ->label('Message')
          ->limit(60),
        TextColumn::make('status')
          ->label('Statut')
          ->badge()
          ->formatStateUsing(fn (?string $state): string => config("forum.submissionStatuses.{$state}", $state ?? ''))
          ->color(fn (?string $state): string => match ($state) {
            'resolved' => 'success',
            'rejected' => 'danger',
            'in_progress' => 'info',
            default => 'warning',
          }),
        TextColumn::make('created_at')
          ->label('Reçu le')
          ->dateTime('d/m/Y H:i')
          ->sortable(),
      ])
      ->filters([
        SelectFilter::make('status')
          ->label('Statut')
          ->options(config('forum.submissionStatuses', [])),
      ])
      ->recordActions([
        ModerationTableActions::resolveRecord(),
        ModerationTableActions::rejectRecord(),
        EditAction::make(),
      ])
      ->toolbarActions([
        BulkActionGroup::make(ModerationTableActions::submissionBulkActions()),
      ]);
  }
}
