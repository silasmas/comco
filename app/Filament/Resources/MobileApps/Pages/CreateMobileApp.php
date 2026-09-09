<?php

namespace App\Filament\Resources\MobileApps\Pages;

use App\Filament\Resources\MobileApps\MobileAppResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Filament\Resources\Pages\CreateRecord;

/**
 * Création d'une application APK.
 */
class CreateMobileApp extends CreateRecord
{
  protected static string $resource = MobileAppResource::class;

  /**
   * Prépare les données avant création.
   *
   * @param  array<string, mixed>  $data  Données formulaire
   * @return array<string, mixed> Données normalisées
   */
  protected function mutateFormDataBeforeCreate(array $data): array
  {
    if (blank($data['slug'] ?? null) && filled($data['title'] ?? null)) {
      $data['slug'] = Str::slug((string) $data['title']);
    }

    $data['slug'] = Str::slug((string) ($data['slug'] ?? 'app'));

    return $this->syncFileMeta($data);
  }

  /**
   * Enrichit les métadonnées du fichier uploadé.
   *
   * @param  array<string, mixed>  $data  Données
   * @return array<string, mixed>
   */
  protected function syncFileMeta(array $data): array
  {
    $path = $data['file_path'] ?? null;
    if (blank($path) || ! is_string($path)) {
      return $data;
    }

    if (Storage::disk('public')->exists($path)) {
      $data['file_size'] = Storage::disk('public')->size($path);
      $data['original_name'] = basename($path);
    }

    return $data;
  }
}
