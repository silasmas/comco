<?php

namespace App\Filament\Resources\CoordinationMembers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

/**
 * Tableau Filament des fiches Coordination.
 */
class CoordinationMembersTable
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
      ->defaultSort('sort_order')
      ->reorderable('sort_order')
      ->columns([
        ImageColumn::make('image')
          ->label('Image')
          ->disk('public')
          ->height(48)
          ->width(48),
        TextColumn::make('title')
          ->label('Titre')
          ->searchable()
          ->sortable(),
        TextColumn::make('summary')
          ->label('Résumé')
          ->limit(60)
          ->toggleable(),
        TextColumn::make('sort_order')
          ->label('Ordre')
          ->sortable(),
        ToggleColumn::make('is_active')
          ->label('Actif')
          ->onColor('success')
          ->offColor('danger'),
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
