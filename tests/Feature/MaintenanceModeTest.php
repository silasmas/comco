<?php

namespace Tests\Feature;

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
        $response->assertSee('Site en maintenance', false);
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
     * Vérifie qu'un administrateur authentifié voit le vrai site pendant la maintenance.
     */
    public function test_authenticated_admin_can_browse_real_site_during_maintenance(): void
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

        $response->assertOk();
        $response->assertDontSee('Travaux en cours', false);
    }

    /**
     * Vérifie que la route preview redirige vers le site public pour un admin.
     */
    public function test_authenticated_admin_preview_redirects_to_real_site(): void
    {
        MaintenanceMode::enable();

        $admin = User::factory()->create([
            'is_super_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('maintenance.preview'));

        $response->assertRedirect(route('home'));
    }

    /**
     * Vérifie qu'un visiteur non authentifié ne peut pas accéder à l'aperçu.
     */
    public function test_guest_cannot_preview_real_site_via_preview_route(): void
    {
        $response = $this->get(route('maintenance.preview'));

        $response->assertRedirect('/admin/login');
    }
}
