<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\View\View;

/**
 * Contrôleur public des actualités COMCO.
 */
class PostController extends Controller
{
  /**
   * Affiche un article publié (gabarit Elixir news.html).
   *
   * @param string $slug Identifiant URL de l'article
   * @return View Vue Blade de l'article
   */
  public function show(string $slug): View
  {
    $post = Post::query()->published()->where('slug', $slug)->firstOrFail();

    $relatedPosts = Post::query()
      ->published()
      ->where('id', '!=', $post->id)
      ->latest('published_at')
      ->limit(3)
      ->get();

    return view('public.posts.show', [
      'post' => $post,
      'relatedPosts' => $relatedPosts,
      'metaTitle' => $post->meta_title ?? $post->title,
      'metaDescription' => $post->meta_description ?? $post->excerpt,
    ]);
  }
}
