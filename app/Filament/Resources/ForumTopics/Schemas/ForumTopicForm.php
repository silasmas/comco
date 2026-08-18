<?php

namespace App\Filament\Resources\ForumTopics\Schemas;

use App\Support\ForumSlug;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

/**
 * Schéma de formulaire des sujets du forum.
 */
class ForumTopicForm
{
    /**
     * Configure le formulaire d'un sujet de forum.
     *
     * @param  Schema  $schema  Schéma Filament
     * @return Schema Schéma configuré
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Titre')
                    ->required()
                    ->helperText('Titre du sujet affiché dans la liste du forum.')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (?string $state, callable $set, ?string $old): void {
                        if ($old !== null) {
                            return;
                        }

                        if (! filled($state)) {
                            return;
                        }

                        $set('slug', ForumSlug::fromTitle($state));
                    }),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('Identifiant d’URL. Rempli automatiquement à partir du titre à la création.'),
                Select::make('category')
                    ->label('Catégorie')
                    ->options(config('forum.categories', []))
                    ->required()
                    ->helperText('Rubrique du forum dans laquelle classer le sujet.'),
                TextInput::make('author_name')
                    ->label('Auteur')
                    ->required()
                    ->helperText('Nom public de l’auteur du sujet.'),
                TextInput::make('author_email')
                    ->label('Email auteur')
                    ->email()
                    ->required()
                    ->helperText('Email de contact de l’auteur (non affiché publiquement en général).'),
                Textarea::make('body')
                    ->label('Contenu')
                    ->required()
                    ->helperText('Message initial du sujet.')
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Statut')
                    ->options(config('forum.statuses.topic', []))
                    ->required()
                    ->helperText('Approuvé = visible ; en attente / rejeté = modération.'),
                TextInput::make('views')
                    ->label('Vues')
                    ->numeric()
                    ->default(0)
                    ->helperText('Compteur de consultations (modifiable si besoin de correction).'),
            ]);
    }
}
