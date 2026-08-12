<?php

namespace App\Support;

use App\Mail\SubmissionConfirmationMail;
use Illuminate\Support\Facades\Mail;

/**
 * Envoie les emails de confirmation aux personnes ayant soumis un formulaire public.
 */
class SubmitterNotifier
{
  /**
   * Notifie l'utilisateur par email après une soumission réussie.
   *
   * @param string $email Adresse email du destinataire
   * @param string $recipientName Nom affiché dans le message
   * @param string $subject Sujet de l'email
   * @param string $intro Texte principal de confirmation
   * @param array<string, mixed> $details Informations complémentaires optionnelles
   */
  public static function confirm(
    string $email,
    string $recipientName,
    string $subject,
    string $intro,
    array $details = [],
  ): void {
    if ($email === '') {
      return;
    }

    try {
      Mail::to($email)->send(new SubmissionConfirmationMail(
        recipientName: $recipientName,
        subjectLine: $subject,
        intro: $intro,
        details: $details,
      ));
    } catch (\Throwable $exception) {
      report($exception);
    }
  }
}
