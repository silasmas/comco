<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\Post;
use App\Support\CroppableImageUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

/**
 * Schéma de formulaire des actualités et activités COMCO.
 */
class PostForm
{
  /**
   * Configure le formulaire d'un article.
   *
   * @param  Schema  $schema  Schéma Filament
   * @return Schema Schéma configuré
   */
  public static function configure(Schema $schema): Schema
  {
    return $schema
      ->components([
        Section::make('Identification')
          ->columns(2)
          ->schema([
            Select::make('content_type')
              ->label('Type de contenu')
              ->options(Post::contentTypeLabels())
              ->default(Post::TYPE_NEWS)
              ->required()
              ->helperText('Les activités apparaissent dans l\'onglet « Nos activités » de l\'accueil.')
              ->native(false),
            TextInput::make('title')
              ->label('Titre')
              ->required()
              ->columnSpanFull(),
            TextInput::make('slug')
              ->label('Slug')
              ->required(),
            TextInput::make('category')
              ->label('Catégorie')
              ->helperText('Ex. Sensibilisation, Formation, 2021…'),
            TextInput::make('author')
              ->label('Auteur'),
          ]),
        Section::make('Contenu')
          ->schema([
            Textarea::make('excerpt')
              ->label('Chapô')
              ->rows(3)
              ->columnSpanFull(),
            RichEditor::make('body')
              ->label('Texte de description')
              ->helperText('Utilisez le bouton « Justifier » pour aligner le texte.')
              ->columnSpanFull(),
          ]),
        Section::make('Médias')
          ->description('La vignette (image à la une) sert aussi d\'afficheur (poster) pour la vidéo.')
          ->schema([
            CroppableImageUpload::apply(
              FileUpload::make('featured_image')
                ->label('Image à la une / vignette')
                ->directory('posts/images')
                ->disk('public')
                ->helperText('Affichée en liste et utilisée comme vignette de la vidéo.')
                ->columnSpanFull(),
              [null, '16:9', '4:3', '1:1']
            ),
            FileUpload::make('featured_video')
              ->label('Vidéo')
              ->acceptedFileTypes([
                'video/mp4',
                'video/webm',
                'video/ogg',
                'video/quicktime',
              ])
              ->maxSize(102400)
              ->directory('posts/videos')
              ->disk('public')
              ->live()
              ->helperText('Formats recommandés : MP4 ou WebM (max. ~100 Mo).')
              ->columnSpanFull(),
          ]),
        Section::make('Mise en avant (modale d\'entrée)')
          ->description('Une seule mise en avant active à la fois. À l\'arrivée sur le site, une modale présente ce contenu ; après fermeture, un bouton flottant clignotant reste visible.')
          ->columns(2)
          ->schema([
            Toggle::make('is_spotlight')
              ->label('Mettre en avant sur le site')
              ->helperText('Affiche la modale dès l\'entrée sur le site public.')
              ->live()
              ->columnSpanFull(),
            Textarea::make('spotlight_text')
              ->label('Texte de la modale')
              ->rows(4)
              ->helperText('Si vide, le chapô est utilisé.')
              ->visible(fn (Get $get): bool => (bool) $get('is_spotlight'))
              ->columnSpanFull(),
            FileUpload::make('spotlight_images')
              ->label('Images de la modale')
              ->image()
              ->multiple()
              ->reorderable()
              ->maxFiles(8)
              ->directory('posts/spotlight')
              ->disk('public')
              ->helperText('Une image = fixe ; plusieurs = diaporama. Sinon, la vignette à la une est utilisée.')
              ->visible(fn (Get $get): bool => (bool) $get('is_spotlight'))
              ->columnSpanFull(),
            Select::make('spotlight_video_mode')
              ->label('Affichage de la vidéo')
              ->options(Post::spotlightVideoModeLabels())
              ->default(Post::VIDEO_MODE_NORMAL)
              ->native(false)
              ->helperText('Utilise la vidéo téléversée dans la section Médias.')
              ->visible(fn (Get $get): bool => (bool) $get('is_spotlight') && filled($get('featured_video')))
              ->columnSpanFull(),
          ]),
        Section::make('Publication & SEO')
          ->columns(2)
          ->description('Laissez « Publié » désactivé pour préparer le contenu, puis utilisez Prévisualiser avant mise en ligne.')
          ->schema([
            TextInput::make('meta_title')
              ->label('Titre SEO'),
            Textarea::make('meta_description')
              ->label('Description SEO')
              ->columnSpanFull(),
            Toggle::make('is_published')
              ->label('Publié (visible sur le site)')
              ->helperText('Désactivé = brouillon invisible au public.')
              ->required(),
            DateTimePicker::make('published_at')
              ->label('Date de publication'),
          ]),
      ]);
  }
}
