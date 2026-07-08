<?php

namespace App\Filament\Resources\SiteBlocks\Tables;

use App\Models\SiteBlock;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

/**
 * Tableau de liste des blocs dynamiques de la page d'accueil.
 */
class SiteBlocksTable
{
    /**
     * Configure le tableau Filament des blocs d'accueil.
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
                    ->searchable()
                    ->limit(40),
                TextColumn::make('block_type')
                    ->label('Type')
                    ->formatStateUsing(fn (string $state): string => SiteBlock::blockTypeLabels()[$state] ?? $state)
                    ->sortable(),
                TextColumn::make('payload.title')
                    ->label('Titre')
                    ->state(fn (SiteBlock $record): string => (string) ($record->payload['title'] ?? $record->payload['name'] ?? ''))
                    ->limit(40),
                TextColumn::make('sort_order')
                    ->label('Ordre')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('block_type')
                    ->label('Type')
                    ->options(SiteBlock::blockTypeLabels()),
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
