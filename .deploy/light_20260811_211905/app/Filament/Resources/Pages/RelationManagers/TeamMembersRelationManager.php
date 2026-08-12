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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * Gestion des profils d'équipe attachés à une page CMS.
 */
class TeamMembersRelationManager extends RelationManager
{
    protected static string $relationship = 'teamMembers';

    protected static ?string $title = 'Équipe & partenaires';

    /**
     * Affiche l'onglet uniquement pour les pages au gabarit alumni.
     *
     * @param  Page  $ownerRecord  Page CMS parente
     * @return bool True si l'onglet est visible
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        if (! $ownerRecord instanceof Page) {
            return false;
        }

        return pageTemplate($ownerRecord->section ?? '', $ownerRecord->slug, $ownerRecord->template) === 'alumni';
    }

    /**
     * Configure le formulaire d'un profil d'équipe.
     *
     * @param  Schema  $schema  Schéma Filament
     * @return Schema Schéma configuré
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nom')
                    ->required(),
                TextInput::make('role')
                    ->label('Fonction / rôle'),
                Textarea::make('text')
                    ->label('Description')
                    ->rows(4)
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->label('Photo')
                    ->image()
                    ->directory('pages/team')
                    ->disk('public'),
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
     * Configure le tableau des profils d'équipe.
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
                    ->label('Photo')
                    ->disk('public')
                    ->height(56)
                    ->width(56),
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable(),
                TextColumn::make('role')
                    ->label('Fonction'),
                TextColumn::make('sort_order')
                    ->label('Ordre')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        if (! empty($data['image'])) {
                            $data['image_source'] = 'storage';
                        }

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
