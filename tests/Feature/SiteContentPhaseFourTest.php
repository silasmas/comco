<?php

namespace Tests\Feature;

use App\Models\EServiceDefinition;
use App\Support\EServiceRegistry;
use App\Support\HomePageContent;
use Database\Seeders\EServiceDefinitionSeeder;
use Database\Seeders\HomeContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests de dynamisation e-services et blocs promotionnels de l'accueil.
 */
class SiteContentPhaseFourTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Vérifie que les e-services sont chargés depuis la base.
     */
    public function test_e_service_registry_reads_database_definitions(): void
    {
        $this->seed(EServiceDefinitionSeeder::class);

        EServiceRegistry::applyToConfig();

        $this->assertTrue(EServiceRegistry::has('signaler-pratique'));
        $this->assertSame('Signalement confidentiel', EServiceRegistry::get('signaler-pratique')['label']);
    }

    /**
     * Vérifie qu'un e-service inactif n'est plus disponible publiquement.
     */
    public function test_inactive_e_service_is_hidden(): void
    {
        $this->seed(EServiceDefinitionSeeder::class);

        EServiceDefinition::query()
            ->where('slug', 'signaler-pratique')
            ->update(['is_active' => false]);

        EServiceRegistry::applyToConfig();

        $this->assertFalse(EServiceRegistry::has('signaler-pratique'));
    }

    /**
     * Vérifie que les blocs promotionnels de l'accueil sont dynamiques.
     */
    public function test_home_promo_blocks_are_loaded_from_database(): void
    {
        $this->seed(HomeContentSeeder::class);

        $content = HomePageContent::resolve();

        $this->assertSame('Signaler une pratique abusive', $content->alertSignalement()['title']);
        $this->assertSame('Bientôt disponible', $content->taloPromo()['title']);
    }

    /**
     * Vérifie que la page d'accueil affiche les blocs promotionnels dynamiques.
     */
    public function test_home_page_renders_promo_blocks(): void
    {
        $this->seed(HomeContentSeeder::class);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Signaler une pratique abusive', false);
        $response->assertSee('Législation congolaise en matière de concurrence', false);
    }
}
