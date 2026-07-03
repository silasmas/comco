<?php

namespace App\Filament\Resources\NewsletterSubscribers;

use App\Filament\Concerns\HasComcoResourceMeta;
use App\Filament\Resources\NewsletterSubscribers\Pages\ListNewsletterSubscribers;
use App\Filament\Resources\NewsletterSubscribers\Tables\NewsletterSubscribersTable;
use App\Models\NewsletterSubscriber;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NewsletterSubscriberResource extends Resource
{
  use HasComcoResourceMeta;

  protected static ?string $model = NewsletterSubscriber::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelopeOpen;

    protected static ?string $navigationLabel = 'Newsletter';

    protected static ?string $modelLabel = 'abonné newsletter';

    protected static ?string $pluralModelLabel = 'abonnés newsletter';

    protected static string|null|\UnitEnum $navigationGroup = 'Soumissions';

  protected static ?int $navigationSort = 3;

  protected static string $resourceDescription = 'Consultez la liste des abonnés à la newsletter institutionnelle.';

  protected static ?string $tourStepId = 'newsletter';

  protected static int $tourStepSort = 32;

  protected static array $tourStepFeatures = [
    'Voir les emails inscrits',
    'Suivre la date d\'inscription',
    'Exporter la base d\'abonnés',
  ];

  public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return NewsletterSubscribersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNewsletterSubscribers::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
