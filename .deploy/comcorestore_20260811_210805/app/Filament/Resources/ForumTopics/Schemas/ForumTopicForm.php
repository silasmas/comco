<?php

namespace App\Filament\Resources\ForumTopics\Schemas;

use App\Support\ForumSlug;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ForumTopicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Titre')
                    ->required()
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
                    ->unique(ignoreRecord: true),
                Select::make('category')
                    ->label('Catégorie')
                    ->options(config('forum.categories', []))
                    ->required(),
                TextInput::make('author_name')
                    ->label('Auteur')
                    ->required(),
                TextInput::make('author_email')
                    ->label('Email auteur')
                    ->email()
                    ->required(),
                Textarea::make('body')
                    ->label('Contenu')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Statut')
                    ->options(config('forum.statuses.topic', []))
                    ->required(),
                TextInput::make('views')
                    ->label('Vues')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
