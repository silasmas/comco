<?php

namespace App\Filament\Resources\SiteBlocks\Schemas;

use App\Models\SiteBlock;
use App\Support\CroppableImageUpload;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

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
                    ->schema(self::contentFields()),
            ]);
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
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'title')),
            Textarea::make('payload.text')
                ->label('Texte de description')
                ->rows(4)
                ->helperText('Paragraphe descriptif du bloc.')
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
            TextInput::make('payload.youtube')
                ->label('Identifiant YouTube')
                ->helperText('ID de la vidéo uniquement (ex. dQw4w9WgXcQ), pas l’URL complète.')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'youtube')),
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
                ->helperText('Dossier d’origine si vous utilisez un chemin de fichier existant.')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'image')),
            TextInput::make('payload.image')
                ->label('Chemin du visuel')
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
                    ->helperText('Ouvrez l\'éditeur après l\'upload pour rogner (ex. 16:9). Le rognage ne grossit jamais l\'image et conserve la qualité.')
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
            SiteBlock::TYPE_LATEST_VIDEO => ['title', 'text', 'image', 'youtube'],
        ];

        return in_array($field, $map[$blockType] ?? [], true);
    }
}
