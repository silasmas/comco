<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests prévisualisation et type « activité ».
 */
class PostPreviewAndActivityTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Un brouillon reste invisible au public.
   */
  public function test_draft_post_is_hidden_from_public(): void
  {
    $post = Post::query()->create([
      'title' => 'Brouillon secret',
      'slug' => 'brouillon-secret',
      'content_type' => Post::TYPE_NEWS,
      'body' => '<p>Secret</p>',
      'is_published' => false,
      'published_at' => now(),
    ]);

    $this->get(route('posts.show', $post->slug))->assertNotFound();
  }

  /**
   * Un admin connecté peut prévisualiser un brouillon.
   */
  public function test_admin_can_preview_draft_post(): void
  {
    $admin = User::factory()->create(['is_super_admin' => true]);
    $post = Post::query()->create([
      'title' => 'Brouillon aperçu',
      'slug' => 'brouillon-apercu',
      'content_type' => Post::TYPE_ACTIVITY,
      'body' => '<p>À vérifier</p>',
      'featured_image' => 'posts/images/preview.jpg',
      'is_published' => false,
    ]);

    $this->actingAs($admin)
      ->get(route('posts.preview', $post->slug))
      ->assertOk()
      ->assertSee('Mode prévisualisation', false)
      ->assertSee('Brouillon aperçu', false)
      ->assertSee('Activité', false);
  }

  /**
   * Un visiteur non connecté ne peut pas prévisualiser.
   */
  public function test_guest_cannot_preview_post(): void
  {
    $post = Post::query()->create([
      'title' => 'Privé',
      'slug' => 'prive',
      'body' => '<p>x</p>',
      'is_published' => false,
    ]);

    $this->get(route('posts.preview', $post->slug))
      ->assertRedirect('/admin/login');
  }

  /**
   * Une activité publiée avec vidéo est accessible publiquement.
   */
  public function test_published_activity_with_video_is_public(): void
  {
    $post = Post::query()->create([
      'title' => 'Atelier 2021',
      'slug' => 'atelier-2021',
      'content_type' => Post::TYPE_ACTIVITY,
      'body' => '<p>Compte rendu</p>',
      'featured_image' => 'posts/images/atelier.jpg',
      'featured_video' => 'posts/videos/atelier.mp4',
      'is_published' => true,
      'published_at' => now()->subYear(),
    ]);

    $this->get(route('posts.show', $post->slug))
      ->assertOk()
      ->assertSee('comco-post-media__video', false)
      ->assertSee('Activité', false);
  }
}
