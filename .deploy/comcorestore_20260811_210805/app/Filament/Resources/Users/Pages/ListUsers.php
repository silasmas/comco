<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Pages\ComcoListRecords;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;

class ListUsers extends ComcoListRecords
{
  protected static string $resource = UserResource::class;

  protected function getHeaderActions(): array
  {
    return [
      CreateAction::make(),
    ];
  }
}
