<?php

namespace App\Filament\Resources\MobileApps\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

/**
 * Formulaire Filament d'une application APK.
 */
class MobileAppForm
{
  /**
   * Configure le schéma de formulaire.
   *
   * @param  Schema  $schema  Schéma Filament
   * @return Schema Schéma configuré
   */
  public static function configure(Schema $schema): Schema
  {
    return $schema
      ->components([
        Section::make('Application')
          ->columns(2)
          ->schema([
            TextInput::make('title')
              ->label('Titre')
              ->required()
              ->maxLength(255)
              ->live(onBlur: true)
              ->afterStateUpdated(function ($state, callable $set, ?\App\Models\MobileApp $record): void {
                if ($record) {
                  return;
                }
                if (filled($state)) {
                  $set('slug', \Illuminate\Support\Str::slug((string) $state));
                }
              })
              ->helperText('Nom affiché sur la page de téléchargement.')
              ->columnSpanFull(),
            TextInput::make('slug')
              ->label('Slug (URL)')
              ->required()
              ->maxLength(255)
              ->unique(ignoreRecord: true)
              ->helperText('Identifiant dans l’URL publique : /app-mobile/{slug}')
              ->columnSpan(1),
            TextInput::make('version')
              ->label('Version')
              ->maxLength(50)
              ->placeholder('1.0.0')
              ->helperText('Optionnel (ex. 1.0.0).')
              ->columnSpan(1),
            FileUpload::make('file_path')
              ->label('Fichier APK')
              ->disk('public')
              ->directory('mobile-apps')
              // Les navigateurs envoient souvent octet-stream / zip pour un .apk.
              ->acceptedFileTypes([
                'application/vnd.android.package-archive',
                'application/octet-stream',
                'application/java-archive',
                'application/zip',
                'application/x-zip-compressed',
              ])
              ->rules(['extensions:apk'])
              ->maxSize(1024 * 200)
              ->required()
              ->downloadable()
              ->openable(false)
              ->helperText('Fichier .apk uniquement (max. 200 Mo). L’upload peut prendre quelques secondes.')
              ->columnSpanFull(),
            Textarea::make('notes')
              ->label('Notes internes')
              ->rows(3)
              ->helperText('Visible uniquement dans l’admin.')
              ->columnSpanFull(),
            Toggle::make('is_active')
              ->label('Actif (lien public)')
              ->default(true)
              ->required()
              ->helperText('Désactivez pour désactiver le lien et le QR sans supprimer le fichier.'),
          ]),
        Section::make('Lien de partage & QR code')
          ->description('Disponible après enregistrement. Scannez le QR pour ouvrir la page de téléchargement.')
          ->visible(fn (?\App\Models\MobileApp $record): bool => $record !== null && filled($record->slug))
          ->schema([
            View::make('filament.mobile-apps.share-panel')
              ->viewData(fn (?\App\Models\MobileApp $record): array => [
                'record' => $record,
              ])
              ->columnSpanFull(),
          ]),
      ]);
  }
}
