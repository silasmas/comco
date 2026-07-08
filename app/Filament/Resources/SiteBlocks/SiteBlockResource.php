<?php

namespace App\Filament\Resources\SiteBlocks;

use App\Filament\Concerns\HasComcoResourceMeta;
use App\Filament\Resources\SiteBlocks\Pages\CreateSiteBlock;
use App\Filament\Resources\SiteBlocks\Pages\EditSiteBlock;
use App\Filament\Resources\SiteBlocks\Pages\ListSiteBlocks;
use App\Filament\Resources\SiteBlocks\Schemas\SiteBlockForm;
use App\Filament\Resources\SiteBlocks\Tables\SiteBlocksTable;
use App\Models\SiteBlock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Ressource Filament de gestion des blocs dynamiques du site public.
 */
class SiteBlockResource extends Resource
{
    use HasComcoResourceMeta;

    protected static ?string $model = SiteBlock::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHomeModern;

    protected static ?string $navigationLabel = 'Page d\'accueil';

    protected static ?string $modelLabel = 'bloc d\'accueil';

    protected static ?string $pluralModelLabel = 'blocs d\'accueil';

    protected static string|null|\UnitEnum $navigationGroup = 'Contenu du site';

    protected static ?int $navigationSort = 0;

    protected static string $resourceDescription = 'Modifiez le slider, les cartes, les chiffres clés et les témoignages affichés sur la page d\'accueil.';

    protected static ?string $tourStepId = 'home-blocks';

    protected static int $tourStepSort = 5;

    protected static array $tourStepFeatures = [
        'Gérer les diapositives du slider',
        'Mettre à jour les missions et ressources',
        'Ajuster les témoignages et partenaires',
    ];

    /**
     * Restreint la ressource au contenu de la page d'accueil.
     *
     * @return Builder<SiteBlock> Requête filtrée
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('page', SiteBlock::PAGE_HOME)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public static function form(Schema $schema): Schema
    {
        return SiteBlockForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SiteBlocksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSiteBlocks::route('/'),
            'create' => CreateSiteBlock::route('/create'),
            'edit' => EditSiteBlock::route('/{record}/edit'),
        ];
    }
}
