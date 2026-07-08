<?php

namespace App\Filament\Resources\Pages\RelationManagers;

use App\Models\Page;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * Gestion des images de galerie attachées à une page CMS.
 */
class GalleryItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'galleryItems';

    protected static ?string $title = 'Galerie photo';

    /**
     * Affiche l'onglet uniquement pour les pages au gabarit galerie.
     *
     * @param  Page  $ownerRecord  Page CMS parente
     * @return bool True si l'onglet est visible
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        if (! $ownerRecord instanceof Page) {
            return false;
        }

        return pageTemplate($ownerRecord->section ?? '', $ownerRecord->slug, $ownerRecord->template) === 'gallery';
    }

    /**
     * Configure le formulaire d'une image de galerie.
     *
     * @param  Schema  $schema  Schéma Filament
     * @return Schema Schéma configuré
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image')
                    ->label('Image')
                    ->image()
                    ->directory('pages/gallery')
                    ->disk('public')
                    ->required(),
                TextInput::make('caption')
                    ->label('Légende')
                    ->maxLength(255),
                TextInput::make('sort_order')
                    ->label('Ordre')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->required(),
            ]);
    }

    /**
     * Configure le tableau des images de galerie.
     *
     * @param  Table  $table  Table Filament
     * @return Table Table configurée
     */
    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('image')
                    ->label('Aperçu')
                    ->disk('public')
                    ->height(56)
                    ->width(56),
                TextColumn::make('caption')
                    ->label('Légende')
                    ->limit(40),
                TextColumn::make('sort_order')
                    ->label('Ordre')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['image_source'] = 'storage';

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
