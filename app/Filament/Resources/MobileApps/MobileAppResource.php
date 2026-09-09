<?php

namespace App\Filament\Resources\MobileApps;

use App\Filament\Concerns\HasComcoResourceMeta;
use App\Filament\Resources\MobileApps\Pages\CreateMobileApp;
use App\Filament\Resources\MobileApps\Pages\EditMobileApp;
use App\Filament\Resources\MobileApps\Pages\ListMobileApps;
use App\Filament\Resources\MobileApps\Schemas\MobileAppForm;
use App\Filament\Resources\MobileApps\Tables\MobileAppsTable;
use App\Models\MobileApp;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * Ressource Filament pour publier des APK (lien + QR code de partage).
 */
class MobileAppResource extends Resource
{
  use HasComcoResourceMeta;

  protected static ?string $model = MobileApp::class;

  protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDevicePhoneMobile;

  protected static ?string $navigationLabel = 'Applications APK';

  protected static ?string $modelLabel = 'application APK';

  protected static ?string $pluralModelLabel = 'applications APK';

  protected static string|\UnitEnum|null $navigationGroup = 'Système';

  protected static ?int $navigationSort = 40;

  protected static string $resourceDescription = 'Uploadez un fichier APK, obtenez un lien public et un QR code à partager pour le téléchargement.';

  protected static ?string $tourStepId = 'mobile-apps';

  protected static int $tourStepSort = 40;

  protected static array $tourStepFeatures = [
    'Uploader un fichier .apk',
    'Générer automatiquement un lien de partage',
    'Afficher un QR code scannable',
    'Suivre le nombre de téléchargements',
  ];

  /**
   * Titre de l'étape de visite guidée.
   *
   * @return string|null Titre
   */
  public static function getTourStepTitle(): ?string
  {
    return 'Applications APK';
  }

  /**
   * Configure le formulaire.
   *
   * @param  Schema  $schema  Schéma
   * @return Schema Schéma configuré
   */
  public static function form(Schema $schema): Schema
  {
    return MobileAppForm::configure($schema);
  }

  /**
   * Configure le tableau.
   *
   * @param  Table  $table  Table
   * @return Table Table configurée
   */
  public static function table(Table $table): Table
  {
    return MobileAppsTable::configure($table);
  }

  /**
   * Pages de la ressource.
   *
   * @return array<string, mixed>
   */
  public static function getPages(): array
  {
    return [
      'index' => ListMobileApps::route('/'),
      'create' => CreateMobileApp::route('/create'),
      'edit' => EditMobileApp::route('/{record}/edit'),
    ];
  }
}
