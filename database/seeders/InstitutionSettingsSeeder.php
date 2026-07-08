<?php

namespace Database\Seeders;

use App\Support\InstitutionSettings;
use Illuminate\Database\Seeder;

/**
 * Seeder des paramètres institutionnels COMCO.
 */
class InstitutionSettingsSeeder extends Seeder
{
    /**
     * Importe les paramètres initiaux depuis config/institution.php.
     */
    public function run(): void
    {
        InstitutionSettings::persist(InstitutionSettings::forForm());
    }
}
