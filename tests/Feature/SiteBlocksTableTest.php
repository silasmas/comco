<?php

namespace Tests\Feature;

use App\Filament\Resources\SiteBlocks\Pages\ListSiteBlocks;
use App\Filament\Resources\SiteBlocks\SiteBlockResource;
use App\Models\SiteBlock;
use App\Models\User;
use App\Support\SiteDeploymentState;
use Database\Seeders\HomeContentSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Tests d'affichage des blocs dynamiques de la page d'accueil.
 */
class SiteBlocksTableTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prépare un panneau admin installé pour les tests Filament.
     */
    protected function setUp(): void
    {
        parent::setUp();

        SiteDeploymentState::markAsInstalled();
    }

    /**
     * Vérifie que les blocs seedés sont bien récupérables pour la liste Filament.
     */
    public function test_home_blocks_are_listable_after_seeding(): void
    {
        $this->seed(HomeContentSeeder::class);

        $records = SiteBlockResource::getEloquentQuery()->get();

        $this->assertGreaterThan(0, $records->count());
        $this->assertTrue($records->every(fn (SiteBlock $block): bool => $block->page === SiteBlock::PAGE_HOME));
    }

    /**
     * Vérifie que la pagination du tableau utilise un paramètre d'URL propre à la ressource.
     */
    public function test_site_blocks_table_uses_isolated_pagination_parameter(): void
    {
        $this->seed(HomeContentSeeder::class);

        $admin = User::factory()->create([
            'is_super_admin' => true,
        ]);

        Filament::setCurrentPanel(Filament::getPanel('admin'));

        $component = Livewire::actingAs($admin)->test(ListSiteBlocks::class);

        $this->assertSame('siteBlocksPage', $component->instance()->getTablePaginationPageName());
        $this->assertTrue($component->instance()->isTableLoaded());
    }
}
