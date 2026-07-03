<?php

namespace App\Filament\Resources\EServiceSubmissions\Pages;

use App\Filament\Resources\EServiceSubmissions\EServiceSubmissionResource;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\Pages\ComcoEditRecord;

class EditEServiceSubmission extends ComcoEditRecord
{
    protected static string $resource = EServiceSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
