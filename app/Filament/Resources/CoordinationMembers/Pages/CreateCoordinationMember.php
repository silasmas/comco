<?php

namespace App\Filament\Resources\CoordinationMembers\Pages;

use App\Filament\Resources\CoordinationMembers\CoordinationMemberResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Création d'une fiche Coordination.
 */
class CreateCoordinationMember extends CreateRecord
{
  protected static string $resource = CoordinationMemberResource::class;

  /**
   * Normalise les données avant création.
   *
   * @param  array<string, mixed>  $data  Données du formulaire
   * @return array<string, mixed> Données préparées
   */
  protected function mutateFormDataBeforeCreate(array $data): array
  {
    if (! empty($data['image'])) {
      $data['image_source'] = 'storage';
    }

    return $data;
  }
}
