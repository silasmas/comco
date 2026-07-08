<?php

namespace App\Filament\Resources\EServiceDefinitions\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Tableau de liste des définitions e-services.
 */
class EServiceDefinitionsTable
{
    /**
     * Configure le tableau Filament des e-services.
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
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),
                TextColumn::make('sort_order')
                    ->label('Ordre')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
