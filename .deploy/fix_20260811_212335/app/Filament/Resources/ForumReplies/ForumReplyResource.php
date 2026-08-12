<?php

namespace App\Filament\Resources\ForumReplies;

use App\Filament\Concerns\HasComcoResourceMeta;
use App\Filament\Concerns\HasSafeNavigationBadge;
use App\Filament\Resources\ForumReplies\Pages\EditForumReply;
use App\Filament\Resources\ForumReplies\Pages\ListForumReplies;
use App\Filament\Resources\ForumReplies\Schemas\ForumReplyForm;
use App\Filament\Resources\ForumReplies\Tables\ForumRepliesTable;
use App\Models\ForumReply;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ForumReplyResource extends Resource
{
    use HasComcoResourceMeta;
    use HasSafeNavigationBadge;

    protected static ?string $model = ForumReply::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftEllipsis;

    protected static ?string $navigationLabel = 'Réponses';

    protected static ?string $modelLabel = 'réponse forum';

    protected static ?string $pluralModelLabel = 'réponses forum';

    protected static string|null|\UnitEnum $navigationGroup = 'Forum';

    protected static ?int $navigationSort = 2;

    protected static string $resourceDescription = 'Modérez les contributions des visiteurs au forum : chaque réponse est soumise à validation avant affichage public.';

    protected static ?string $tourStepId = 'forum-replies';

    protected static int $tourStepSort = 31;

    protected static array $tourStepFeatures = [
        'Lire le texte complet de chaque réponse et son auteur',
        'Approuver ou rejeter en masse depuis le tableau de liste',
        'Retrouver le sujet parent depuis la fiche de la réponse',
        'Traiter en priorité les réponses en attente (badge orange du menu)',
        'Garantir un forum modéré sans publication automatique',
    ];

    public static function form(Schema $schema): Schema
    {
        return ForumReplyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ForumRepliesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListForumReplies::route('/'),
            'edit' => EditForumReply::route('/{record}/edit'),
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
