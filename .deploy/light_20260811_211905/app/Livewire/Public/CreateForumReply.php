<?php

namespace App\Livewire\Public;

use App\Livewire\Concerns\WithUserFeedback;
use App\Models\ForumReply;
use App\Models\ForumTopic;
use App\Support\SubmitterNotifier;
use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * Formulaire de réponse à un sujet du forum public.
 */
class CreateForumReply extends Component
{
  use WithUserFeedback;

  public int $topicId = 0;
  public string $authorName = '';
  public string $authorEmail = '';
  public string $body = '';

  /**
   * Initialise le composant avec l'identifiant du sujet.
   *
   * @param int $topicId Identifiant du sujet cible
   */
  public function mount(int $topicId): void
  {
    $this->topicId = $topicId;
  }

  /**
   * Valide et enregistre une réponse en modération.
   */
  public function submit(): void
  {
    $validated = $this->validate([
      'authorName' => ['required', 'string', 'max:255'],
      'authorEmail' => ['required', 'email', 'max:255'],
      'body' => ['required', 'string', 'min:10'],
    ]);

    $topic = ForumTopic::query()->approved()->findOrFail($this->topicId);

    ForumReply::create([
      'forum_topic_id' => $topic->id,
      'body' => $validated['body'],
      'author_name' => $validated['authorName'],
      'author_email' => $validated['authorEmail'],
      'status' => 'pending',
    ]);

    SubmitterNotifier::confirm(
      email: $validated['authorEmail'],
      recipientName: $validated['authorName'],
      subject: 'Confirmation de votre réponse — COMCO',
      intro: 'Votre réponse a bien été enregistrée. Elle sera visible sur le forum après validation par la COMCO.',
      details: [
        'Sujet' => $topic->title,
      ],
    );

    $this->reset(['authorName', 'authorEmail', 'body']);
    $this->notifyInfo('Votre réponse a été enregistrée et sera visible après validation par la COMCO. Un email de confirmation vous a été envoyé.');
  }

  /**
   * Rendu du formulaire de réponse.
   *
   * @return View Vue Blade du formulaire
   */
  public function render(): View
  {
    return view('livewire.public.create-forum-reply');
  }
}
