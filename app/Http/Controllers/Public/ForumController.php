<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ForumTopic;
use Illuminate\Contracts\View\View;

/**
 * Contrôleur public du forum COMCO.
 */
class ForumController extends Controller
{
  /**
   * Affiche la liste des sujets approuvés.
   *
   * @return View Vue liste du forum
   */
  public function index(): View
  {
    $topics = ForumTopic::query()
      ->approved()
      ->withCount('approvedReplies')
      ->latest()
      ->paginate(12);

    return view('public.forum.index', [
      'topics' => $topics,
      'categories' => config('forum.categories', []),
    ]);
  }

  /**
   * Affiche un sujet et ses réponses approuvées.
   *
   * @param string $slug Identifiant URL du sujet
   * @return View Vue détail du sujet
   */
  public function show(string $slug): View
  {
    $topic = ForumTopic::query()
      ->approved()
      ->where('slug', $slug)
      ->firstOrFail();

    $topic->increment('views');

    $replies = $topic->approvedReplies()->oldest()->get();

    return view('public.forum.show', [
      'topic' => $topic,
      'replies' => $replies,
    ]);
  }
}
