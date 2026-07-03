<?php

namespace App\Filament\Resources\ContactMessages;

use App\Filament\Concerns\HasComcoResourceMeta;
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

  protected static ?string $model = ContactMessage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $navigationLabel = 'Messages contact';

    protected static ?string $modelLabel = 'message contact';

    protected static ?string $pluralModelLabel = 'messages contact';

    protected static string|null|\UnitEnum $navigationGroup = 'Soumissions';

  protected static ?int $navigationSort = 2;

  protected static string $resourceDescription = 'Consultez et traitez les messages envoyés via le formulaire de contact du site.';

  protected static ?string $tourStepId = 'contact-messages';

  protected static int $tourStepSort = 30;

  protected static array $tourStepFeatures = [
    'Lire les messages reçus',
    'Valider ou rejeter depuis le tableau',
    'Suivre le statut de traitement',
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
        $count = static::getModel()::query()->where('status', 'pending')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }
}
