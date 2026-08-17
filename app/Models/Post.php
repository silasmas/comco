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

  public const VIDEO_MODE_NORMAL = 'normal';

  public const VIDEO_MODE_STORY = 'story';

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
    'spotlight_images',
    'spotlight_video_mode',
    'spotlight_text',
    'meta_title',
    'meta_description',
    'is_published',
    'is_spotlight',
    'published_at',
  ];

  /**
   * Initialise les événements du modèle.
   */
  protected static function booted(): void
  {
    static::saved(function (Post $post): void {
      if (! $post->is_spotlight) {
        return;
      }

      static::query()
        ->whereKeyNot($post->getKey())
        ->where('is_spotlight', true)
        ->update(['is_spotlight' => false]);
    });
  }

  /**
   * Cast des attributs du modèle.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'is_published' => 'boolean',
      'is_spotlight' => 'boolean',
      'spotlight_images' => 'array',
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
   * Libellés des modes d'affichage vidéo en modale.
   *
   * @return array<string, string>
   */
  public static function spotlightVideoModeLabels(): array
  {
    return [
      self::VIDEO_MODE_NORMAL => 'Vidéo classique (lecteur avec contrôles)',
      self::VIDEO_MODE_STORY => 'Style story (plein cadre, lecture auto)',
    ];
  }

  /**
   * Retourne le contenu publié actuellement mis en avant, s'il existe.
   *
   * @return self|null Post spotlight ou null
   */
  public static function currentSpotlight(): ?self
  {
    return static::query()
      ->published()
      ->where('is_spotlight', true)
      ->latest('published_at')
      ->first();
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
   * Indique si la vidéo doit s'afficher en mode story.
   *
   * @return bool True si mode story
   */
  public function usesStoryVideo(): bool
  {
    return ($this->spotlight_video_mode ?: self::VIDEO_MODE_NORMAL) === self::VIDEO_MODE_STORY;
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
   * URLs des images de la modale (galerie ou vignette de secours).
   *
   * @return list<string>
   */
  public function spotlightImageUrls(): array
  {
    $images = is_array($this->spotlight_images) ? $this->spotlight_images : [];

    if ($images === [] && filled($this->featured_image)) {
      $images = [$this->featured_image];
    }

    $urls = [];
    foreach ($images as $path) {
      if (! filled($path)) {
        continue;
      }
      $urls[] = postImage((string) $path);
    }

    return array_values($urls);
  }

  /**
   * Texte présenté dans la modale de mise en avant.
   *
   * @return string Texte de présentation
   */
  public function spotlightPresentationText(): string
  {
    if (filled($this->spotlight_text)) {
      return (string) $this->spotlight_text;
    }

    return (string) ($this->excerpt ?? '');
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
