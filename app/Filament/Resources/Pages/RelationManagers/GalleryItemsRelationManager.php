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
     * Titre de l'onglet selon le gabarit (couverture Présentation ou galerie).
     *
     * @param  Model  $ownerRecord  Page CMS parente
     * @param  string  $pageClass  Classe de page Filament
     * @return string Titre affiché
     */
    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        if (
            $ownerRecord instanceof Page
            && pageTemplate($ownerRecord->section ?? '', $ownerRecord->slug, $ownerRecord->template) === 'presentation-hub'
            && ($ownerRecord->slug ?? '') === 'presentation'
        ) {
            return 'Image de couverture';
        }

        return 'Galerie photo';
    }

    /**
     * Affiche l'onglet pour la galerie ou la page Présentation (image de couverture).
     *
     * @param  Page  $ownerRecord  Page CMS parente
     * @return bool True si l'onglet est visible
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        if (! $ownerRecord instanceof Page) {
            return false;
        }

        $template = pageTemplate($ownerRecord->section ?? '', $ownerRecord->slug, $ownerRecord->template);

        if ($template === 'gallery') {
            return true;
        }

        return $template === 'presentation-hub' && ($ownerRecord->slug ?? '') === 'presentation';
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
                    ->helperText('Sur Présentation, la première image active sert de couverture (affichée au-dessus du texte).')
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
