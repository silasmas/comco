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
                    ->description(
                        'Menu latéral : 1) gabarit « Page avec menu latéral », 2) dans Navigation créer un Groupe '
                        .'(même section + slug hub) puis ajouter les enfants. Chaque page enfant choisit texte, PDF ou les deux.'
                    )
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
                            ->helperText('Doit correspondre à la section du groupe Navigation (ex. Centre d’information).'),
                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->helperText('Identifiant d’URL sans espaces. Hub = slug du groupe Navigation ; pages enfants = slug de chaque entrée sidebar.'),
                        Select::make('template')
                            ->label('Gabarit d\'affichage')
                            ->options(config('cms-templates'))
                            ->live()
                            ->helperText('Choisissez « Page avec menu latéral » pour activer la sidebar. Les entrées du menu se gèrent dans Navigation.')
                            ->nullable(),
                        Select::make('content_display')
                            ->label('Affichage du contenu')
                            ->options(Page::contentDisplayLabels())
                            ->default(Page::DISPLAY_CONTENT)
                            ->required()
                            ->native(false)
                            ->helperText('Texte = chapô + contenu. PDF = onglet Documents. Les deux = texte puis visionneuse PDF.')
                            ->columnSpanFull(),
                    ]),
                Section::make('Contenu texte')
                    ->description('Utilisé si le mode d’affichage inclut le contenu texte.')
                    ->visible(fn (Get $get): bool => in_array(
                        $get('content_display') ?: Page::DISPLAY_CONTENT,
                        [Page::DISPLAY_CONTENT, Page::DISPLAY_BOTH],
                        true
                    ))
                    ->schema([
                        Textarea::make('excerpt')
                            ->label('Chapô / extrait')
                            ->rows(3)
                            ->helperText('Texte d’introduction. Sur la page hub, s’affiche sous l’image de couverture.')
                            ->columnSpanFull(),
                        RichEditor::make('body')
                            ->label('Contenu principal / description')
                            ->fileAttachmentsDirectory('pages/content')
                            ->helperText('Paragraphes, titres et listes. Bouton « Justifier » disponible dans la barre d’outils.')
                            ->columnSpanFull(),
                    ]),
                Section::make('Documents PDF')
                    ->description('Après enregistrement, utilisez l’onglet « Documents PDF » en bas de fiche pour téléverser les fichiers.')
                    ->visible(fn (Get $get): bool => in_array(
                        $get('content_display'),
                        [Page::DISPLAY_PDF, Page::DISPLAY_BOTH],
                        true
                    ) || ($get('template') === 'legal'))
                    ->schema([
                        TextInput::make('pdf_help')
                            ->label('Aide')
                            ->disabled()
                            ->dehydrated(false)
                            ->default('Les PDF se gèrent dans l’onglet « Documents PDF » (ou Documents juridiques) après la première sauvegarde.')
                            ->helperText('Mode PDF ou « texte et PDF » : ajoutez au moins un fichier actif pour l’affichage public.'),
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
