<?php

namespace App\Filament\Resources\NavigationItems\Tables;

use App\Models\NavigationItem;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

/**
 * Tableau de liste des éléments de navigation.
 */
class NavigationItemsTable
{
    /**
     * Configure le tableau Filament de navigation.
     *
     * @param  Table  $table  Table Filament
     * @return Table Table configurée
     */
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('label')
                    ->label('Libellé')
                    ->searchable(),
                TextColumn::make('menu')
                    ->label('Menu')
                    ->formatStateUsing(fn (string $state): string => NavigationItem::menuLabels()[$state] ?? $state)
                    ->sortable(),
                TextColumn::make('link_type')
                    ->label('Type')
                    ->formatStateUsing(fn (string $state): string => NavigationItem::linkTypeLabels()[$state] ?? $state),
                TextColumn::make('parent.label')
                    ->label('Parent')
                    ->placeholder('—'),
                TextColumn::make('sort_order')
                    ->label('Ordre')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('menu')
                    ->label('Menu')
                    ->options(NavigationItem::menuLabels()),
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
