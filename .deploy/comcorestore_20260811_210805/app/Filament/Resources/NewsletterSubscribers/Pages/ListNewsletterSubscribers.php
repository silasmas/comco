<?php

namespace App\Filament\Resources\NewsletterSubscribers\Pages;

use App\Filament\Resources\NewsletterSubscribers\NewsletterSubscriberResource;
use App\Filament\Resources\Pages\ComcoListRecords;

class ListNewsletterSubscribers extends ComcoListRecords
{
    protected static string $resource = NewsletterSubscriberResource::class;
}
