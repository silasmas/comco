<?php

namespace Database\Seeders;

use App\Support\EServiceRegistry;
use App\Support\InstitutionSettings;
use App\Support\SiteNavigation;
use Illuminate\Database\Seeder;

/**
 * Seeder central des contenus institutionnels dynamiques COMCO.
 */
class SiteContentSeeder extends Seeder
{
    /**
     * Importe tous les contenus de base dans la base de données.
     */
    public function run(): void
    {
        $this->call([
            InstitutionSeeder::class,
            InstitutionSettingsSeeder::class,
            NavigationSeeder::class,
            HomeContentSeeder::class,
            ContactContentSeeder::class,
            PageAttachmentsSeeder::class,
            EServiceDefinitionSeeder::class,
        ]);

        InstitutionSettings::applyToConfig();
        SiteNavigation::applyToConfig();
        EServiceRegistry::applyToConfig();
    }
}
