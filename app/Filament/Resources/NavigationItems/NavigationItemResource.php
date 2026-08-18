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

    protected static string $resourceDescription = 'Configurez la navigation publique : menu principal, sous-menus, menu latéral (groupe + slug hub) et colonnes du pied de page. Créez d’abord la page CMS, puis rattachez-la ici avec la même section et le même slug.';

    protected static ?string $tourStepId = 'navigation-items';

    protected static int $tourStepSort = 5;

    protected static array $tourStepFeatures = [
        'Organiser le menu principal (Accueil, Qui sommes-nous, Centre d\'information, Médias, E-services, Forum, Contact)',
        'Attacher une page CMS : même « section » et « slug » que dans Pages',
        'Créer un menu latéral : type Groupe + section + slug de la page hub, puis enfants sous ce parent',
        'Créer un déroulant classique : type Groupe avec section seule (sans slug)',
        'Gérer les menus du pied de page : institution, e-services et liens utiles',
        'Masquer un lien temporairement avec « Actif » sans le supprimer',
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
