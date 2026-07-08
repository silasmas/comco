<?php

namespace App\Filament\Resources\NavigationItems;

use App\Filament\Concerns\HasComcoResourceMeta;
use App\Filament\Resources\NavigationItems\Pages\CreateNavigationItem;
use App\Filament\Resources\NavigationItems\Pages\EditNavigationItem;
use App\Filament\Resources\NavigationItems\Pages\ListNavigationItems;
use App\Filament\Resources\NavigationItems\Schemas\NavigationItemForm;
use App\Filament\Resources\NavigationItems\Tables\NavigationItemsTable;
use App\Models\NavigationItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * Ressource Filament de gestion de la navigation publique.
 */
class NavigationItemResource extends Resource
{
    use HasComcoResourceMeta;

    protected static ?string $model = NavigationItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBars3;

    protected static ?string $navigationLabel = 'Navigation';

    protected static ?string $modelLabel = 'élément de menu';

    protected static ?string $pluralModelLabel = 'éléments de menu';

    protected static string|\UnitEnum|null $navigationGroup = 'Contenu du site';

    protected static ?int $navigationSort = 5;

    protected static string $resourceDescription = 'Gérez le menu principal et les liens du pied de page affichés sur le site public.';

    protected static ?string $tourStepId = 'navigation-items';

    protected static int $tourStepSort = 6;

    protected static array $tourStepFeatures = [
        'Modifier le menu principal et ses sous-menus',
        'Gérer les liens du pied de page',
        'Activer ou désactiver un lien sans le supprimer',
    ];

    public static function form(Schema $schema): Schema
    {
        return NavigationItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NavigationItemsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNavigationItems::route('/'),
            'create' => CreateNavigationItem::route('/create'),
            'edit' => EditNavigationItem::route('/{record}/edit'),
        ];
    }
}
