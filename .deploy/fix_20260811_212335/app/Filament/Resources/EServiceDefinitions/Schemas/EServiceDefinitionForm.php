<?php

namespace App\Filament\Resources\EServiceDefinitions\Schemas;

use App\Models\EServiceDefinition;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

/**
 * Schéma de formulaire des définitions e-services.
 */
class EServiceDefinitionForm
{
    /**
     * Configure le formulaire d'édition d'un e-service.
     *
     * @param  Schema  $schema  Schéma Filament
     * @return Schema Schéma configuré
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Service')
                    ->columns(2)
                    ->schema([
                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->unique(EServiceDefinition::class, 'slug', ignoreRecord: true)
                            ->disabled(fn (?EServiceDefinition $record): bool => $record !== null)
                            ->dehydrated()
                            ->helperText('Doit correspondre au slug de la page CMS (section e-services).'),
                        TextInput::make('label')
                            ->label('Libellé')
                            ->required(),
                        Textarea::make('intro')
                            ->label('Introduction')
                            ->rows(3)
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('sort_order')
                            ->label('Ordre')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Actif')
                            ->default(true)
                            ->required(),
                    ]),
                Section::make('Champs du formulaire')
                    ->schema([
                        Repeater::make('fields')
                            ->label('Champs')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Identifiant technique')
                                    ->required(),
                                TextInput::make('label')
                                    ->label('Libellé affiché')
                                    ->required(),
                                Select::make('type')
                                    ->label('Type')
                                    ->options([
                                        'text' => 'Texte court',
                                        'textarea' => 'Texte long',
                                        'select' => 'Liste déroulante',
                                        'checkbox' => 'Case à cocher',
                                    ])
                                    ->required()
                                    ->live(),
                                Toggle::make('required')
                                    ->label('Obligatoire')
                                    ->default(false),
                                TextInput::make('rows')
                                    ->label('Nombre de lignes')
                                    ->numeric()
                                    ->visible(fn ($get): bool => $get('type') === 'textarea'),
                                TagsInput::make('options')
                                    ->label('Options')
                                    ->visible(fn ($get): bool => $get('type') === 'select')
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull()
                            ->collapsible(),
                    ]),
            ]);
    }
}
