<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Pages\ComcoEditRecord;
use App\Filament\Resources\Users\UserResource;

class EditUser extends ComcoEditRecord
{
  protected static string $resource = UserResource::class;
}
