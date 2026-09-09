<?php

namespace App\Filament\Resources\MobileApps\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

/**
 * Tableau Filament des applications APK.
 */
class MobileAppsTable
{
  /**
   * Configure le tableau.
   *
   * @param  Table  $table  Table Filament
   * @return Table Table configurée
   */
  public static function configure(Table $table): Table
  {
    return $table
      ->defaultSort('created_at', 'desc')
      ->columns([
        TextColumn::make('title')
          ->label('Titre')
          ->searchable()
          ->sortable(),
        TextColumn::make('version')
          ->label('Version')
          ->placeholder('—')
          ->sortable(),
        TextColumn::make('slug')
          ->label('Slug')
          ->copyable()
          ->toggleable(),
        TextColumn::make('download_count')
          ->label('Téléchargements')
          ->sortable()
          ->alignCenter(),
        TextColumn::make('file_size')
          ->label('Taille')
          ->formatStateUsing(fn ($state, $record): string => $record->humanFileSize() ?? '—'),
        ToggleColumn::make('is_active')
          ->label('Actif')
          ->onColor('success')
          ->offColor('danger'),
        TextColumn::make('updated_at')
          ->label('Mis à jour')
          ->dateTime('d/m/Y H:i')
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->recordActions([
        EditAction::make(),
      ])
      ->toolbarActions([
        BulkActionGroup::make([
          DeleteBulkAction::make(),
        ]),
      ]);
  }
}
