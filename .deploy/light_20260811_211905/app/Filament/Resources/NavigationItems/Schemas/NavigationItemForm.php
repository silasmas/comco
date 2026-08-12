<?php

namespace App\Filament\Resources\NavigationItems\Schemas;

use App\Models\NavigationItem;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
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
                    ->columns(2)
                    ->schema([
                        Select::make('menu')
                            ->label('Menu')
                            ->options(NavigationItem::menuLabels())
                            ->required()
                            ->live(),
                        Select::make('parent_id')
                            ->label('Élément parent')
                            ->options(fn (): array => NavigationItem::query()
                                ->where('menu', NavigationItem::MENU_MAIN)
                                ->whereNull('parent_id')
                                ->pluck('label', 'id')
                                ->all())
                            ->visible(fn ($get): bool => $get('menu') === NavigationItem::MENU_MAIN)
                            ->nullable(),
                        TextInput::make('label')
                            ->label('Libellé')
                            ->required(),
                        Select::make('link_type')
                            ->label('Type de lien')
                            ->options(NavigationItem::linkTypeLabels())
                            ->required()
                            ->live(),
                        TextInput::make('route')
                            ->label('Route Laravel')
                            ->helperText('Ex. home, contact, forum.index')
                            ->visible(fn ($get): bool => $get('link_type') === NavigationItem::LINK_ROUTE),
                        TextInput::make('section')
                            ->label('Section CMS')
                            ->helperText('Ex. qui-sommes-nous, e-services')
                            ->visible(fn ($get): bool => in_array($get('link_type'), [NavigationItem::LINK_SECTION, NavigationItem::LINK_GROUP], true)),
                        TextInput::make('slug')
                            ->label('Slug de page')
                            ->visible(fn ($get): bool => $get('link_type') === NavigationItem::LINK_SECTION),
                        TextInput::make('url')
                            ->label('URL externe')
                            ->url()
                            ->visible(fn ($get): bool => $get('link_type') === NavigationItem::LINK_EXTERNAL),
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
            ]);
    }
}
