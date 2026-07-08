<?php

namespace App\Filament\Resources\Posts;

use App\Filament\Concerns\HasComcoResourceMeta;
use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Filament\Resources\Posts\Schemas\PostForm;
use App\Filament\Resources\Posts\Tables\PostsTable;
use App\Models\Post;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PostResource extends Resource
{
    use HasComcoResourceMeta;

    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Actualités';

    protected static string|null|\UnitEnum $navigationGroup = 'Contenu';

    protected static ?int $navigationSort = 1;

    protected static string $resourceDescription = 'Publiez les actualités et communiqués de la COMCO : rédaction, visuel, catégorie, extrait et date de publication sur le site public.';

    protected static ?string $tourStepId = 'posts';

    protected static int $tourStepSort = 10;

    protected static array $tourStepFeatures = [
        'Créer un article avec titre, chapô, corps HTML et catégorie thématique',
        'Téléverser ou sélectionner l\'image d\'illustration affichée en liste et en détail',
        'Programmer ou modifier la date de publication et l\'état publié / brouillon',
        'Organiser les contenus par catégorie (concurrence, consommateurs, concentrations…)',
        'Modifier un communiqué existant sans toucher à sa structure URL',
        'Retrouver les articles récents dans le bloc « À la une » de la page d\'accueil',
    ];

    public static function form(Schema $schema): Schema
    {
        return PostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
