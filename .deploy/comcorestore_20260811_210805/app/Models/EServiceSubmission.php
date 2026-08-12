<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Soumission d'un e-service COMCO (fusion, plainte, signalement, etc.).
 */
class EServiceSubmission extends Model
{
  /**
   * Attributs assignables en masse.
   *
   * @var list<string>
   */
  protected $fillable = [
    'service_slug',
    'name',
    'email',
    'phone',
    'description',
    'payload',
    'status',
  ];

  /**
   * Casts des attributs du modèle.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'payload' => 'array',
    ];
  }
}
