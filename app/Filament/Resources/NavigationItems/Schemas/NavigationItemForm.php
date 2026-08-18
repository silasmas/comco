<?php

namespace App\Filament\Resources\NavigationItems\Schemas;

use App\Models\NavigationItem;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

/**
 * Schéma de formulaire des éléments de navigation.
 */
class NavigationItemForm
{
    /**
     * Configure le formulaire d'un élément de menu.
     *
     * @param  Schema  $schema  Schéma Filament
     * @return Schema Schéma configuré
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Élément de menu')
                    ->description(
                        'Guide sidebar : 1) Créez les pages (gabarit « Page avec menu latéral », mode texte/PDF/les deux). '
                        .'2) Groupe Navigation : section + slug hub (sidebar vierge si aucun enfant). '
                        .'3) Ajoutez les enfants (même section, slug de chaque page). '
                        .'Déroulant classique = Groupe sans slug. Pieds de page = menus pied, sans parent.'
                    )
                    ->columns(2)
                    ->schema([
                        Select::make('menu')
                            ->label('Menu')
                            ->options(NavigationItem::menuLabels())
                            ->required()
                            ->live()
                            ->helperText('Menu principal = barre du haut. Les trois « Pied de page » = colonnes du bas du site (navigation, e-services, liens rapides).'),
                        Select::make('parent_id')
                            ->label('Élément parent')
                            ->options(fn (): array => NavigationItem::query()
                                ->where('menu', NavigationItem::MENU_MAIN)
                                ->whereNull('parent_id')
                                ->pluck('label', 'id')
                                ->all())
                            ->visible(fn (Get $get): bool => $get('menu') === NavigationItem::MENU_MAIN)
                            ->nullable()
                            ->helperText('Laissez vide pour un onglet de premier niveau. Choisissez un groupe parent pour en faire un sous-lien (déroulant ou menu latéral).'),
                        TextInput::make('label')
                            ->label('Libellé')
                            ->required()
                            ->helperText('Texte visible dans le menu (ex. Présentation, Contact, Cadre juridique).'),
                        Select::make('link_type')
                            ->label('Type de lien')
                            ->options(NavigationItem::linkTypeLabels())
                            ->required()
                            ->live()
                            ->helperText('Page CMS = page créée dans « Pages ». Groupe = parent de sous-liens (déroulant ou latéral). Route = page technique (accueil, contact, forum). URL = lien hors site.'),
                        TextInput::make('route')
                            ->label('Route Laravel')
                            ->helperText('Nom de route interne. Exemples : home (accueil), contact, forum.index.')
                            ->visible(fn (Get $get): bool => $get('link_type') === NavigationItem::LINK_ROUTE),
                        Select::make('section')
                            ->label('Section CMS')
                            ->options(fn (): array => navigationSections())
                            ->searchable()
                            ->helperText('Doit correspondre à la section de la page CMS. Choisissez « Centre d’information (centre-information) » pour cette rubrique.')
                            ->visible(fn (Get $get): bool => in_array($get('link_type'), [NavigationItem::LINK_SECTION, NavigationItem::LINK_GROUP], true)),
                        TextInput::make('slug')
                            ->label('Slug de page')
                            ->helperText(fn (Get $get): string => match ($get('link_type')) {
                                NavigationItem::LINK_GROUP => 'Obligatoire pour un menu latéral : slug de la page hub (ex. presentation). Sans slug = menu déroulant classique. Avec slug = lien direct + enfants en sidebar.',
                                default => 'Doit être identique au slug de la page CMS (ex. presentation, missions, cadre-juridique).',
                            })
                            ->visible(fn (Get $get): bool => in_array($get('link_type'), [NavigationItem::LINK_SECTION, NavigationItem::LINK_GROUP], true)),
                        TextInput::make('url')
                            ->label('URL externe')
                            ->url()
                            ->helperText('Adresse complète commençant par https:// (site partenaire, document en ligne, etc.).')
                            ->visible(fn (Get $get): bool => $get('link_type') === NavigationItem::LINK_EXTERNAL),
                        TextInput::make('sort_order')
                            ->label('Ordre')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->helperText('Position d’affichage (0, 1, 2…). Plus le nombre est petit, plus le lien apparaît tôt.'),
                        Toggle::make('is_active')
                            ->label('Actif')
                            ->default(true)
                            ->required()
                            ->helperText('Désactivez pour masquer le lien sur le site sans le supprimer.'),
                    ]),
            ]);
    }
}
