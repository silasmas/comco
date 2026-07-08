<?php

namespace Tests\Feature;

use App\Models\SiteBlock;
use App\Support\HomePageContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests du contenu dynamique de la page d'accueil.
 */
class HomePageContentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Vérifie le repli sur la configuration lorsque la base est vide.
     */
    public function test_it_falls_back_to_config_when_no_blocks_exist(): void
    {
        $content = HomePageContent::resolve();

        $this->assertSame(config('institution.tagline'), $content->tagline());
        $this->assertCount(count(config('institution.slider')), $content->slider());
    }

    /**
     * Vérifie que les blocs seedés remplacent la configuration statique.
     */
    public function test_it_reads_slider_blocks_from_database(): void
    {
        SiteBlock::query()->create([
            'page' => SiteBlock::PAGE_HOME,
            'block_type' => SiteBlock::TYPE_SLIDER,
            'block_key' => 'slider-test',
            'label' => 'Test slider',
            'payload' => [
                'title' => 'Titre dynamique',
                'text' => 'Texte dynamique',
                'image' => 'demo.jpg',
            ],
            'sort_order' => 0,
            'is_active' => true,
        ]);

        SiteBlock::query()->create([
            'page' => SiteBlock::PAGE_HOME,
            'block_type' => SiteBlock::TYPE_SETTING,
            'block_key' => 'tagline',
            'label' => 'Slogan',
            'payload' => ['value' => 'Slogan dynamique'],
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $content = HomePageContent::resolve();

        $this->assertSame('Slogan dynamique', $content->tagline());
        $this->assertSame('Titre dynamique', $content->slider()[0]['title']);
    }

    /**
     * Vérifie que la page d'accueil publique répond correctement.
     */
    public function test_home_page_renders_successfully(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee(config('institution.shortName'), false);
    }
}
