<?php

namespace App\Filament\Resources\MobileApps\Pages;

use App\Filament\Resources\MobileApps\MobileAppResource;
use App\Filament\Resources\Pages\ComcoListRecords;
use Filament\Actions\CreateAction;

/**
 * Liste des applications APK.
 */
class ListMobileApps extends ComcoListRecords
{
  protected static string $resource = MobileAppResource::class;

  /**
   * Actions d'en-tête.
   *
   * @return list<CreateAction>
   */
  protected function getHeaderActions(): array
  {
    return [
      CreateAction::make()
        ->label('Nouvelle application APK'),
    ];
  }
}
