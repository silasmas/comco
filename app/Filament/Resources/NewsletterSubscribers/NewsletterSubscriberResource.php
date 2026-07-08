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

    protected static string $resourceDescription = 'Consultez la liste des personnes inscrites à la newsletter institutionnelle depuis le pied de page ou les formulaires du site.';

    protected static ?string $tourStepId = 'newsletter';

    protected static int $tourStepSort = 22;

    protected static array $tourStepFeatures = [
        'Voir tous les e-mails inscrits avec la date d\'enregistrement',
        'Rechercher un abonné par adresse e-mail',
        'Suivre la croissance des inscriptions via le graphique du tableau de bord',
        'Exporter ou exploiter la liste pour vos campagnes (hors panneau)',
        'Constater qu\'aucune inscription ne peut être créée manuellement ici (flux public uniquement)',
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
