<?php

namespace App\Filament\Resources\EServiceSubmissions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EServiceSubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('service_slug')
                    ->label('Service')
                    ->formatStateUsing(fn (?string $state): string => config("e-services.{$state}.label", $state ?? ''))
                    ->disabled(),
                TextInput::make('name')
                    ->label('Nom')
                    ->disabled(),
                TextInput::make('email')
                    ->label('Email')
                    ->disabled(),
                TextInput::make('phone')
                    ->label('Téléphone')
                    ->disabled(),
                Textarea::make('description')
                    ->label('Description')
                    ->disabled()
                    ->columnSpanFull(),
                Textarea::make('payload')
                    ->label('Données complémentaires')
                    ->formatStateUsing(fn ($state): string => json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: '{}')
                    ->disabled()
                    ->dehydrated(false)
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Statut')
                    ->options(config('forum.submissionStatuses', []))
                    ->required(),
            ]);
    }
}
