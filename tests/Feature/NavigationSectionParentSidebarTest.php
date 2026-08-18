<?php

namespace Tests\Feature;

use App\Models\NavigationItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Vérifie qu'un parent Page CMS avec enfants produit une sidebar.
 */
class NavigationSectionParentSidebarTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Un élément section avec des enfants actifs doit exposer sidebar + children.
     */
    public function test_section_parent_with_children_becomes_sidebar(): void
    {
        $parent = NavigationItem::query()->create([
            'menu' => NavigationItem::MENU_MAIN,
            'label' => 'Présentation',
            'link_type' => NavigationItem::LINK_SECTION,
            'section' => 'qui-sommes-nous',
            'slug' => 'presentation',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        NavigationItem::query()->create([
            'menu' => NavigationItem::MENU_MAIN,
            'parent_id' => $parent->id,
            'label' => 'Mandat',
            'link_type' => NavigationItem::LINK_SECTION,
            'section' => 'qui-sommes-nous',
            'slug' => 'notre-mandat',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $parent->load(['children' => fn ($query) => $query->active()->orderBy('sort_order')]);
        $nav = $parent->toNavArray();

        $this->assertTrue($nav['sidebar'] ?? false);
        $this->assertSame('presentation', $nav['slug'] ?? null);
        $this->assertCount(1, $nav['children'] ?? []);
        $this->assertSame('notre-mandat', $nav['children'][0]['slug']);
    }
}
