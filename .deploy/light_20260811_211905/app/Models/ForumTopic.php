<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Sujet de discussion du forum public COMCO.
 */
class ForumTopic extends Model
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
    'body',
    'author_name',
    'author_email',
    'status',
    'views',
    'user_id',
  ];

  /**
   * Réponses associées au sujet.
   *
   * @return HasMany<ForumReply, $this>
   */
  public function replies(): HasMany
  {
    return $this->hasMany(ForumReply::class);
  }

  /**
   * Réponses approuvées visibles publiquement.
   *
   * @return HasMany<ForumReply, $this>
   */
  public function approvedReplies(): HasMany
  {
    return $this->replies()->where('status', 'approved');
  }

  /**
   * Filtre les sujets approuvés.
   *
   * @param Builder<ForumTopic> $query Requête Eloquent
   * @return Builder<ForumTopic> Requête filtrée
   */
  public function scopeApproved(Builder $query): Builder
  {
    return $query->where('status', 'approved');
  }

  /**
   * Libellé lisible de la catégorie.
   *
   * @return string Nom de la catégorie
   */
  public function categoryLabel(): string
  {
    return config("forum.categories.{$this->category}", $this->category);
  }

  /**
   * Libellé lisible du statut.
   *
   * @return string Nom du statut
   */
  public function statusLabel(): string
  {
    return config("forum.statuses.topic.{$this->status}", $this->status);
  }
}
