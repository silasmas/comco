<?php

namespace App\Livewire\Public;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * Composant Livewire affichant les derniers articles ou activités.
 */
class LatestPosts extends Component
{
  public string $variant = 'grid';

  /**
   * Type de contenu affiché (news|activity).
   */
  public string $contentType = Post::TYPE_NEWS;

  /**
   * Rendu du composant avec les contenus publiés récents.
   *
   * @return View Vue Blade du composant
   */
  public function render(): View
  {
    $limit = $this->variant === 'list' ? 5 : ($this->contentType === Post::TYPE_ACTIVITY ? 6 : 3);

    $posts = Post::query()
      ->published()
      ->ofType($this->contentType)
      ->latest('published_at')
      ->limit($limit)
      ->get();

    return view('livewire.public.latest-posts', [
      'posts' => $posts,
      'contentType' => $this->contentType,
    ]);
  }
}
