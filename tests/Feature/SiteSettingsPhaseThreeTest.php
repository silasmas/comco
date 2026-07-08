<?php

namespace Tests\Feature;

use App\Models\NavigationItem;
use App\Models\SiteSetting;
use App\Support\ContactPageContent;
use App\Support\InstitutionSettings;
use App\Support\SiteNavigation;
use Database\Seeders\ContactContentSeeder;
use Database\Seeders\InstitutionSettingsSeeder;
use Database\Seeders\NavigationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests de dynamisation institutionnelle, navigation et contact.
 */
class SiteSettingsPhaseThreeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prépare les paramètres, la navigation et le contact en base.
     */
    private function seedPhaseThree(): void
    {
        $this->seed(InstitutionSettingsSeeder::class);
        $this->seed(NavigationSeeder::class);
        $this->seed(ContactContentSeeder::class);
    }

    /**
     * Vérifie que les paramètres institutionnels remplacent la configuration.
     */
    public function test_institution_settings_override_config(): void
    {
        SiteSetting::store('institution.contact.email', 'dynamic@comco.gouv.cd');
        InstitutionSettings::applyToConfig();

        $this->assertSame('dynamic@comco.gouv.cd', config('institution.contact.email'));
    }

    /**
     * Vérifie que la navigation dynamique conserve le menu principal.
     */
    public function test_navigation_is_loaded_from_database(): void
    {
        $this->seedPhaseThree();
        SiteNavigation::applyToConfig();

        $mainMenu = config('navigation.main');

        $this->assertNotEmpty($mainMenu);
        $this->assertSame('Accueil', $mainMenu[0]['label']);
        $this->assertArrayHasKey('children', $mainMenu[1]);
    }

    /**
     * Vérifie que la page contact affiche les blocs dynamiques.
     */
    public function test_contact_page_renders_dynamic_blocks(): void
    {
        $this->seedPhaseThree();

        $response = $this->get('/contact');

        $response->assertOk();
        $response->assertSee('Représentations provinciales', false);
        $response->assertSee('E-services', false);
    }

    /**
     * Vérifie que le contenu contact est lu depuis la base.
     */
    public function test_contact_page_content_reads_database_blocks(): void
    {
        $this->seed(ContactContentSeeder::class);

        $content = ContactPageContent::resolve();

        $this->assertSame('Représentations provinciales', $content->provincialOffices()['title']);
    }

    /**
     * Vérifie que les sections routables proviennent de la navigation seedée.
     */
    public function test_navigation_sections_include_cms_sections(): void
    {
        $this->seed(NavigationSeeder::class);

        $sections = SiteNavigation::sections();

        $this->assertArrayHasKey('qui-sommes-nous', $sections);
        $this->assertArrayHasKey('e-services', $sections);
    }

    /**
     * Vérifie qu'un élément de navigation inactif est exclu du menu.
     */
    public function test_inactive_navigation_items_are_hidden(): void
    {
        $this->seed(NavigationSeeder::class);

        NavigationItem::query()
            ->where('label', 'Forum')
            ->update(['is_active' => false]);

        SiteNavigation::applyToConfig();

        $labels = collect(config('navigation.main'))->pluck('label')->all();

        $this->assertNotContains('Forum', $labels);
    }
}
