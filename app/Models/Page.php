<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle représentant une page de contenu institutionnelle.
 */
class Page extends Model
{
  /**
   * Attributs assignables en masse.
   *
   * @var list<string>
   */
  protected $fillable = [
    'title',
    'section',
    'slug',
    'excerpt',
    'body',
    'meta_title',
    'meta_description',
    'is_published',
    'published_at',
  ];

  /**
   * Cast des attributs du modèle.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'is_published' => 'boolean',
      'published_at' => 'datetime',
    ];
  }

  /**
   * Scope pour ne retourner que les pages publiées.
   *
   * @param Builder<Page> $query Requête Eloquent
   * @return Builder<Page> Requête filtrée
   */
  public function scopePublished(Builder $query): Builder
  {
    return $query
      ->where('is_published', true)
      ->where(function (Builder $builder): void {
        $builder
          ->whereNull('published_at')
          ->orWhere('published_at', '<=', now());
      });
  }
}
