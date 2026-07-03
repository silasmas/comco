<?php

namespace App\Filament\Resources\ContactMessages\Pages;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Resources\Pages\ComcoListRecords;

class ListContactMessages extends ComcoListRecords
{
    protected static string $resource = ContactMessageResource::class;
}
