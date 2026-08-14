<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle représentant un article ou une actualité institutionnelle.
 */
class Post extends Model
{
  /**
   * Attributs assignables en masse.
   *
   * @var list<string>
   */
  protected $fillable = [
    'title',
    'slug',
    'category',
    'author',
    'excerpt',
    'body',
    'featured_image',
    'featured_video',
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
   * Indique si l'actualité a une vidéo associée.
   *
   * @return bool True si une vidéo est définie
   */
  public function hasVideo(): bool
  {
    return filled($this->featured_video);
  }

  /**
   * Scope pour ne retourner que les articles publiés.
   *
   * @param Builder<Post> $query Requête Eloquent
   * @return Builder<Post> Requête filtrée
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
