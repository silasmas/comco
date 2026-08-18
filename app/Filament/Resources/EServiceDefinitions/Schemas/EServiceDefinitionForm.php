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
                            ->helperText('Doit correspondre au slug de la page CMS (section e-services) pour afficher le formulaire.'),
                        TextInput::make('label')
                            ->label('Libellé')
                            ->required()
                            ->helperText('Nom du service affiché sur le site et dans les listes admin.'),
                        Textarea::make('intro')
                            ->label('Introduction')
                            ->rows(3)
                            ->required()
                            ->helperText('Texte d’introduction au-dessus du formulaire public.')
                            ->columnSpanFull(),
                        TextInput::make('sort_order')
                            ->label('Ordre')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->helperText('Position dans la liste des e-services (0 = en premier).'),
                        Toggle::make('is_active')
                            ->label('Actif')
                            ->default(true)
                            ->required()
                            ->helperText('Désactivez pour masquer le formulaire public sans supprimer la configuration.'),
                    ]),
                Section::make('Champs du formulaire')
                    ->description('Chaque ligne définit un champ visible par l’usager. L’identifiant technique ne doit pas contenir d’espaces.')
                    ->schema([
                        Repeater::make('fields')
                            ->label('Champs')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Identifiant technique')
                                    ->required()
                                    ->helperText('Clé interne (ex. nom_entreprise, email). Sans espaces ni accents.'),
                                TextInput::make('label')
                                    ->label('Libellé affiché')
                                    ->required()
                                    ->helperText('Texte vu par l’usager à côté du champ.'),
                                Select::make('type')
                                    ->label('Type')
                                    ->options([
                                        'text' => 'Texte court',
                                        'textarea' => 'Texte long',
                                        'select' => 'Liste déroulante',
                                        'checkbox' => 'Case à cocher',
                                    ])
                                    ->required()
                                    ->live()
                                    ->helperText('Détermine le contrôle affiché et les options disponibles ci-dessous.'),
                                Toggle::make('required')
                                    ->label('Obligatoire')
                                    ->default(false)
                                    ->helperText('Si activé, l’usager doit remplir ce champ pour envoyer le formulaire.'),
                                TextInput::make('rows')
                                    ->label('Nombre de lignes')
                                    ->numeric()
                                    ->helperText('Hauteur de la zone de texte (textarea uniquement).')
                                    ->visible(fn ($get): bool => $get('type') === 'textarea'),
                                TagsInput::make('options')
                                    ->label('Options')
                                    ->helperText('Une option par étiquette (liste déroulante uniquement).')
                                    ->visible(fn ($get): bool => $get('type') === 'select')
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull()
                            ->collapsible(),
                    ]),
            ]);
    }
}
