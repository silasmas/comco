<?php

namespace Tests\Feature;

use App\Models\Page;
use Database\Seeders\InstitutionSeeder;
use Database\Seeders\PageAttachmentsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests de dynamisation des pages CMS institutionnelles.
 */
class CmsPageContentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prépare les pages et contenus attachés en base.
     */
    private function seedCmsContent(): void
    {
        $this->seed(InstitutionSeeder::class);
        $this->seed(PageAttachmentsSeeder::class);
    }

    /**
     * Vérifie que le gabarit en base prime sur la configuration.
     */
    public function test_page_template_prefers_database_value(): void
    {
        $this->seedCmsContent();

        $page = Page::query()
            ->where('section', 'centre-information')
            ->where('slug', 'documentation-diverse')
            ->firstOrFail();

        $page->update(['template' => 'service']);

        $this->assertSame('service', pageTemplate($page->section, $page->slug, $page->template));
    }

    /**
     * Vérifie l'affichage public de la galerie photo dynamique.
     */
    public function test_gallery_page_renders_database_images(): void
    {
        $this->seedCmsContent();

        $response = $this->get('/medias/galerie-photo');

        $response->assertOk();
        $response->assertSee('id="comco-gallery"', false);
        $response->assertSee('gallery/06-f.jpg', false);
    }

    /**
     * Vérifie l'affichage public des profils d'équipe dynamiques.
     */
    public function test_alumni_page_renders_database_team_members(): void
    {
        $this->seedCmsContent();

        $response = $this->get('/qui-sommes-nous/partenaires');

        $response->assertOk();
        $response->assertSee('Coordination nationale', false);
        $response->assertSee('Collège des analystes', false);
    }

    /**
     * Vérifie l'affichage public des documents juridiques dynamiques.
     */
    public function test_legal_page_renders_database_documents(): void
    {
        $this->seedCmsContent();

        $response = $this->get('/centre-information/cadre-juridique');

        $response->assertOk();
        $response->assertSee('Loi n° 18-020 — Texte de référence (Cng 1875-07)', false);
        $response->assertSee('cng-1875-07.pdf', false);
    }

    /**
     * Vérifie qu'une page CMS publiée répond correctement.
     */
    public function test_cms_page_renders_with_rich_content(): void
    {
        $this->seedCmsContent();

        $page = Page::query()->published()->firstOrFail();
        $page->update([
            'body' => '<p>Contenu <strong>riche</strong> COMCO</p>',
        ]);

        $response = $this->get('/'.$page->section.'/'.$page->slug);

        $response->assertOk();
        $response->assertSee('Contenu', false);
        $response->assertSee('<strong>riche</strong>', false);
    }
}
