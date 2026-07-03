<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Filament\Support\ModerationTableActions;
use App\Models\Post;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PostsTable
{
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
        TextColumn::make('category')
          ->label('Catégorie')
          ->searchable(),
        TextColumn::make('author')
          ->label('Auteur')
          ->searchable(),
        IconColumn::make('is_published')
          ->label('Publié')
          ->boolean(),
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
      ->recordActions([
        EditAction::make(),
      ])
      ->toolbarActions([
        BulkActionGroup::make([
          DeleteBulkAction::make(),
        ]),
      ]);
  }
}
