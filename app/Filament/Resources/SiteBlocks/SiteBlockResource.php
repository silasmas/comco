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

    protected static string $resourceDescription = 'Administrez les blocs dynamiques de la page d\'accueil : slider hero, cartes institutionnelles, chiffres clés, témoignages, partenaires, vidéo et onglets actualités.';

    protected static ?string $tourStepId = 'home-blocks';

    protected static int $tourStepSort = 1;

    protected static array $tourStepFeatures = [
        'Modifier les diapositives du slider (titres, textes, images, boutons et liens internes)',
        'Éditer les sections Bienvenue, Notre histoire, Missions, Ressources et Pourquoi la COMCO',
        'Ajuster les chiffres clés animés, le bloc « À la une » et les activités institutionnelles',
        'Mettre à jour les témoignages du carousel et les logos partenaires',
        'Changer la vidéo institutionnelle mise en avant et les libellés des onglets',
        'Téléverser des visuels COMCO ou choisir une image du thème Elixir',
        'Désactiver temporairement un bloc sans le supprimer (case « Actif »)',
    ];

    /**
     * Restreint la ressource au contenu de la page d'accueil.
     *
     * @return Builder<SiteBlock> Requête filtrée
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('page', SiteBlock::PAGE_HOME);
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
