<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\View\View;

/**
 * Contrôleur public des actualités et activités COMCO.
 */
class PostController extends Controller
{
  /**
   * Affiche un article publié (gabarit Elixir news.html).
   *
   * @param  string  $slug  Identifiant URL de l'article
   * @return View Vue Blade de l'article
   */
  public function show(string $slug): View
  {
    $post = Post::query()->published()->where('slug', $slug)->firstOrFail();

    return $this->renderPost($post, false);
  }

  /**
   * Prévisualise un article (publié ou brouillon) pour un administrateur connecté.
   *
   * @param  string  $slug  Identifiant URL de l'article
   * @return View Vue Blade de prévisualisation
   */
  public function preview(string $slug): View
  {
    $post = Post::query()->where('slug', $slug)->firstOrFail();

    return $this->renderPost($post, true);
  }

  /**
   * Prépare la vue détail / prévisualisation d'un article.
   *
   * @param  Post  $post  Article à afficher
   * @param  bool  $isPreview  True si mode prévisualisation admin
   * @return View Vue Blade
   */
  private function renderPost(Post $post, bool $isPreview): View
  {
    $relatedQuery = Post::query()
      ->published()
      ->where('id', '!=', $post->id)
      ->ofType($post->content_type ?: Post::TYPE_NEWS)
      ->latest('published_at')
      ->limit(3);

    return view('public.posts.show', [
      'post' => $post,
      'relatedPosts' => $relatedQuery->get(),
      'isPreview' => $isPreview,
      'metaTitle' => $post->meta_title ?? $post->title,
      'metaDescription' => $post->meta_description ?? $post->excerpt,
    ]);
  }
}
