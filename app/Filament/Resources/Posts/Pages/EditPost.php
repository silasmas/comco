<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Pages\ComcoEditRecord;
use App\Filament\Resources\Posts\PostResource;
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;

/**
 * Page d'édition d'une actualité ou activité.
 */
class EditPost extends ComcoEditRecord
{
  protected static string $resource = PostResource::class;

  /**
   * Actions d'en-tête : prévisualisation et suppression.
   *
   * @return list<Action|DeleteAction>
   */
  protected function getHeaderActions(): array
  {
    /** @var Post $record */
    $record = $this->getRecord();

    return [
      Action::make('preview')
        ->label('Prévisualiser')
        ->icon(Heroicon::OutlinedEye)
        ->color('gray')
        ->url(route('posts.preview', $record->slug))
        ->openUrlInNewTab(),
      DeleteAction::make(),
    ];
  }
}
