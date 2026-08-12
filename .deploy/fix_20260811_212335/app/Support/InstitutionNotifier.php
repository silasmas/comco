<?php

namespace App\Support;

use App\Mail\InstitutionSubmissionMail;
use Illuminate\Support\Facades\Mail;

/**
 * Envoie les notifications email vers l'adresse institutionnelle COMCO.
 */
class InstitutionNotifier
{
  /**
   * Notifie la COMCO d'une nouvelle soumission publique.
   *
   * @param string $subject Sujet de l'email
   * @param array<string, mixed> $lines Contenu structuré du message
   */
  public static function notify(string $subject, array $lines): void
  {
    $recipient = config('institution.contact.email');

    if (! is_string($recipient) || $recipient === '') {
      return;
    }

    Mail::to($recipient)->send(new InstitutionSubmissionMail($subject, $lines));
  }
}
