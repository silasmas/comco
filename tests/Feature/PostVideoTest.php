<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests d'affichage des actualités avec vidéo.
 */
class PostVideoTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Vérifie qu'une actualité avec vidéo affiche le lecteur et la vignette.
   */
  public function test_post_with_video_renders_player_and_poster(): void
  {
    $post = Post::query()->create([
      'title' => 'Actualité vidéo COMCO',
      'slug' => 'actualite-video-comco',
      'excerpt' => 'Chapô de test',
      'body' => '<p>Contenu de test</p>',
      'featured_image' => 'posts/images/vignette.jpg',
      'featured_video' => 'posts/videos/demo.mp4',
      'is_published' => true,
      'published_at' => now(),
    ]);

    $response = $this->get(route('posts.show', $post->slug));

    $response->assertOk();
    $response->assertSee('comco-post-media__video', false);
    $response->assertSee('posts/videos/demo.mp4', false);
    $response->assertSee('posts/images/vignette.jpg', false);
    $response->assertSee('poster=', false);
  }

  /**
   * Vérifie qu'une actualité sans vidéo garde l'image à la une.
   */
  public function test_post_without_video_keeps_featured_image(): void
  {
    $post = Post::query()->create([
      'title' => 'Actualité image COMCO',
      'slug' => 'actualite-image-comco',
      'body' => '<p>Contenu</p>',
      'featured_image' => 'posts/images/photo.jpg',
      'is_published' => true,
      'published_at' => now(),
    ]);

    $response = $this->get(route('posts.show', $post->slug));

    $response->assertOk();
    $response->assertDontSee('comco-post-media__video', false);
    $response->assertSee('posts/images/photo.jpg', false);
  }
}
