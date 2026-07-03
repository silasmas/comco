<?php

namespace App\Filament\Resources\Users;

use App\Filament\Concerns\HasComcoResourceMeta;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
  use HasComcoResourceMeta;

  protected static ?string $model = User::class;

  protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

  protected static ?string $navigationLabel = 'Utilisateurs';

  protected static ?string $modelLabel = 'utilisateur';

  protected static ?string $pluralModelLabel = 'utilisateurs';

  protected static string|null|\UnitEnum $navigationGroup = 'Système';

  protected static ?int $navigationSort = 90;

  protected static string $resourceDescription = 'Gérez les comptes ayant accès au panneau d\'administration et les droits de super administrateur.';

  protected static ?string $tourStepId = 'users';

  protected static int $tourStepSort = 90;

  protected static array $tourStepFeatures = [
    'Créer des comptes administrateurs',
    'Attribuer le rôle super administrateur',
    'Modifier les identifiants de connexion',
  ];

  /**
   * Restreint l'accès aux super administrateurs.
   */
  public static function canAccess(): bool
  {
    $user = Auth::user();

    return $user instanceof User && $user->is_super_admin;
  }

  public static function form(Schema $schema): Schema
  {
    return UserForm::configure($schema);
  }

  public static function table(Table $table): Table
  {
    return UsersTable::configure($table);
  }

  public static function getPages(): array
  {
    return [
      'index' => ListUsers::route('/'),
      'create' => CreateUser::route('/create'),
      'edit' => EditUser::route('/{record}/edit'),
    ];
  }
}
