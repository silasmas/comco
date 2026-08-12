<?php

namespace Tests\Feature;

use App\Http\Middleware\ShowMaintenancePage;
use App\Models\User;
use App\Support\MaintenanceMode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests du mode maintenance public et du bypass de prévisualisation admin.
 */
class MaintenanceModeTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Vérifie que le site public reste accessible lorsque la maintenance est inactive.
   */
  public function test_public_site_is_available_when_maintenance_is_disabled(): void
  {
    MaintenanceMode::disable();

    $response = $this->get('/');

    $response->assertOk();
  }

  /**
   * Vérifie que le site public renvoie la page 503 en mode maintenance.
   */
  public function test_public_site_shows_maintenance_page_when_enabled(): void
  {
    MaintenanceMode::update([
      'enabled' => true,
      'title' => 'Travaux en cours',
      'message' => 'Merci de patienter.',
    ]);

    $response = $this->get('/');

    $response->assertStatus(503);
    $response->assertSee('Travaux en cours', false);
    $response->assertSee('Merci de patienter.', false);
    $response->assertSee('Site temporairement indisponible', false);
  }

  /**
   * Vérifie que la page de connexion admin reste accessible pendant la maintenance.
   */
  public function test_admin_login_remains_accessible_during_maintenance(): void
  {
    MaintenanceMode::enable();

    $response = $this->get('/admin/login');

    $response->assertOk();
  }

  /**
   * Vérifie que les endpoints Livewire hashés ne sont pas bloqués (login Filament).
   */
  public function test_hashed_livewire_endpoint_bypasses_maintenance(): void
  {
    MaintenanceMode::enable();

    $path = ltrim(
      \Livewire\Mechanisms\HandleRequests\EndpointResolver::updatePath(),
      '/',
    );

    $response = $this->post('/'.$path);

    $this->assertNotSame(503, $response->status());
    $response->assertDontSee('Site temporairement indisponible', false);
  }

  /**
   * Vérifie qu'un administrateur connecté voit aussi la page de maintenance sur le site public.
   */
  public function test_authenticated_admin_sees_maintenance_page_on_public_site(): void
  {
    MaintenanceMode::update([
      'enabled' => true,
      'title' => 'Travaux en cours',
      'message' => 'Merci de patienter.',
    ]);

    $admin = User::factory()->create([
      'is_super_admin' => true,
    ]);

    $response = $this->actingAs($admin)->get('/');

    $response->assertStatus(503);
    $response->assertSee('Travaux en cours', false);
  }

  /**
   * Vérifie que la prévisualisation active le bypass puis affiche le vrai site.
   */
  public function test_authenticated_admin_preview_unlocks_real_site(): void
  {
    MaintenanceMode::enable();

    $admin = User::factory()->create([
      'is_super_admin' => true,
    ]);

    $response = $this->actingAs($admin)->get(route('maintenance.preview'));

    $response->assertRedirect(route('home'));
    $response->assertSessionHas(ShowMaintenancePage::PREVIEW_SESSION_KEY, true);

    $home = $this->actingAs($admin)->get('/');

    $home->assertOk();
    $home->assertDontSee('Site temporairement indisponible', false);
  }

  /**
   * Vérifie qu'un visiteur non authentifié ne peut pas accéder à l'aperçu.
   */
  public function test_guest_cannot_preview_real_site_via_preview_route(): void
  {
    $response = $this->get(route('maintenance.preview'));

    $response->assertRedirect('/admin/login');
  }

  /**
   * Vérifie que les assets du thème ne sont pas bloqués pendant la maintenance.
   */
  public function test_theme_assets_bypass_maintenance_middleware(): void
  {
    MaintenanceMode::enable();

    $response = $this->get('/theme/assets/css/theme.min.css');

    $this->assertNotSame(503, $response->status());
  }
}
