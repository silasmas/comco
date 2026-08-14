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
    $response->assertSee('comco-side-nav', false);
    $response->assertSee('comco-overview__media', false);
    $response->assertSee('comco-side-nav__link is-active', false);
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

    $member = CoordinationMember::query()->create([
      'title' => 'Fiche test coordination',
      'summary' => 'Résumé de test pour la grille.',
      'body' => 'Contenu détaillé de test.',
      'sort_order' => 99,
      'is_active' => true,
    ]);

    $response = $this->get('/qui-sommes-nous/coordination');

    $response->assertOk();
    $response->assertSee('Fiche test coordination', false);
    $response->assertSee('comco-lift-card', false);
    $response->assertSee(route('coordination.show', $member), false);
  }

  /**
   * Vérifie la page de détail d'une fiche Coordination.
   */
  public function test_coordination_detail_page_is_available(): void
  {
    $this->seedPresentation();

    $member = CoordinationMember::query()->firstOrFail();

    $response = $this->get(route('coordination.show', $member));

    $response->assertOk();
    $response->assertSee($member->title, false);
    $response->assertSee('Retour à la Coordination', false);
  }

  /**
   * Vérifie que la page Mission affiche le corps éditorial.
   */
  public function test_mission_page_renders_body_content(): void
  {
    $this->seedPresentation();

    $response = $this->get('/qui-sommes-nous/missions-services');

    $response->assertOk();
    $response->assertSee('Missions et pouvoirs', false);
    $response->assertSee('comco-hub-body', false);
    $response->assertDontSee('sera publié prochainement', false);
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
