<?php

namespace App\Filament\Resources\SiteBlocks\Schemas;

use App\Models\SiteBlock;
use App\Support\CroppableImageUpload;
use App\Support\HomeSlideStyle;
use Filament\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\View\View as ViewContract;

/**
 * Schéma de formulaire des blocs dynamiques de la page d'accueil.
 */
class SiteBlockForm
{
    /**
     * Configure le formulaire d'édition d'un bloc de contenu.
     *
     * @param  Schema  $schema  Schéma Filament
     * @return Schema Schéma configuré
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Bloc')
                    ->description('Les blocs structurent la page d’accueil. Le type détermine les champs de contenu disponibles ci-dessous.')
                    ->columns(2)
                    ->collapsible()
                    ->persistCollapsed()
                    ->schema([
                        Select::make('page')
                            ->label('Page')
                            ->options(SiteBlock::pageLabels())
                            ->default(SiteBlock::PAGE_HOME)
                            ->required()
                            ->disabled()
                            ->helperText('Actuellement réservé à la page d’accueil.'),
                        Select::make('block_type')
                            ->label('Type de bloc')
                            ->options(SiteBlock::blockTypeLabels())
                            ->required()
                            ->live()
                            ->helperText('Changez le type pour afficher les champs adaptés (citation, chiffre clé, carte, etc.).'),
                        TextInput::make('label')
                            ->label('Libellé interne')
                            ->maxLength(255)
                            ->helperText('Nom pour vous repérer dans l’admin (non affiché au public).'),
                        TextInput::make('sort_order')
                            ->label('Ordre d\'affichage')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->helperText('Position relative parmi les blocs du même type (0 = en premier).'),
                        Toggle::make('is_active')
                            ->label('Actif')
                            ->default(true)
                            ->required()
                            ->helperText('Désactivez pour masquer le bloc sur le site sans le supprimer.'),
                    ]),
                Section::make('Contenu')
                    ->description('Renseignez uniquement les champs utiles au type choisi. Les autres restent masqués.')
                    ->collapsible()
                    ->persistCollapsed()
                    ->schema(self::contentFields()),
                Section::make('Aperçu du slide')
                    ->description('Aperçu live ci-dessous. Le bouton ouvre un panneau latéral plus large. Sur mobile, la hauteur du slide est celle du site (pas tout l’écran).')
                    ->visible(fn ($get): bool => $get('block_type') === SiteBlock::TYPE_SLIDER)
                    ->collapsible()
                    ->persistCollapsed()
                    ->schema([
                        View::make('filament.site-blocks.slide-preview-inline')
                            ->viewData(fn (Get $get): array => [
                                'preview' => HomeSlideStyle::previewFromForm($get),
                            ])
                            ->columnSpanFull(),
                        Actions::make([
                            Action::make('previewSlide')
                                ->label('Agrandir l’aperçu (panneau latéral)')
                                ->icon(Heroicon::OutlinedArrowsPointingOut)
                                ->color('warning')
                                ->slideOver()
                                ->modalWidth(Width::Screen)
                                ->modalHeading('Aperçu du slide')
                                ->modalDescription('Basculez PC / Mobile. La hauteur mobile respecte le réglage du slide.')
                                ->modalSubmitAction(false)
                                ->modalCancelActionLabel('Fermer')
                                ->modalContent(function (Get $get): ViewContract {
                                    return view('filament.site-blocks.slide-preview-panel', [
                                        'preview' => HomeSlideStyle::previewFromForm($get),
                                    ]);
                                }),
                        ])->fullWidth(),
                    ]),
                Section::make('Apparence du slide')
                    ->description('Couleurs, polices, boutons, position et hauteur. Laissez vide pour les valeurs par défaut.')
                    ->columns(2)
                    ->visible(fn ($get): bool => $get('block_type') === SiteBlock::TYPE_SLIDER)
                    ->collapsible()
                    ->persistCollapsed()
                    ->schema(self::sliderStyleFields()),
            ]);
    }

    /**
     * Champs d'apparence spécifiques aux diapositives du slider.
     *
     * @return list<Component>
     */
    private static function sliderStyleFields(): array
    {
        return [
            TextInput::make('payload.title_color')
                ->label('Couleur du titre')
                ->type('color')
                ->default('#ffffff')
                ->live(debounce: 300)
                ->helperText('Couleur du titre sur l’image.'),
            TextInput::make('payload.text_color')
                ->label('Couleur de la description')
                ->type('color')
                ->default('#ffc107')
                ->live(debounce: 300)
                ->helperText('Couleur du sous-titre / description.'),
            Select::make('payload.title_font')
                ->label('Police du titre')
                ->options(HomeSlideStyle::fontOptions())
                ->default('inherit')
                ->live()
                ->native(false),
            Select::make('payload.text_font')
                ->label('Police de la description')
                ->options(HomeSlideStyle::fontOptions())
                ->default('inherit')
                ->live()
                ->native(false),
            Select::make('payload.content_h_align')
                ->label('Position horizontale du texte')
                ->options(HomeSlideStyle::horizontalAlignOptions())
                ->default('start')
                ->live()
                ->native(false),
            Select::make('payload.content_v_align')
                ->label('Position verticale')
                ->options(HomeSlideStyle::verticalAlignOptions())
                ->default('center')
                ->live()
                ->native(false),
            Select::make('payload.min_height')
                ->label('Hauteur du slide')
                ->options(HomeSlideStyle::heightOptions())
                ->default('default')
                ->live()
                ->native(false)
                ->helperText('Augmente la hauteur visuelle de cette diapositive.')
                ->columnSpanFull(),
            Select::make('payload.btn_shape')
                ->label('Forme des boutons')
                ->options(HomeSlideStyle::buttonShapeOptions())
                ->default('rounded')
                ->live()
                ->native(false),
            Select::make('payload.btn_h_align')
                ->label('Position horizontale des boutons')
                ->options(HomeSlideStyle::buttonAlignOptions())
                ->default('inherit')
                ->live()
                ->native(false)
                ->helperText('Gauche, centre ou droite — indépendant de l’alignement du titre.'),
            Select::make('payload.btn_placement')
                ->label('Emplacement des boutons')
                ->options(HomeSlideStyle::buttonPlacementOptions())
                ->default('after_text')
                ->live()
                ->native(false)
                ->helperText('Sous le titre, sous la description, au-dessus du titre, ou en bas du bloc.')
                ->columnSpanFull(),
            TextInput::make('payload.btn_primary_label')
                ->label('Bouton 1 — libellé')
                ->placeholder('En savoir plus')
                ->live(debounce: 400)
                ->helperText('Laissez vide pour masquer ce bouton.'),
            Select::make('payload.btn_primary_style')
                ->label('Bouton 1 — style')
                ->options(HomeSlideStyle::buttonStyleOptions())
                ->default('warning')
                ->live()
                ->native(false),
            TextInput::make('payload.btn_primary_section')
                ->label('Bouton 1 — section CMS')
                ->placeholder('qui-sommes-nous'),
            TextInput::make('payload.btn_primary_slug')
                ->label('Bouton 1 — slug CMS')
                ->placeholder('presentation'),
            TextInput::make('payload.btn_primary_url')
                ->label('Bouton 1 — URL (prioritaire)')
                ->url()
                ->helperText('Si remplie, remplace section/slug.'),
            TextInput::make('payload.btn_secondary_label')
                ->label('Bouton 2 — libellé')
                ->placeholder('(optionnel)')
                ->live(debounce: 400)
                ->helperText('Le signalement rouge est aussi dans la barre du haut. Laissez vide pour ne pas le dupliquer dans le slide.'),
            Select::make('payload.btn_secondary_style')
                ->label('Bouton 2 — style')
                ->options(HomeSlideStyle::buttonStyleOptions())
                ->default('danger')
                ->live()
                ->native(false),
            TextInput::make('payload.btn_secondary_section')
                ->label('Bouton 2 — section CMS')
                ->placeholder('e-services'),
            TextInput::make('payload.btn_secondary_slug')
                ->label('Bouton 2 — slug CMS')
                ->placeholder('signaler-pratique'),
            TextInput::make('payload.btn_secondary_url')
                ->label('Bouton 2 — URL (prioritaire)')
                ->url(),
        ];
    }

