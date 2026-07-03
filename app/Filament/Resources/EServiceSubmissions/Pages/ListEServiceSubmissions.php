<?php

namespace App\Filament\Resources\EServiceSubmissions\Pages;

use App\Filament\Resources\EServiceSubmissions\EServiceSubmissionResource;
use App\Filament\Resources\Pages\ComcoListRecords;

class ListEServiceSubmissions extends ComcoListRecords
{
    protected static string $resource = EServiceSubmissionResource::class;
}
