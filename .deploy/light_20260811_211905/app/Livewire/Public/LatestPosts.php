<?php

namespace App\Livewire\Public;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * Composant Livewire affichant les derniers articles sur la page d'accueil.
 */
class LatestPosts extends Component
{
  public string $variant = 'grid';

  /**
   * Rendu du composant avec les articles publiés récents.
   *
   * @return View Vue Blade du composant
   */
  public function render(): View
  {
    $posts = Post::query()
      ->published()
      ->latest('published_at')
      ->limit($this->variant === 'list' ? 5 : 3)
      ->get();

    return view('livewire.public.latest-posts', [
      'posts' => $posts,
    ]);
  }
}
