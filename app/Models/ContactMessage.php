<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Message envoyé via le formulaire de contact public.
 */
class ContactMessage extends Model
{
  /**
   * Attributs assignables en masse.
   *
   * @var list<string>
   */
  protected $fillable = [
    'name',
    'email',
    'message',
    'status',
  ];
}
