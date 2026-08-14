<?php

namespace Tests\Feature;

use App\Models\CoordinationMember;
use App\Models\NavigationItem;
use Database\Seeders\CoordinationMemberSeeder;
use Database\Seeders\InstitutionSeeder;
use Database\Seeders\NavigationSeeder;
use Database\Seeders\PageAttachmentsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests de la rubrique Présentation (sidebar + coordination).
 */
class PresentationHubTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Prépare les pages, navigation et fiches coordination.
   */
  private function seedPresentation(): void
  {
    $this->seed(InstitutionSeeder::class);
    $this->seed(NavigationSeeder::class);
    $this->seed(PageAttachmentsSeeder::class);
    $this->seed(CoordinationMemberSeeder::class);
  }

  /**
   * Vérifie la page Présentation avec barre latérale.
   */
  public function test_presentation_overview_renders_sidebar(): void
  {
    $this->seedPresentation();

    $response = $this->get('/qui-sommes-nous/presentation');

    $response->assertOk();
    $response->assertSee('comco-presentation__sidebar', false);
    $response->assertSee('Mandat', false);
    $response->assertSee('Coordination', false);
    $response->assertSee('Commission de la Concurrence', false);
  }

  /**
   * Vérifie que Coordination affiche les fiches dynamiques.
   */
  public function test_coordination_page_renders_dynamic_members(): void
  {
    $this->seedPresentation();

    CoordinationMember::query()->create([
      'title' => 'Fiche test coordination',
      'summary' => 'Résumé de test pour la grille.',
      'sort_order' => 99,
      'is_active' => true,
    ]);

    $response = $this->get('/qui-sommes-nous/coordination');

    $response->assertOk();
    $response->assertSee('Fiche test coordination', false);
    $response->assertSee('comco-presentation-card', false);
  }

  /**
   * Vérifie qu'un menu peut être désactivé via is_active.
   */
  public function test_navigation_toggle_hides_inactive_item(): void
  {
    $this->seed(NavigationSeeder::class);

    NavigationItem::query()
      ->where('label', 'Plan du site')
      ->update(['is_active' => false]);

    $this->get('/plan-du-site')->assertOk();

    \App\Support\SiteNavigation::applyToConfig();
    $labels = collect(config('navigation.main'))->pluck('label')->all();

    $this->assertNotContains('Plan du site', $labels);
  }
}
