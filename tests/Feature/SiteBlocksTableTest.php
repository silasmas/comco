<?php

namespace Tests\Feature;

use App\Filament\Resources\SiteBlocks\SiteBlockResource;
use App\Models\SiteBlock;
use Database\Seeders\HomeContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests d'affichage des blocs dynamiques de la page d'accueil.
 */
class SiteBlocksTableTest extends TestCase
{
    use RefreshDatabase;

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
}
