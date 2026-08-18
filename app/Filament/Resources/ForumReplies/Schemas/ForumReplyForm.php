<?php

namespace App\Filament\Resources\ForumReplies\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

/**
 * Schéma de modération d'une réponse du forum.
 */
class ForumReplyForm
{
    /**
     * Configure le formulaire d'une réponse de forum.
     *
     * @param  Schema  $schema  Schéma Filament
     * @return Schema Schéma configuré
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('topic_title')
                    ->label('Sujet')
                    ->afterStateHydrated(function (TextInput $component, $record): void {
                        $component->state($record?->topic?->title);
                    })
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText('Sujet auquel cette réponse est rattachée.'),
                TextInput::make('author_name')
                    ->label('Auteur')
                    ->disabled()
                    ->helperText('Nom public de l’auteur de la réponse.'),
                TextInput::make('author_email')
                    ->label('Email')
                    ->disabled()
                    ->helperText('Email de l’auteur (modération).'),
                Textarea::make('body')
                    ->label('Réponse')
                    ->disabled()
                    ->helperText('Contenu de la réponse (lecture seule).')
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Statut')
                    ->options(config('forum.statuses.reply', []))
                    ->required()
                    ->helperText('Approuvé = visible sur le forum ; sinon masqué / en attente.'),
            ]);
    }
}
