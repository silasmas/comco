<?php

namespace App\Filament\Resources\ForumTopics;

use App\Filament\Concerns\HasComcoResourceMeta;
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

  protected static ?string $model = ForumTopic::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleBottomCenterText;

    protected static ?string $navigationLabel = 'Sujets';

    protected static ?string $modelLabel = 'sujet forum';

    protected static ?string $pluralModelLabel = 'sujets forum';

    protected static string|null|\UnitEnum $navigationGroup = 'Forum';

  protected static ?int $navigationSort = 1;

  protected static string $resourceDescription = 'Modérez les sujets du forum public : approuvez, rejetez ou fermez les discussions.';

  protected static ?string $tourStepId = 'forum-topics';

  protected static int $tourStepSort = 40;

  protected static array $tourStepFeatures = [
    'Approuver ou rejeter depuis le tableau',
    'Gérer les catégories de discussion',
    'Consulter les réponses associées',
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
        $count = static::getModel()::query()->where('status', 'pending')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }
}
