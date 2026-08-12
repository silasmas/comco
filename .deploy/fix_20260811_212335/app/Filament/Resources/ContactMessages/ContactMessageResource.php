<?php

namespace App\Filament\Resources\ContactMessages;

use App\Filament\Concerns\HasComcoResourceMeta;
use App\Filament\Concerns\HasSafeNavigationBadge;
use App\Filament\Resources\ContactMessages\Pages\EditContactMessage;
use App\Filament\Resources\ContactMessages\Pages\ListContactMessages;
use App\Filament\Resources\ContactMessages\Schemas\ContactMessageForm;
use App\Filament\Resources\ContactMessages\Tables\ContactMessagesTable;
use App\Models\ContactMessage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    use HasComcoResourceMeta;
    use HasSafeNavigationBadge;

    protected static ?string $model = ContactMessage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $navigationLabel = 'Messages contact';

    protected static ?string $modelLabel = 'message contact';

    protected static ?string $pluralModelLabel = 'messages contact';

    protected static string|null|\UnitEnum $navigationGroup = 'Soumissions';

    protected static ?int $navigationSort = 2;

    protected static string $resourceDescription = 'Lisez et traitez les messages reçus via le formulaire de contact public : coordonnées, objet et corps du message.';

    protected static ?string $tourStepId = 'contact-messages';

    protected static int $tourStepSort = 21;

    protected static array $tourStepFeatures = [
        'Consulter le détail de chaque message (nom, e-mail, téléphone, objet, texte)',
        'Filtrer et trier les messages par date ou statut de traitement',
        'Valider ou rejeter un message depuis le tableau ou la fiche d\'édition',
        'Repérer les messages non lus grâce au badge du menu « Messages contact »',
        'Conserver l\'historique des échanges sans suppression automatique',
        'Bénéficier d\'une confirmation e-mail envoyée au visiteur après envoi',
    ];

    public static function form(Schema $schema): Schema
    {
        return ContactMessageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContactMessagesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContactMessages::route('/'),
            'edit' => EditContactMessage::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::countNavigationBadge(static::getModel(), ['status' => 'pending']);
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }
}
