<?php

namespace Database\Seeders;

use App\Models\EServiceDefinition;
use Illuminate\Database\Seeder;

/**
 * Seeder des définitions e-services COMCO.
 */
class EServiceDefinitionSeeder extends Seeder
{
    /**
     * Importe les formulaires e-services depuis config/e-services.php.
     */
    public function run(): void
    {
        foreach (config('e-services', []) as $slug => $definition) {
            EServiceDefinition::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'label' => $definition['label'],
                    'intro' => $definition['intro'],
                    'fields' => $definition['fields'],
                    'sort_order' => array_search($slug, array_keys(config('e-services', [])), true) ?: 0,
                    'is_active' => true,
                ],
            );
        }
    }
}