    /**
     * Retourne les champs de contenu selon le type de bloc sélectionné.
     *
     * @return list<Component>
     */
    private static function contentFields(): array
    {
        return [
            TextInput::make('payload.title')
                ->label('Titre')
                ->helperText('Titre principal affiché dans ce bloc.')
                ->live(debounce: 400)
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'title')),
            Textarea::make('payload.text')
                ->label('Texte de description')
                ->rows(4)
                ->helperText('Paragraphe descriptif du bloc.')
                ->live(debounce: 400)
                ->columnSpanFull()
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'text')),
            Textarea::make('payload.desc')
                ->label('Description courte')
                ->rows(3)
                ->helperText('Texte court complémentaire.')
                ->columnSpanFull()
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'desc')),
            Textarea::make('payload.quote')
                ->label('Citation')
                ->rows(4)
                ->helperText('Texte de la citation mise en avant.')
                ->columnSpanFull()
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'quote')),
            TextInput::make('payload.name')
                ->label('Nom')
                ->helperText('Nom de la personne citée ou présentée.')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'name')),
            TextInput::make('payload.role')
                ->label('Fonction / rôle')
                ->helperText('Fonction affichée sous le nom.')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'role')),
            TextInput::make('payload.date')
                ->label('Date affichée')
                ->helperText('Date libre affichée telle quelle (ex. 12 mars 2024).')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'date')),
            TextInput::make('payload.label')
                ->label('Libellé du chiffre')
                ->helperText('Légende sous le chiffre clé (ex. Décisions).')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'label')),
            TextInput::make('payload.value')
                ->label('Valeur numérique')
                ->numeric()
                ->helperText('Chiffre affiché en grand (ex. 120).')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'value')),
            TextInput::make('payload.icon')
                ->label('Icône Font Awesome ou fichier')
                ->helperText('Ex. far fa-chart-bar ou sharing.png pour une ressource.')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'icon')),
            TextInput::make('payload.transform')
                ->label('Transformation icône')
                ->helperText('Classe de transformation Font Awesome (optionnel).')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'transform')),
            Select::make('payload.source')
                ->label('Source de la vidéo')
                ->options([
                    'youtube' => 'YouTube',
                    'upload' => 'Fichier local (téléversement)',
                    'url' => 'URL directe (MP4, WebM…)',
                ])
                ->default('youtube')
                ->live()
                ->native(false)
                ->helperText('Choisissez YouTube, un fichier hébergé sur le site, ou une URL vidéo. Créez jusqu’à 3 blocs « Vidéo mise en avant » (ordre d’affichage).')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'video_source')),
            TextInput::make('payload.youtube')
                ->label('YouTube (ID ou URL)')
                ->helperText('Collez l’ID (ex. dQw4w9WgXcQ) ou l’URL complète YouTube / youtu.be.')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'video_source')
                    && ($get('payload.source') ?? 'youtube') === 'youtube'),
            FileUpload::make('payload.uploaded_video')
                ->label('Téléverser une vidéo')
                ->directory('site-blocks/videos')
                ->disk('public')
                ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime'])
                ->maxSize(102400)
                ->helperText('MP4 ou WebM recommandé (max. ~100 Mo). Affiché via le lecteur du site.')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'video_source')
                    && ($get('payload.source') ?? '') === 'upload'),
            TextInput::make('payload.video')
                ->label('Chemin vidéo actuel')
                ->disabled()
                ->dehydrated()
                ->helperText('Renseigné automatiquement après téléversement.')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'video_source')
                    && ($get('payload.source') ?? '') === 'upload'
                    && filled($get('payload.video'))),
            TextInput::make('payload.video_url')
                ->label('URL de la vidéo')
                ->url()
                ->helperText('Lien direct vers un fichier vidéo (MP4…) ou une URL YouTube.')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'video_source')
                    && ($get('payload.source') ?? '') === 'url'),
            TextInput::make('payload.link.section')
                ->label('Section de lien')
                ->helperText('Section CMS cible (ex. e-services), comme dans Navigation.')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'link')),
            TextInput::make('payload.link.slug')
                ->label('Slug de lien')
                ->helperText('Slug de la page CMS cible, identique à celui de « Pages ».')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'link')),
            Toggle::make('payload.reverse')
                ->label('Inverser la disposition')
                ->helperText('Inverse image / texte (gauche ↔ droite).')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'reverse')),
            Select::make('payload.image_source')
                ->label('Source du visuel')
                ->options([
                    'comco' => 'Assets COMCO (/assets/)',
                    'theme' => 'Thème Elixir (/theme/)',
                ])
                ->default('comco')
                ->live()
                ->helperText('Dossier d’origine si vous utilisez un chemin de fichier existant.')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'image')),
            TextInput::make('payload.image')
                ->label('Chemin du visuel')
                ->live(debounce: 400)
                ->helperText('Ex. 1.jpg.jpeg, assets/img/client1.png ou gallery/06-f.jpg')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'image')),
            TextInput::make('payload.logo')
                ->label('Chemin du logo')
                ->helperText('Chemin relatif du logo partenaire dans les assets.')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'logo')),
            CroppableImageUpload::apply(
                FileUpload::make('payload.uploaded_image')
                    ->label('Téléverser une image')
                    ->directory('site-blocks/home')
                    ->disk('public')
                    ->live()
                    ->helperText('Ouvrez l\'éditeur après l\'upload pour rogner (ex. 16:9). Le rognage ne grossit jamais l\'image et conserve la qualité. Pour un slide, préférez un format large.')
                    ->visible(fn ($get): bool => self::hasField($get('block_type'), 'image')),
                [null, '16:9', '21:9', '3:2', '4:3']
            ),
            Textarea::make('payload.value')
                ->label('Valeur du paramètre')
                ->rows(3)
                ->helperText('Valeur texte du paramètre (ex. slogan).')
                ->columnSpanFull()
                ->visible(fn ($get): bool => $get('block_type') === SiteBlock::TYPE_SETTING && $get('block_key') === 'tagline'),
            KeyValue::make('payload.value')
                ->label('Libellés des onglets')
                ->helperText('Clé = identifiant d’onglet, valeur = libellé affiché.')
                ->visible(fn ($get): bool => $get('block_type') === SiteBlock::TYPE_SETTING && $get('block_key') === 'home_tabs'),
        ];
    }

    /**
     * Indique si un champ est pertinent pour un type de bloc donné.
     *
     * @param  string|null  $blockType  Type de bloc
     * @param  string  $field  Nom du champ
     * @return bool True si le champ doit être affiché
     */
    private static function hasField(?string $blockType, string $field): bool
    {
        if ($blockType === SiteBlock::TYPE_SETTING) {
            return false;
        }

        $map = [
            SiteBlock::TYPE_SLIDER => ['title', 'text', 'image'],
            SiteBlock::TYPE_WELCOME_ITEM => ['title', 'desc', 'icon'],
            SiteBlock::TYPE_STORY_ITEM => ['title', 'text', 'icon'],
            SiteBlock::TYPE_SERVICE => ['title', 'text', 'image', 'link', 'reverse'],
            SiteBlock::TYPE_WHY_CHOOSE => ['title', 'text', 'icon', 'transform'],
            SiteBlock::TYPE_FEATURE => ['title', 'text', 'icon'],
            SiteBlock::TYPE_FUN_FACT => ['value', 'label'],
            SiteBlock::TYPE_FEATURED => ['title', 'text', 'date', 'image'],
            SiteBlock::TYPE_ACTIVITY => ['title', 'text'],
            SiteBlock::TYPE_TESTIMONIAL => ['quote', 'name', 'role', 'image'],
            SiteBlock::TYPE_PARTNER => ['logo', 'name'],
            SiteBlock::TYPE_LATEST_VIDEO => ['title', 'text', 'image', 'video_source'],
        ];

        return in_array($field, $map[$blockType] ?? [], true);
    }
}
