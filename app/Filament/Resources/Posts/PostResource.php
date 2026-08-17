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

    protected static ?string $navigationLabel = 'Actualités & Activités';

    protected static string|null|\UnitEnum $navigationGroup = 'Contenu';

    protected static ?int $navigationSort = 1;

    protected static string $resourceDescription = 'Rédigez actualités et activités avec image, vidéo, brouillon, prévisualisation et mise en avant (modale d\'entrée + bouton flottant).';

    protected static ?string $tourStepId = 'posts';

    protected static int $tourStepSort = 10;

    protected static array $tourStepFeatures = [
        'Créer une actualité ou une activité avec titre, chapô, corps HTML et catégorie',
        'Téléverser une image à la une, une galerie de mise en avant et une vidéo optionnelle',
        'Mettre un contenu en avant : modale à l\'entrée du site, puis bouton flottant clignotant',
        'Choisir l\'affichage vidéo classique ou style story dans la modale',
        'Préparer en brouillon puis prévisualiser avant publication sur le site',
        'Afficher les activités dans l\'onglet « Nos activités » de la page d\'accueil',
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
