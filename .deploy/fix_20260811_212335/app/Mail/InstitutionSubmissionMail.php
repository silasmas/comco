<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Notification email envoyée à la COMCO pour une soumission publique.
 */
class InstitutionSubmissionMail extends Mailable
{
  use Queueable;
  use SerializesModels;

  /**
   * @param string $subjectLine Sujet du message
   * @param array<string, mixed> $lines Paires libellé / valeur à afficher
   */
  public function __construct(
    public string $subjectLine,
    public array $lines,
  ) {
  }

  /**
   * Définit l'enveloppe du message.
   */
  public function envelope(): Envelope
  {
    return new Envelope(
      subject: $this->subjectLine,
    );
  }

  /**
   * Définit le contenu HTML du message.
   */
  public function content(): Content
  {
    return new Content(
      view: 'mail.institution-submission',
    );
  }
}
