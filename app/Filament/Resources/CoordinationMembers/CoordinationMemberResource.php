<?php

namespace App\Filament\Resources\CoordinationMembers;

use App\Filament\Concerns\HasComcoResourceMeta;
use App\Filament\Resources\CoordinationMembers\Pages\CreateCoordinationMember;
use App\Filament\Resources\CoordinationMembers\Pages\EditCoordinationMember;
use App\Filament\Resources\CoordinationMembers\Pages\ListCoordinationMembers;
use App\Filament\Resources\CoordinationMembers\Schemas\CoordinationMemberForm;
use App\Filament\Resources\CoordinationMembers\Tables\CoordinationMembersTable;
use App\Models\CoordinationMember;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * Ressource Filament des contenus de la rubrique Coordination.
 */
class CoordinationMemberResource extends Resource
{
  use HasComcoResourceMeta;

  protected static ?string $model = CoordinationMember::class;

  protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

  protected static ?string $navigationLabel = 'Coordination';

  protected static ?string $modelLabel = 'fiche coordination';

  protected static ?string $pluralModelLabel = 'fiches coordination';

  protected static string|\UnitEnum|null $navigationGroup = 'Contenu du site';

  protected static ?int $navigationSort = 7;

  protected static string $resourceDescription = 'Gérez les cartes affichées dans la rubrique Présentation → Coordination : titre, résumé, image, lien et ordre.';

  protected static ?string $tourStepId = 'coordination-members';

  protected static int $tourStepSort = 7;

  protected static array $tourStepFeatures = [
    'Ajouter une fiche avec image, titre et résumé',
    'Définir un lien « En détail » vers une page ou une URL',
    'Réordonner les cartes via le champ Ordre',
    'Activer ou désactiver une fiche sans la supprimer',
  ];

  /**
   * Titre affiché dans la visite guidée.
   *
   * @return string|null Titre du tour
   */
  public static function getTourStepTitle(): ?string
  {
    return 'Coordination';
  }

  /**
   * Configure le formulaire Filament.
   *
   * @param  Schema  $schema  Schéma Filament
   * @return Schema Schéma configuré
   */
  public static function form(Schema $schema): Schema
  {
    return CoordinationMemberForm::configure($schema);
  }

  /**
   * Configure le tableau Filament.
   *
   * @param  Table  $table  Table Filament
   * @return Table Table configurée
   */
  public static function table(Table $table): Table
  {
    return CoordinationMembersTable::configure($table);
  }

  /**
   * Pages de la ressource.
   *
   * @return array<string, mixed> Routes de pages
   */
  public static function getPages(): array
  {
    return [
      'index' => ListCoordinationMembers::route('/'),
      'create' => CreateCoordinationMember::route('/create'),
      'edit' => EditCoordinationMember::route('/{record}/edit'),
    ];
  }
}
