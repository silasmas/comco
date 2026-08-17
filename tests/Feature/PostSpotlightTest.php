<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests de la mise en avant (modale + bouton flottant).
 */
class PostSpotlightTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Une mise en avant publiée apparaît sur l'accueil.
   */
  public function test_published_spotlight_renders_modal_on_home(): void
  {
    Post::query()->create([
      'title' => 'Opération spéciale COMCO',
      'slug' => 'operation-speciale',
      'content_type' => Post::TYPE_ACTIVITY,
      'excerpt' => 'Intervention en cours à Kinshasa.',
      'body' => '<p>Détails</p>',
      'featured_image' => 'posts/images/op.jpg',
      'spotlight_images' => ['posts/spotlight/a.jpg', 'posts/spotlight/b.jpg'],
      'spotlight_text' => 'Suivez l’opération en cours.',
      'spotlight_video_mode' => Post::VIDEO_MODE_NORMAL,
      'is_spotlight' => true,
      'is_published' => true,
      'published_at' => now(),
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('comco-spotlight', false);
    $response->assertSee('Opération spéciale COMCO', false);
    $response->assertSee('Activité en cours', false);
    $response->assertSee('Suivez l’opération en cours.', false);
  }

  /**
   * Un brouillon mis en avant n'apparaît pas au public.
   */
  public function test_draft_spotlight_is_hidden(): void
  {
    Post::query()->create([
      'title' => 'Brouillon spotlight',
      'slug' => 'brouillon-spotlight',
      'is_spotlight' => true,
      'is_published' => false,
      'body' => '<p>x</p>',
    ]);

    $this->get(route('home'))
      ->assertOk()
      ->assertDontSee('Brouillon spotlight', false)
      ->assertDontSee('comco-spotlight', false);
  }

  /**
   * Une seule mise en avant reste active à la fois.
   */
  public function test_only_one_spotlight_remains_active(): void
  {
    $first = Post::query()->create([
      'title' => 'Premier',
      'slug' => 'premier-spotlight',
      'is_spotlight' => true,
      'is_published' => true,
      'published_at' => now()->subDay(),
      'body' => '<p>1</p>',
    ]);

    $second = Post::query()->create([
      'title' => 'Second',
      'slug' => 'second-spotlight',
      'is_spotlight' => true,
      'is_published' => true,
      'published_at' => now(),
      'body' => '<p>2</p>',
    ]);

    $this->assertFalse($first->fresh()->is_spotlight);
    $this->assertTrue($second->fresh()->is_spotlight);
  }

  /**
   * Le mode story est exposé dans le markup.
   */
  public function test_story_video_mode_is_marked_in_markup(): void
  {
    Post::query()->create([
      'title' => 'Story vidéo',
      'slug' => 'story-video',
      'content_type' => Post::TYPE_NEWS,
      'featured_video' => 'posts/videos/story.mp4',
      'spotlight_video_mode' => Post::VIDEO_MODE_STORY,
      'is_spotlight' => true,
      'is_published' => true,
      'published_at' => now(),
      'body' => '<p>Story</p>',
    ]);

    $this->get(route('home'))
      ->assertOk()
      ->assertSee('comco-spotlight__story', false)
      ->assertSee('isStory: true', false);
  }
}
