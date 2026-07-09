<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\InstallationStatus;
use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests de l'état d'installation affiché sur la page seeders.
 */
class InstallationStatusTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Vérifie que les seeders non exécutés sont signalés comme en attente.
     */
    public function test_installation_status_reports_pending_seeders(): void
    {
        $status = InstallationStatus::forView();

        $this->assertTrue($status['databaseReady']);
        $this->assertFalse($status['contentSeeded']);
        $this->assertFalse($status['postsSeeded']);
    }

    /**
     * Vérifie que les seeders exécutés sont signalés comme importés.
     */
    public function test_installation_status_reports_completed_seeders(): void
    {
        $this->seed(SiteContentSeeder::class);

        $status = InstallationStatus::forView();

        $this->assertTrue($status['contentSeeded']);
        $this->assertGreaterThan(0, $status['counts']['pages']);
    }

    /**
     * Vérifie que la page d'installation affiche les boutons seeders.
     */
    public function test_installation_page_displays_seeder_buttons(): void
    {
        $admin = User::factory()->create([
            'is_super_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('comco.installation.show'));

        $response->assertOk();
        $response->assertSee('Exécuter tous les seeders', false);
        $response->assertSee('Importer les contenus du site', false);
        $response->assertSee('État de l\'installation', false);
    }
}
