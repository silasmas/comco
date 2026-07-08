<?php

namespace App\Filament\Resources\Pages\RelationManagers;

use App\Models\Page;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * Gestion des documents juridiques attachés à une page CMS.
 */
class LegalDocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'legalDocuments';

    protected static ?string $title = 'Documents juridiques';

    /**
     * Affiche l'onglet uniquement pour les pages au gabarit legal.
     *
     * @param  Page  $ownerRecord  Page CMS parente
     * @return bool True si l'onglet est visible
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        if (! $ownerRecord instanceof Page) {
            return false;
        }

        return pageTemplate($ownerRecord->section ?? '', $ownerRecord->slug, $ownerRecord->template) === 'legal';
    }

    /**
     * Configure le formulaire d'un document juridique.
     *
     * @param  Schema  $schema  Schéma Filament
     * @return Schema Schéma configuré
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Titre')
                    ->required(),
                Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->columnSpanFull(),
                FileUpload::make('filename')
                    ->label('Fichier PDF')
                    ->acceptedFileTypes(['application/pdf'])
                    ->directory('pages/legal')
                    ->disk('public')
                    ->required(),
                TextInput::make('sort_order')
                    ->label('Ordre')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Toggle::make('is_active')
                    ->label('Actif')
                    ->default(true)
                    ->required(),
            ]);
    }

    /**
     * Configure le tableau des documents juridiques.
     *
     * @param  Table  $table  Table Filament
     * @return Table Table configurée
     */
    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('title')
                    ->label('Titre')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('filename')
                    ->label('Fichier')
                    ->limit(30),
                TextColumn::make('sort_order')
                    ->label('Ordre')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
