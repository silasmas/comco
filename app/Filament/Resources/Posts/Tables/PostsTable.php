<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

/**
 * Table de liste des actualités et activités.
 */
class PostsTable
{
  /**
   * Configure la table des posts.
   *
   * @param  Table  $table  Table Filament
   * @return Table Table configurée
   */
  public static function configure(Table $table): Table
  {
    return $table
      ->defaultSort('created_at', 'desc')
      ->columns([
        ImageColumn::make('featured_image')
          ->label('Image')
          ->height(56)
          ->width(56)
          ->checkFileExistence(false)
          ->defaultImageUrl(fn (): string => postImage(null))
          ->state(fn (Post $record): string => postImage($record->featured_image)),
        TextColumn::make('title')
          ->label('Titre')
          ->searchable()
          ->limit(40),
        TextColumn::make('content_type')
          ->label('Type')
          ->badge()
          ->formatStateUsing(fn (?string $state): string => Post::contentTypeLabels()[$state] ?? (string) $state)
          ->color(fn (?string $state): string => $state === Post::TYPE_ACTIVITY ? 'warning' : 'info'),
        TextColumn::make('category')
          ->label('Catégorie')
          ->searchable(),
        TextColumn::make('author')
          ->label('Auteur')
          ->searchable()
          ->toggleable(isToggledHiddenByDefault: true),
        IconColumn::make('is_published')
          ->label('Publié')
          ->boolean(),
        IconColumn::make('is_spotlight')
          ->label('En avant')
          ->boolean()
          ->trueIcon(Heroicon::OutlinedSparkles)
          ->falseIcon(Heroicon::OutlinedMinus),
        IconColumn::make('featured_video')
          ->label('Vidéo')
          ->boolean()
          ->getStateUsing(fn (Post $record): bool => $record->hasVideo())
          ->trueIcon(Heroicon::OutlinedPlayCircle)
          ->falseIcon(Heroicon::OutlinedMinus),
        TextColumn::make('published_at')
          ->label('Publié le')
          ->dateTime('d/m/Y H:i')
          ->sortable(),
        TextColumn::make('created_at')
          ->label('Créé le')
          ->dateTime('d/m/Y H:i')
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->filters([
        SelectFilter::make('content_type')
          ->label('Type')
          ->options(Post::contentTypeLabels()),
        TernaryFilter::make('is_published')
          ->label('Publié'),
        TernaryFilter::make('is_spotlight')
          ->label('En avant'),
      ])
      ->recordActions([
        Action::make('preview')
          ->label('Aperçu')
          ->icon(Heroicon::OutlinedEye)
          ->url(fn (Post $record): string => route('posts.preview', $record->slug))
          ->openUrlInNewTab(),
        EditAction::make(),
      ])
      ->toolbarActions([
        BulkActionGroup::make([
          DeleteBulkAction::make(),
        ]),
      ]);
  }
}
