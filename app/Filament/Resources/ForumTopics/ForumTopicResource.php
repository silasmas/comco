<?php

namespace App\Filament\Resources\ForumTopics;

use App\Filament\Concerns\HasComcoResourceMeta;
use App\Filament\Concerns\HasSafeNavigationBadge;
use App\Filament\Resources\ForumTopics\Pages\CreateForumTopic;
use App\Filament\Resources\ForumTopics\Pages\EditForumTopic;
use App\Filament\Resources\ForumTopics\Pages\ListForumTopics;
use App\Filament\Resources\ForumTopics\RelationManagers\RepliesRelationManager;
use App\Filament\Resources\ForumTopics\Schemas\ForumTopicForm;
use App\Filament\Resources\ForumTopics\Tables\ForumTopicsTable;
use App\Models\ForumTopic;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ForumTopicResource extends Resource
{
    use HasComcoResourceMeta;
    use HasSafeNavigationBadge;

    protected static ?string $model = ForumTopic::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleBottomCenterText;

    protected static ?string $navigationLabel = 'Sujets';

    protected static ?string $modelLabel = 'sujet forum';

    protected static ?string $pluralModelLabel = 'sujets forum';

    protected static string|null|\UnitEnum $navigationGroup = 'Forum';

    protected static ?int $navigationSort = 1;

    protected static string $resourceDescription = 'Modérez les discussions ouvertes sur le forum public : validation, rejet, fermeture et suivi des réponses associées.';

    protected static ?string $tourStepId = 'forum-topics';

    protected static int $tourStepSort = 30;

    protected static array $tourStepFeatures = [
        'Approuver ou rejeter un sujet avant sa publication visible',
        'Créer un sujet officiel au nom de la COMCO (catégorie, titre, contenu)',
        'Fermer une discussion devenue inactive ou inappropriée',
        'Consulter les réponses liées depuis l\'onglet « Réponses » de la fiche sujet',
        'Filtrer les sujets en attente grâce au badge orange du menu',
        'Organiser les échanges par catégorie thématique du forum',
    ];

    public static function form(Schema $schema): Schema
    {
        return ForumTopicForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ForumTopicsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RepliesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListForumTopics::route('/'),
            'create' => CreateForumTopic::route('/create'),
            'edit' => EditForumTopic::route('/{record}/edit'),
        ];
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
