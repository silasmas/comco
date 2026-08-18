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
                    ->description('Pour afficher cette page dans le menu : créez ensuite un élément dans « Navigation » avec exactement la même section et le même slug.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Titre')
                            ->required()
                            ->helperText('Titre affiché sur la page publique et dans les listes admin.'),
                        Select::make('section')
                            ->label('Section')
                            ->options(fn (): array => navigationSections())
                            ->searchable()
                            ->nullable()
                            ->helperText('Liste issue de config/navigation.php (clé « sections »), enrichie par les groupes du menu principal. Choisissez « Centre d’information (centre-information) » pour cette rubrique.'),
                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->helperText('Identifiant d’URL sans espaces (ex. presentation). À reprendre à l’identique dans Navigation pour rattacher le menu.'),
                        Select::make('template')
                            ->label('Gabarit d\'affichage')
                            ->options(config('cms-templates'))
                            ->helperText('presentation-hub active souvent le menu latéral (enfants du groupe Navigation). Autres gabarits : galerie, équipe, PDF, etc.')
                            ->nullable(),
                    ]),
                Section::make('Contenu')
                    ->description('Pour la page Présentation : le chapô et le texte s\'affichent sous l\'image de couverture (onglet dédié en bas de fiche).')
                    ->schema([
                        Textarea::make('excerpt')
                            ->label('Chapô / extrait')
                            ->rows(3)
                            ->helperText('Texte d\'introduction affiché sous l\'image sur la page Présentation.')
                            ->columnSpanFull(),
                        RichEditor::make('body')
                            ->label('Contenu principal / description')
                            ->fileAttachmentsDirectory('pages/content')
                            ->helperText('Paragraphes, titres et listes. Bouton « Justifier » disponible dans la barre d\'outils.')
                            ->columnSpanFull(),
                    ]),
                Section::make('Formulaire en ligne')
                    ->visible(fn (Get $get): bool => $get('section') === 'e-services')
                    ->description('Le formulaire public est défini dans « E-services » : le slug de la page doit correspondre au slug du service.')
                    ->schema([
                        TextInput::make('e_service_form_status')
                            ->label('Statut du formulaire')
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Indicateur en lecture seule : aucune action à saisir ici.')
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
                            ->label('Titre SEO')
                            ->helperText('Titre navigateur / Google. Si vide, le titre de la page est utilisé.'),
                        Textarea::make('meta_description')
                            ->label('Description SEO')
                            ->helperText('Résumé court pour les moteurs de recherche (idéalement 150–160 caractères).')
                            ->columnSpanFull(),
                        Toggle::make('is_published')
                            ->label('Publiée')
                            ->helperText('Désactivé = page invisible au public (brouillon).')
                            ->required(),
                        DateTimePicker::make('published_at')
                            ->label('Date de publication')
                            ->helperText('Date affichée / de référence. Peut rester vide pour un brouillon.'),
                    ]),
            ]);
    }
}
