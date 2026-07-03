<?php

namespace App\Livewire\Public;

use App\Livewire\Concerns\WithUserFeedback;
use App\Models\NewsletterSubscriber;
use App\Support\InstitutionNotifier;
use App\Support\SubmitterNotifier;
use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * Formulaire d'inscription à la newsletter institutionnelle.
 */
class NewsletterForm extends Component
{
  use WithUserFeedback;

  public string $email = '';

  /**
   * Valide et enregistre l'abonnement newsletter.
   */
  public function subscribe(): void
  {
    $validated = $this->validate([
      'email' => ['required', 'email', 'max:255'],
    ]);

    NewsletterSubscriber::updateOrCreate(
      ['email' => $validated['email']],
      ['subscribed_at' => now()],
    );

    InstitutionNotifier::notify('Nouvel abonné newsletter COMCO', [
      'Email' => $validated['email'],
    ]);

    SubmitterNotifier::confirm(
      email: $validated['email'],
      recipientName: 'Abonné(e)',
      subject: 'Confirmation d\'inscription à la newsletter — COMCO',
      intro: 'Merci pour votre inscription à la newsletter de la COMCO. Vous recevrez nos actualités et communiqués officiels à cette adresse.',
      details: [
        'Email inscrit' => $validated['email'],
      ],
    );

    $this->reset('email');
    $this->notifySuccess('Merci ! Vous êtes inscrit à notre newsletter. Un email de confirmation vous a été envoyé.');
  }

  /**
   * Rendu du formulaire newsletter.
   *
   * @return View Vue Blade du formulaire
   */
  public function render(): View
  {
    return view('livewire.public.newsletter-form');
  }
}
