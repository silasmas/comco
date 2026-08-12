<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Password;

class UserForm
{
  /**
   * Configure le formulaire utilisateur admin.
   */
  public static function configure(Schema $schema): Schema
  {
    return $schema
      ->components([
        TextInput::make('name')
          ->label('Nom complet')
          ->required()
          ->maxLength(255),
        TextInput::make('email')
          ->label('Email')
          ->email()
          ->required()
          ->maxLength(255)
          ->unique(ignoreRecord: true),
        TextInput::make('password')
          ->label('Mot de passe')
          ->password()
          ->revealable()
          ->rule(Password::defaults())
          ->dehydrated(fn (?string $state): bool => filled($state))
          ->required(fn (string $operation): bool => $operation === 'create'),
        Toggle::make('is_super_admin')
          ->label('Super administrateur')
          ->helperText('Accès complet, y compris la gestion des utilisateurs et l\'installation production.'),
      ]);
  }
}
