<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Models\Page;
use App\Support\EServiceRegistry;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

/**
 * Schéma de formulaire des pages CMS institutionnelles.
 */
class PageForm
{
    /**
     * Configure le formulaire d'édition d'une page CMS.
     *
     * @param  Schema  $schema  Schéma Filament
     * @return Schema Schéma configuré
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identification')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Titre')
                            ->required(),
                        Select::make('section')
                            ->label('Section')
                            ->options(config('navigation.sections'))
                            ->nullable(),
                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->required(),
                        Select::make('template')
                            ->label('Gabarit d\'affichage')
                            ->options(config('cms-templates'))
                            ->helperText('Détermine la mise en page publique (galerie, équipe, PDF, etc.).')
                            ->nullable(),
                    ]),
                Section::make('Contenu')
                    ->schema([
                        Textarea::make('excerpt')
                            ->label('Chapô / extrait')
                            ->rows(3)
                            ->columnSpanFull(),
                        RichEditor::make('body')
                            ->label('Contenu principal')
                            ->fileAttachmentsDirectory('pages/content')
                            ->columnSpanFull(),
                    ]),
                Section::make('Formulaire en ligne')
                    ->visible(fn (Get $get): bool => $get('section') === 'e-services')
                    ->schema([
                        TextInput::make('e_service_form_status')
                            ->label('Statut du formulaire')
                            ->disabled()
                            ->dehydrated(false)
                            ->default(function (Get $get, ?Page $record): string {
                                $slug = $get('slug') ?? $record?->slug;

                                if (! filled($slug)) {
                                    return 'Enregistrez la page pour associer un formulaire.';
                                }

                                $definition = EServiceRegistry::findDefinition((string) $slug);

                                if ($definition === null) {
                                    return 'Page informative uniquement (aucun formulaire en ligne).';
                                }

                                return $definition->is_active
                                    ? 'Formulaire en ligne actif.'
                                    : 'Formulaire configuré mais désactivé.';
                            }),
                    ]),
                Section::make('Publication & SEO')
                    ->columns(2)
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Titre SEO'),
                        Textarea::make('meta_description')
                            ->label('Description SEO')
                            ->columnSpanFull(),
                        Toggle::make('is_published')
                            ->label('Publiée')
                            ->required(),
                        DateTimePicker::make('published_at')
                            ->label('Date de publication'),
                    ]),
            ]);
    }
}
