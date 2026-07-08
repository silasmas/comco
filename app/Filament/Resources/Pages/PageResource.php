<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Concerns\HasComcoResourceMeta;
use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Filament\Resources\Pages\Pages\ListPages;
use App\Filament\Resources\Pages\RelationManagers\GalleryItemsRelationManager;
use App\Filament\Resources\Pages\RelationManagers\LegalDocumentsRelationManager;
use App\Filament\Resources\Pages\RelationManagers\TeamMembersRelationManager;
use App\Filament\Resources\Pages\Schemas\PageForm;
use App\Filament\Resources\Pages\Tables\PagesTable;
use App\Models\Page;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PageResource extends Resource
{
    use HasComcoResourceMeta;

    protected static ?string $model = Page::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Pages CMS';

    protected static string|null|\UnitEnum $navigationGroup = 'Contenu';

    protected static ?int $navigationSort = 2;

    protected static string $resourceDescription = 'Éditez le contenu des pages institutionnelles par section (Qui sommes-nous, Centre d\'information, Médias, E-services) avec gabarits, médias attachés et SEO.';

    protected static ?string $tourStepId = 'pages';

    protected static int $tourStepSort = 11;

    protected static array $tourStepFeatures = [
        'Modifier le titre, le chapô et le corps éditorial avec l\'éditeur enrichi',
        'Choisir un gabarit d\'affichage (standard, galerie photo, équipe/partenaires, documents juridiques)',
        'Gérer la galerie, l\'équipe ou les PDF juridiques via les onglets en bas de fiche (selon le gabarit)',
        'Consulter le statut du formulaire en ligne pour les pages de la section E-services',
        'Ajouter ou configurer un formulaire e-service depuis les boutons en en-tête de fiche',
        'Contrôler la publication (brouillon / publiée) et les métadonnées SEO (titre et description)',
        'Prévisualiser la page publique via le bouton « Voir la page publique »',
    ];

    public static function form(Schema $schema): Schema
    {
        return PageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            GalleryItemsRelationManager::class,
            TeamMembersRelationManager::class,
            LegalDocumentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }
}
