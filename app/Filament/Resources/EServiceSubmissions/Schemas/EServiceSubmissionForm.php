<?php

namespace App\Filament\Resources\EServiceSubmissions\Schemas;

use App\Support\EServiceRegistry;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

/**
 * Schéma de consultation d'une soumission e-service.
 */
class EServiceSubmissionForm
{
    /**
     * Configure le formulaire d'une soumission e-service.
     *
     * @param  Schema  $schema  Schéma Filament
     * @return Schema Schéma configuré
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('service_slug')
                    ->label('Service')
                    ->formatStateUsing(fn (?string $state): string => EServiceRegistry::get($state ?? '')['label'] ?? ($state ?? ''))
                    ->disabled()
                    ->helperText('E-service concerné par cette demande.'),
                TextInput::make('name')
                    ->label('Nom')
                    ->disabled()
                    ->helperText('Identité du déposant.'),
                TextInput::make('email')
                    ->label('Email')
                    ->disabled()
                    ->helperText('Email de contact du déposant.'),
                TextInput::make('phone')
                    ->label('Téléphone')
                    ->disabled()
                    ->helperText('Téléphone renseigné dans le formulaire (si fourni).'),
                Textarea::make('description')
                    ->label('Description')
                    ->disabled()
                    ->helperText('Description principale saisie par le déposant.')
                    ->columnSpanFull(),
                Textarea::make('payload')
                    ->label('Données complémentaires')
                    ->formatStateUsing(fn ($state): string => json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: '{}')
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText('Autres champs du formulaire (lecture seule, format technique).')
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Statut')
                    ->options(config('forum.submissionStatuses', []))
                    ->required()
                    ->helperText('Suivez le traitement interne : nouveau, en cours, traité, etc.'),
            ]);
    }
}
