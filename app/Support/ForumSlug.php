<?php

namespace App\Support;

use App\Models\ForumTopic;
use Illuminate\Support\Str;

/**
 * Génère des slugs uniques pour les sujets du forum.
 */
class ForumSlug
{
  /**
   * Crée un slug unique à partir d'un titre.
   *
   * @param string $title Titre du sujet
   * @return string Slug unique
   */
  public static function fromTitle(string $title): string
  {
    $baseSlug = Str::slug($title);
    $slug = $baseSlug !== '' ? $baseSlug : 'sujet';
    $counter = 1;

    while (ForumTopic::query()->where('slug', $slug)->exists()) {
      $counter++;
      $slug = "{$baseSlug}-{$counter}";
    }

    return $slug;
  }
}
