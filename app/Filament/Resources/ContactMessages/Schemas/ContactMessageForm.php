<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

/**
 * Schéma de consultation / traitement d'un message de contact.
 */
class ContactMessageForm
{
    /**
     * Configure le formulaire d'un message de contact.
     *
     * @param  Schema  $schema  Schéma Filament
     * @return Schema Schéma configuré
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nom')
                    ->disabled()
                    ->helperText('Saisi par l’expéditeur via le formulaire public.'),
                TextInput::make('email')
                    ->label('Email')
                    ->disabled()
                    ->helperText('Adresse de réponse de l’expéditeur.'),
                Textarea::make('message')
                    ->label('Message')
                    ->disabled()
                    ->helperText('Contenu du message reçu (lecture seule).')
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Statut')
                    ->options(config('forum.submissionStatuses', []))
                    ->required()
                    ->helperText('Marquez comme traité après prise en charge.'),
            ]);
    }
}
