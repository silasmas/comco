<?php

namespace App\Filament\Resources\CoordinationMembers\Pages;

use App\Filament\Resources\CoordinationMembers\CoordinationMemberResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

/**
 * Édition d'une fiche Coordination.
 */
class EditCoordinationMember extends EditRecord
{
  protected static string $resource = CoordinationMemberResource::class;

  /**
   * Actions d'en-tête.
   *
   * @return list<DeleteAction> Actions
   */
  protected function getHeaderActions(): array
  {
    return [
      DeleteAction::make(),
    ];
  }

  /**
   * Normalise les données avant sauvegarde.
   *
   * @param  array<string, mixed>  $data  Données du formulaire
   * @return array<string, mixed> Données préparées
   */
  protected function mutateFormDataBeforeSave(array $data): array
  {
    if (! empty($data['image'])) {
      $data['image_source'] = 'storage';
    }

    return $data;
  }
}
