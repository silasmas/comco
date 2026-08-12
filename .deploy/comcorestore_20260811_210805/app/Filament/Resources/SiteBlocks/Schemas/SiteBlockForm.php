<?php

namespace App\Filament\Resources\SiteBlocks\Schemas;

use App\Models\SiteBlock;
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
                    ->columns(2)
                    ->schema([
                        Select::make('page')
                            ->label('Page')
                            ->options(SiteBlock::pageLabels())
                            ->default(SiteBlock::PAGE_HOME)
                            ->required()
                            ->disabled(),
                        Select::make('block_type')
                            ->label('Type de bloc')
                            ->options(SiteBlock::blockTypeLabels())
                            ->required()
                            ->live(),
                        TextInput::make('label')
                            ->label('Libellé interne')
                            ->maxLength(255),
                        TextInput::make('sort_order')
                            ->label('Ordre d\'affichage')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Actif')
                            ->default(true)
                            ->required(),
                    ]),
                Section::make('Contenu')
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
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'title')),
            Textarea::make('payload.text')
                ->label('Texte')
                ->rows(4)
                ->columnSpanFull()
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'text')),
            Textarea::make('payload.desc')
                ->label('Description courte')
                ->rows(3)
                ->columnSpanFull()
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'desc')),
            Textarea::make('payload.quote')
                ->label('Citation')
                ->rows(4)
                ->columnSpanFull()
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'quote')),
            TextInput::make('payload.name')
                ->label('Nom')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'name')),
            TextInput::make('payload.role')
                ->label('Fonction / rôle')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'role')),
            TextInput::make('payload.date')
                ->label('Date affichée')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'date')),
            TextInput::make('payload.label')
                ->label('Libellé du chiffre')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'label')),
            TextInput::make('payload.value')
                ->label('Valeur numérique')
                ->numeric()
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'value')),
            TextInput::make('payload.icon')
                ->label('Icône Font Awesome ou fichier')
                ->helperText('Ex. far fa-chart-bar ou sharing.png pour une ressource.')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'icon')),
            TextInput::make('payload.transform')
                ->label('Transformation icône')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'transform')),
            TextInput::make('payload.youtube')
                ->label('Identifiant YouTube')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'youtube')),
            TextInput::make('payload.link.section')
                ->label('Section de lien')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'link')),
            TextInput::make('payload.link.slug')
                ->label('Slug de lien')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'link')),
            Toggle::make('payload.reverse')
                ->label('Inverser la disposition')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'reverse')),
            Select::make('payload.image_source')
                ->label('Source du visuel')
                ->options([
                    'comco' => 'Assets COMCO (/assets/)',
                    'theme' => 'Thème Elixir (/theme/)',
                ])
                ->default('comco')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'image')),
            TextInput::make('payload.image')
                ->label('Chemin du visuel')
                ->helperText('Ex. 1.jpg.jpeg, assets/img/client1.png ou gallery/06-f.jpg')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'image')),
            TextInput::make('payload.logo')
                ->label('Chemin du logo')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'logo')),
            FileUpload::make('payload.uploaded_image')
                ->label('Téléverser une image')
                ->image()
                ->directory('site-blocks/home')
                ->disk('public')
                ->visible(fn ($get): bool => self::hasField($get('block_type'), 'image')),
            Textarea::make('payload.value')
                ->label('Valeur du paramètre')
                ->rows(3)
                ->columnSpanFull()
                ->visible(fn ($get): bool => $get('block_type') === SiteBlock::TYPE_SETTING && $get('block_key') === 'tagline'),
            KeyValue::make('payload.value')
                ->label('Libellés des onglets')
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
