<?php

namespace App\Filament\Resources\MobileApps\Pages;

use App\Filament\Resources\MobileApps\MobileAppResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

/**
 * Édition d'une application APK (lien + QR code).
 */
class EditMobileApp extends EditRecord
{
  protected static string $resource = MobileAppResource::class;

  /**
   * Actions d'en-tête.
   *
   * @return list<Action>
   */
  protected function getHeaderActions(): array
  {
    return [
      Action::make('openShare')
        ->label('Ouvrir la page publique')
        ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
        ->url(fn (): string => $this->getRecord()->shareUrl())
        ->openUrlInNewTab()
        ->visible(fn (): bool => $this->getRecord()->is_active),
      DeleteAction::make(),
    ];
  }

  /**
   * Prépare les données avant sauvegarde.
   *
   * @param  array<string, mixed>  $data  Données formulaire
   * @return array<string, mixed> Données normalisées
   */
  protected function mutateFormDataBeforeSave(array $data): array
  {
    if (blank($data['slug'] ?? null) && filled($data['title'] ?? null)) {
      $data['slug'] = Str::slug((string) $data['title']);
    }

    $data['slug'] = Str::slug((string) ($data['slug'] ?? 'app'));

    $path = $data['file_path'] ?? null;
    if (filled($path) && is_string($path) && Storage::disk('public')->exists($path)) {
      $data['file_size'] = Storage::disk('public')->size($path);
      $data['original_name'] = basename($path);
    }

    return $data;
  }
}
