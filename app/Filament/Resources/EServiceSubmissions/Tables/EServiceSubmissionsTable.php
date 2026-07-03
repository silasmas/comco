<?php

namespace App\Filament\Resources\EServiceSubmissions\Tables;

use App\Filament\Support\ModerationTableActions;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EServiceSubmissionsTable
{
  public static function configure(Table $table): Table
  {
    return $table
      ->defaultSort('created_at', 'desc')
      ->columns([
        TextColumn::make('service_slug')
          ->label('Service')
          ->formatStateUsing(fn (?string $state): string => config("e-services.{$state}.label", $state ?? ''))
          ->searchable(),
        TextColumn::make('name')
          ->label('Nom')
          ->searchable(),
        TextColumn::make('email')
          ->label('Email')
          ->searchable(),
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
        SelectFilter::make('service_slug')
          ->label('Service')
          ->options(collect(config('e-services', []))->mapWithKeys(
            fn (array $service, string $slug): array => [$slug => $service['label'] ?? $slug]
          )->all()),
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
