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

  protected static string $resourceDescription = 'Traitez les dossiers transmis par les e-services publics (signalements, fusions, autorisations, etc.).';

  protected static ?string $tourStepId = 'e-service-submissions';

  protected static int $tourStepSort = 31;

  protected static array $tourStepFeatures = [
    'Consulter les dossiers reçus',
    'Marquer un dossier comme traité ou rejeté',
    'Filtrer par service et statut',
  ];

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
