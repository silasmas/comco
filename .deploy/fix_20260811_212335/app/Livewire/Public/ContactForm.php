<?php

namespace App\Livewire\Public;

use App\Livewire\Concerns\WithUserFeedback;
use App\Models\ContactMessage;
use App\Support\InstitutionNotifier;
use App\Support\SubmitterNotifier;
use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * Formulaire de contact public dynamique (Livewire).
 */
class ContactForm extends Component
{
  use WithUserFeedback;

  public string $name = '';
  public string $email = '';
  public string $message = '';

  /**
   * Valide et traite l'envoi du formulaire de contact.
   */
  public function submit(): void
  {
    $validated = $this->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'email', 'max:255'],
      'message' => ['required', 'string', 'min:10'],
    ]);

    ContactMessage::create($validated);

    InstitutionNotifier::notify('Nouveau message de contact COMCO', [
      'Nom' => $validated['name'],
      'Email' => $validated['email'],
      'Message' => $validated['message'],
    ]);

    SubmitterNotifier::confirm(
      email: $validated['email'],
      recipientName: $validated['name'],
      subject: 'Confirmation de votre message — COMCO',
      intro: 'Nous avons bien reçu votre message. Notre équipe vous répondra dans les meilleurs délais.',
      details: [
        'Objet' => 'Message de contact',
        'Message' => $validated['message'],
      ],
    );

    $this->reset(['name', 'email', 'message']);
    $this->notifySuccess('Votre message a bien été reçu. Un email de confirmation vous a été envoyé ; nous vous répondrons dans les meilleurs délais.');
  }

  /**
   * Rendu du formulaire de contact.
   *
   * @return View Vue Blade du formulaire
   */
  public function render(): View
  {
    return view('livewire.public.contact-form');
  }
}
