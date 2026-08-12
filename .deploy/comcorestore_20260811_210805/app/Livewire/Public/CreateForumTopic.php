<?php

namespace App\Livewire\Public;

use App\Livewire\Concerns\WithUserFeedback;
use App\Models\ForumTopic;
use App\Support\ForumSlug;
use App\Support\SubmitterNotifier;
use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * Formulaire de création d'un sujet de forum public.
 */
class CreateForumTopic extends Component
{
  use WithUserFeedback;

  public string $title = '';
  public string $category = '';
  public string $authorName = '';
  public string $authorEmail = '';
  public string $body = '';

  /**
   * Valide et enregistre un nouveau sujet en modération.
   */
  public function submit(): void
  {
    $categories = array_keys(config('forum.categories', []));

    $validated = $this->validate([
      'title' => ['required', 'string', 'max:255'],
      'category' => ['required', 'in:' . implode(',', $categories)],
      'authorName' => ['required', 'string', 'max:255'],
      'authorEmail' => ['required', 'email', 'max:255'],
      'body' => ['required', 'string', 'min:20'],
    ]);

    ForumTopic::create([
      'title' => $validated['title'],
      'slug' => ForumSlug::fromTitle($validated['title']),
      'category' => $validated['category'],
      'body' => $validated['body'],
      'author_name' => $validated['authorName'],
      'author_email' => $validated['authorEmail'],
      'status' => 'pending',
    ]);

    $categoryLabel = config('forum.categories.' . $validated['category'], $validated['category']);

    SubmitterNotifier::confirm(
      email: $validated['authorEmail'],
      recipientName: $validated['authorName'],
      subject: 'Confirmation de votre sujet de forum — COMCO',
      intro: 'Votre sujet a bien été enregistré. Il sera visible sur le forum après validation par la COMCO.',
      details: [
        'Titre' => $validated['title'],
        'Catégorie' => $categoryLabel,
      ],
    );

    $this->reset(['title', 'category', 'authorName', 'authorEmail', 'body']);
    $this->notifyInfo('Votre sujet a été enregistré et sera visible après validation par la COMCO. Un email de confirmation vous a été envoyé.');
  }

  /**
   * Rendu du formulaire de création de sujet.
   *
   * @return View Vue Blade du formulaire
   */
  public function render(): View
  {
    return view('livewire.public.create-forum-topic', [
      'categories' => config('forum.categories', []),
    ]);
  }
}
