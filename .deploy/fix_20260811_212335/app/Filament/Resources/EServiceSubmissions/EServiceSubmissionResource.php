<?php

namespace App\Filament\Resources\EServiceSubmissions;

use App\Filament\Concerns\HasComcoResourceMeta;
use App\Filament\Concerns\HasSafeNavigationBadge;
use App\Filament\Resources\EServiceSubmissions\Pages\EditEServiceSubmission;
use App\Filament\Resources\EServiceSubmissions\Pages\ListEServiceSubmissions;
use App\Filament\Resources\EServiceSubmissions\Schemas\EServiceSubmissionForm;
use App\Filament\Resources\EServiceSubmissions\Tables\EServiceSubmissionsTable;
use App\Models\EServiceSubmission;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EServiceSubmissionResource extends Resource
{
    use HasComcoResourceMeta;
    use HasSafeNavigationBadge;

    protected static ?string $model = EServiceSubmission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;

    protected static ?string $navigationLabel = 'E-services';

    protected static ?string $modelLabel = 'soumission e-service';

    protected static ?string $pluralModelLabel = 'soumissions e-services';

    protected static string|null|\UnitEnum $navigationGroup = 'Soumissions';

    protected static ?int $navigationSort = 1;

    protected static string $resourceDescription = 'Traitez les dossiers déposés en ligne par le public : notifications de fusion, plaintes, signalements confidentiels, produits dangereux, etc.';

    protected static ?string $tourStepId = 'e-service-submissions';

    protected static int $tourStepSort = 20;

    protected static array $tourStepFeatures = [
        'Consulter chaque dossier avec nom, coordonnées, description et champs spécifiques au service',
        'Filtrer par type de service (fusion, plainte consommateur, signalement…) et par statut',
        'Marquer un dossier comme traité, en cours ou rejeté depuis la fiche ou le tableau',
        'Identifier les dossiers en attente grâce au badge orange dans le menu',
        'Lire les données complémentaires saisies dans le formulaire dynamique (payload JSON)',
        'Recevoir automatiquement un e-mail institutionnel à chaque nouvelle soumission',
    ];

    /**
     * Titre affiché dans la visite guidée.
     */
    public static function getTourStepTitle(): ?string
    {
        return 'Soumissions e-services';
    }

    public static function form(Schema $schema): Schema
    {
        return EServiceSubmissionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EServiceSubmissionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEServiceSubmissions::route('/'),
            'edit' => EditEServiceSubmission::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::countNavigationBadge(static::getModel(), ['status' => 'pending']);
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }
}
