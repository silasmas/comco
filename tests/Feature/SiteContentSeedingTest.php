<?php

namespace Tests\Feature;

use App\Models\EServiceDefinition;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\SiteBlock;
use App\Models\SiteSetting;
use App\Support\HomePageContent;
use App\Support\SiteInstaller;
use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests d'import automatique des contenus institutionnels en base.
 */
class SiteContentSeedingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Vérifie que le seeder central remplit toutes les tables de contenu.
     */
    public function test_site_content_seeder_populates_all_dynamic_tables(): void
    {
        $this->seed(SiteContentSeeder::class);

        $this->assertGreaterThan(0, Page::query()->count());
        $this->assertGreaterThan(0, SiteBlock::query()->where('page', SiteBlock::PAGE_HOME)->count());
        $this->assertGreaterThan(0, SiteBlock::query()->where('page', SiteBlock::PAGE_CONTACT)->count());
        $this->assertGreaterThan(0, NavigationItem::query()->count());
        $this->assertGreaterThan(0, SiteSetting::query()->count());
        $this->assertGreaterThan(0, EServiceDefinition::query()->count());
    }

    /**
     * Vérifie que le contenu de l'accueil est lu depuis la base après seeding.
     */
    public function test_home_page_reads_seeded_content_from_database(): void
    {
        $this->seed(SiteContentSeeder::class);

        $content = HomePageContent::resolve();

        $this->assertNotEmpty($content->slider());
        $this->assertNotEmpty($content->tagline());
    }

    /**
     * Vérifie que les migrations déclenchent l'import automatique sur base vide.
     */
    public function test_migrations_auto_seed_content_on_empty_database(): void
    {
        $this->assertTrue(SiteInstaller::needsDefaultContentSeeding());

        SiteInstaller::runContentSeeders();

        $this->assertFalse(SiteInstaller::needsDefaultContentSeeding());
        $this->assertGreaterThan(0, Page::query()->count());
    }
}
