<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle représentant un article, une actualité ou une activité institutionnelle.
 */
class Post extends Model
{
  public const TYPE_NEWS = 'news';

  public const TYPE_ACTIVITY = 'activity';

  /**
   * Attributs assignables en masse.
   *
   * @var list<string>
   */
  protected $fillable = [
    'title',
    'slug',
    'content_type',
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
   * Libellés des types de contenu pour l'administration.
   *
   * @return array<string, string>
   */
  public static function contentTypeLabels(): array
  {
    return [
      self::TYPE_NEWS => 'Actualité',
      self::TYPE_ACTIVITY => 'Activité',
    ];
  }

  /**
   * Indique si le contenu est une activité.
   *
   * @return bool True si type activité
   */
  public function isActivity(): bool
  {
    return $this->content_type === self::TYPE_ACTIVITY;
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
   * @param  Builder<Post>  $query  Requête Eloquent
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

  /**
   * Scope pour filtrer par type de contenu.
   *
   * @param  Builder<Post>  $query  Requête Eloquent
   * @param  string  $type  Type news|activity
   * @return Builder<Post> Requête filtrée
   */
  public function scopeOfType(Builder $query, string $type): Builder
  {
    return $query->where('content_type', $type);
  }
}
