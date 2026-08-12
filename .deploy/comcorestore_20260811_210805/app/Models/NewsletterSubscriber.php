<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Abonné à la newsletter institutionnelle COMCO.
 */
class NewsletterSubscriber extends Model
{
  /**
   * Attributs assignables en masse.
   *
   * @var list<string>
   */
  protected $fillable = [
    'email',
    'subscribed_at',
  ];

  /**
   * Casts des attributs du modèle.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'subscribed_at' => 'datetime',
    ];
  }
}
