<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Membre ou bloc de contenu de la rubrique Coordination.
 */
class CoordinationMember extends Model
{
  /**
   * Attributs assignables en masse.
   *
   * @var list<string>
   */
  protected $fillable = [
    'title',
    'summary',
    'image',
    'image_source',
    'link_url',
    'link_label',
    'sort_order',
    'is_active',
  ];

  /**
   * Cast des attributs du modèle.
   *
   * @return array<string, string> Casts
   */
  protected function casts(): array
  {
    return [
      'is_active' => 'boolean',
      'sort_order' => 'integer',
    ];
  }

  /**
   * Scope des fiches actives.
   *
   * @param  Builder<CoordinationMember>  $query  Requête Eloquent
   * @return Builder<CoordinationMember> Requête filtrée
   */
  public function scopeActive(Builder $query): Builder
  {
    return $query->where('is_active', true);
  }

  /**
   * Scope d'ordre d'affichage.
   *
   * @param  Builder<CoordinationMember>  $query  Requête Eloquent
   * @return Builder<CoordinationMember> Requête triée
   */
  public function scopeOrdered(Builder $query): Builder
  {
    return $query->orderBy('sort_order')->orderBy('title');
  }
}
