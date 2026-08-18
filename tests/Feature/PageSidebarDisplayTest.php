<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Support\PageSidebar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Vérifie le mode d'affichage des pages et la résolution du menu latéral.
 */
class PageSidebarDisplayTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Vérifie les helpers showsContent / showsPdf selon le mode.
     */
    public function test_content_display_flags(): void
    {
        $content = Page::query()->create([
            'title' => 'Texte',
            'section' => 'centre-information',
            'slug' => 'texte',
            'template' => 'presentation-hub',
            'content_display' => Page::DISPLAY_CONTENT,
            'is_published' => true,
        ]);
        $pdf = Page::query()->create([
            'title' => 'PDF',
            'section' => 'centre-information',
            'slug' => 'pdf',
            'template' => 'presentation-hub',
            'content_display' => Page::DISPLAY_PDF,
            'is_published' => true,
        ]);
        $both = Page::query()->create([
            'title' => 'Les deux',
            'section' => 'centre-information',
            'slug' => 'les-deux',
            'template' => 'presentation-hub',
            'content_display' => Page::DISPLAY_BOTH,
            'is_published' => true,
        ]);

        $this->assertTrue($content->showsContent());
        $this->assertFalse($content->showsPdf());
        $this->assertFalse($pdf->showsContent());
        $this->assertTrue($pdf->showsPdf());
        $this->assertTrue($both->showsContent());
        $this->assertTrue($both->showsPdf());
    }

    /**
     * Vérifie la résolution d'une sidebar depuis la navigation config.
     */
    public function test_page_sidebar_resolves_from_navigation_config(): void
    {
        config([
            'navigation.main' => [
                [
                    'label' => 'Centre d\'information',
                    'section' => 'centre-information',
                    'slug' => 'centre-information',
                    'sidebar' => true,
                    'children' => [
                        ['label' => 'Lois', 'slug' => 'cadre-juridique'],
                    ],
                ],
            ],
        ]);

        $hub = Page::query()->create([
            'title' => 'Centre',
            'section' => 'centre-information',
            'slug' => 'centre-information',
            'template' => 'presentation-hub',
            'content_display' => Page::DISPLAY_CONTENT,
            'is_published' => true,
        ]);
        $child = Page::query()->create([
            'title' => 'Lois',
            'section' => 'centre-information',
            'slug' => 'cadre-juridique',
            'template' => 'presentation-hub',
            'content_display' => Page::DISPLAY_BOTH,
            'is_published' => true,
        ]);

        $sidebar = PageSidebar::forPage($hub);
        $this->assertNotNull($sidebar);
        $this->assertSame('Centre d\'information', $sidebar['label']);
        $this->assertSame('centre-information', $sidebar['hubSlug']);
        $this->assertTrue(PageSidebar::isHub($hub));
        $this->assertFalse(PageSidebar::isHub($child));
    }
}
