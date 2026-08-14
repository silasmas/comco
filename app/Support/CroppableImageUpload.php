<?php

namespace App\Support;

use Filament\Forms\Components\FileUpload;

/**
 * Configure un FileUpload Filament avec éditeur de rognage sans agrandissement.
 */
class CroppableImageUpload
{
  /**
   * Applique le rognage manuel à un champ image, sans upscale.
   *
   * @param  FileUpload  $upload  Champ fichier Filament
   * @param  list<string|null>  $aspectRatios  Ratios proposés (null = libre)
   * @return FileUpload Champ configuré
   */
  public static function apply(FileUpload $upload, array $aspectRatios = [null, '16:9', '21:9', '4:3', '1:1']): FileUpload
  {
    return $upload
      ->image()
      ->imageEditor()
      ->imageEditorAspectRatios($aspectRatios)
      ->imageEditorMode(2)
      ->automaticallyUpscaleImagesWhenResizing(false);
  }
}
