<?php

namespace App\Filament\Resources\CoordinationMembers\Pages;

use App\Filament\Resources\CoordinationMembers\CoordinationMemberResource;
use App\Filament\Resources\Pages\ComcoListRecords;
use Filament\Actions\CreateAction;

/**
 * Liste des fiches Coordination.
 */
class ListCoordinationMembers extends ComcoListRecords
{
  protected static string $resource = CoordinationMemberResource::class;

  /**
   * Actions d'en-tête de la liste.
   *
   * @return list<CreateAction> Actions
   */
  protected function getHeaderActions(): array
  {
    return [
      CreateAction::make()
        ->label('Nouvelle fiche'),
    ];
  }
}
