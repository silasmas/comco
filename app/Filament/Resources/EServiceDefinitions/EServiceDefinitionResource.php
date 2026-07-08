<?php

namespace App\Filament\Resources\EServiceDefinitions;

use App\Filament\Concerns\HasComcoResourceMeta;
use App\Filament\Resources\EServiceDefinitions\Pages\CreateEServiceDefinition;
use App\Filament\Resources\EServiceDefinitions\Pages\EditEServiceDefinition;
use App\Filament\Resources\EServiceDefinitions\Pages\ListEServiceDefinitions;
use App\Filament\Resources\EServiceDefinitions\Schemas\EServiceDefinitionForm;
use App\Filament\Resources\EServiceDefinitions\Tables\EServiceDefinitionsTable;
use App\Models\EServiceDefinition;
use App\Support\EServiceRegistry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * Ressource Filament de gestion des formulaires e-services publics.
 */
class EServiceDefinitionResource extends Resource
{
    use HasComcoResourceMeta;

    protected static ?string $model = EServiceDefinition::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'E-services';

    protected static ?string $modelLabel = 'e-service';

    protected static ?string $pluralModelLabel = 'e-services';

    protected static string|\UnitEnum|null $navigationGroup = 'Contenu du site';

    protected static ?int $navigationSort = 6;

    protected static string $resourceDescription = 'Configurez les formulaires de dépôt en ligne (fusions, plaintes, signalements…) : libellés, introduction, champs dynamiques et activation publique.';

    protected static ?string $tourStepId = 'e-service-definitions';

    protected static int $tourStepSort = 6;

    protected static array $tourStepFeatures = [
        'Créer un nouveau formulaire e-service avec le bouton « Nouveau formulaire »',
        'Personnaliser les champs (texte, zone de texte, liste déroulante, case à cocher, obligatoire ou non)',
        'Adapter le libellé et l\'introduction affichés au-dessus du formulaire public',
        'Activer ou désactiver un service sans supprimer sa configuration',
        'Associer un formulaire à une page CMS via le même slug URL (section e-services)',
        'Accéder à la page publique ou à la page CMS depuis l\'en-tête d\'édition',
    ];

    /**
     * Titre affiché dans la visite guidée.
     */
    public static function getTourStepTitle(): ?string
    {
        return 'Formulaires e-services';
    }

    public static function form(Schema $schema): Schema
    {
        return EServiceDefinitionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EServiceDefinitionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEServiceDefinitions::route('/'),
            'create' => CreateEServiceDefinition::route('/create'),
            'edit' => EditEServiceDefinition::route('/{record}/edit'),
        ];
    }

    /**
     * Rafraîchit la configuration e-services après édition.
     */
    public static function refreshRegistry(): void
    {
        EServiceRegistry::applyToConfig();
    }
}
