<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Email de confirmation envoyé à l'utilisateur après une soumission publique.
 */
class SubmissionConfirmationMail extends Mailable
{
  use Queueable;
  use SerializesModels;

  /**
   * @param string $recipientName Nom du destinataire
   * @param string $subjectLine Sujet du message
   * @param string $intro Message principal de confirmation
   * @param array<string, mixed> $details Informations complémentaires (libellé => valeur)
   */
  public function __construct(
    public string $recipientName,
    public string $subjectLine,
    public string $intro,
    public array $details = [],
  ) {
  }

  /**
   * Définit l'enveloppe du message.
   *
   * @return Envelope Enveloppe SMTP du message
   */
  public function envelope(): Envelope
  {
    return new Envelope(
      subject: $this->subjectLine,
    );
  }

  /**
   * Définit le contenu HTML du message.
   *
   * @return Content Vue HTML du message
   */
  public function content(): Content
  {
    return new Content(
      view: 'mail.submission-confirmation',
    );
  }
}
