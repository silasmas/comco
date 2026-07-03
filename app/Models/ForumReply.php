<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Réponse publiée sur un sujet du forum COMCO.
 */
class ForumReply extends Model
{
  /**
   * Attributs assignables en masse.
   *
   * @var list<string>
   */
  protected $fillable = [
    'forum_topic_id',
    'body',
    'author_name',
    'author_email',
    'status',
    'user_id',
  ];

  /**
   * Sujet parent de la réponse.
   *
   * @return BelongsTo<ForumTopic, $this>
   */
  public function topic(): BelongsTo
  {
    return $this->belongsTo(ForumTopic::class, 'forum_topic_id');
  }

  /**
   * Filtre les réponses approuvées.
   *
   * @param Builder<ForumReply> $query Requête Eloquent
   * @return Builder<ForumReply> Requête filtrée
   */
  public function scopeApproved(Builder $query): Builder
  {
    return $query->where('status', 'approved');
  }

  /**
   * Libellé lisible du statut.
   *
   * @return string Nom du statut
   */
  public function statusLabel(): string
  {
    return config("forum.statuses.reply.{$this->status}", $this->status);
  }
}
