<?php

namespace App\Support;

use App\Models\EServiceDefinition;
use App\Models\Page;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Charge les définitions e-services depuis la base ou la configuration.
 */
class EServiceRegistry
{
    /**
     * Retourne toutes les définitions e-services disponibles.
     *
     * @return array<string, array{label: string, intro: string, fields: list<array<string, mixed>>}> Services indexés par slug
     */
    public static function resolve(): array
    {
        if (! Schema::hasTable('e_service_definitions')) {
            return config('e-services', []);
        }

        $definitions = EServiceDefinition::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($definitions->isEmpty()) {
            return config('e-services', []);
        }

        return $definitions
            ->mapWithKeys(fn (EServiceDefinition $definition): array => [
                $definition->slug => $definition->toServiceConfig(),
            ])
            ->all();
    }

    /**
     * Applique les définitions dynamiques à la configuration Laravel.
     */
    public static function applyToConfig(): void
    {
        if (! Schema::hasTable('e_service_definitions')) {
            return;
        }

        config(['e-services' => self::resolve()]);
    }

    /**
     * Indique si un e-service est disponible publiquement.
     *
     * @param  string  $slug  Identifiant du service
     * @return bool True si le service existe et est actif
     */
    public static function has(string $slug): bool
    {
        return array_key_exists($slug, self::resolve());
    }

    /**
     * Retourne la configuration d'un e-service.
     *
     * @param  string  $slug  Identifiant du service
     * @return array<string, mixed> Configuration ou tableau vide
     */
    public static function get(string $slug): array
    {
        return self::resolve()[$slug] ?? [];
    }

    /**
     * Retourne une définition e-service par slug, y compris si elle est inactive.
     *
     * @param  string  $slug  Identifiant du service
     * @return EServiceDefinition|null Définition trouvée ou null
     */
    public static function findDefinition(string $slug): ?EServiceDefinition
    {
        if (! Schema::hasTable('e_service_definitions')) {
            return null;
        }

        return EServiceDefinition::query()
            ->where('slug', $slug)
            ->first();
    }

    /**
     * Garantit l'existence d'une page CMS pour un formulaire e-service.
     *
     * @param  EServiceDefinition  $definition  Définition du formulaire
     * @return Page Page CMS associée
     */
    public static function ensurePage(EServiceDefinition $definition): Page
    {
        $existingPage = Page::query()
            ->where('section', 'e-services')
            ->where('slug', $definition->slug)
            ->first();

        if ($existingPage instanceof Page) {
            return $existingPage;
        }

        return Page::query()->create([
            'section' => 'e-services',
            'slug' => $definition->slug,
            'title' => $definition->label,
            'excerpt' => Str::limit(strip_tags($definition->intro), 160),
            'body' => '<p>'.e($definition->intro).'</p>',
            'template' => config('page-templates.default', 'blank'),
            'meta_title' => $definition->label.' | COMCO',
            'meta_description' => Str::limit(strip_tags($definition->intro), 160),
            'is_published' => true,
            'published_at' => now(),
        ]);
    }
}
