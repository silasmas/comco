<?php

namespace App\Support;

use App\Models\NavigationItem;
use Illuminate\Support\Facades\Schema;

/**
 * Construit la navigation publique depuis la base ou la configuration.
 */
class SiteNavigation
{
    /**
     * Retourne la structure complète de navigation.
     *
     * @return array<string, mixed> Navigation compatible avec les vues existantes
     */
    public static function resolve(): array
    {
        if (! Schema::hasTable('navigation_items') || NavigationItem::query()->count() === 0) {
            return config('navigation', []);
        }

        return [
            'main' => self::mainMenu(),
            'footer' => [
                'navigation' => self::footerMenu(NavigationItem::MENU_FOOTER_NAVIGATION),
                'eServices' => self::footerMenu(NavigationItem::MENU_FOOTER_ESERVICES),
                'quickLinks' => self::footerMenu(NavigationItem::MENU_FOOTER_QUICK),
            ],
            'sections' => self::sections(),
        ];
    }

    /**
     * Applique la navigation dynamique à la configuration Laravel.
     */
    public static function applyToConfig(): void
    {
        if (! Schema::hasTable('navigation_items')) {
            return;
        }

        config(['navigation' => self::resolve()]);
    }

    /**
     * Retourne les sections routables avec leurs libellés.
     *
     * @return array<string, string> Sections disponibles
     */
    public static function sections(): array
    {
        if (! Schema::hasTable('navigation_items') || NavigationItem::query()->count() === 0) {
            return config('navigation.sections', []);
        }

        $sections = NavigationItem::query()
            ->where('menu', NavigationItem::MENU_MAIN)
            ->whereNull('parent_id')
            ->where('link_type', NavigationItem::LINK_GROUP)
            ->active()
            ->orderBy('sort_order')
            ->pluck('label', 'section')
            ->filter(fn (string $label, ?string $section): bool => filled($section))
            ->all();

        if ($sections === []) {
            return config('navigation.sections', []);
        }

        return $sections;
    }

    /**
     * Retourne le menu principal hiérarchique.
     *
     * @return list<array<string, mixed>> Éléments du menu principal
     */
    private static function mainMenu(): array
    {
        return NavigationItem::query()
            ->where('menu', NavigationItem::MENU_MAIN)
            ->whereNull('parent_id')
            ->active()
            ->orderBy('sort_order')
            ->with(['children' => fn ($query) => $query->active()->orderBy('sort_order')])
            ->get()
            ->map(fn (NavigationItem $item): array => $item->toNavArray())
            ->all();
    }

    /**
     * Retourne un menu de pied de page.
     *
     * @param  string  $menu  Identifiant du menu footer
     * @return list<array<string, mixed>> Liens du pied de page
     */
    private static function footerMenu(string $menu): array
    {
        return NavigationItem::query()
            ->where('menu', $menu)
            ->whereNull('parent_id')
            ->active()
            ->orderBy('sort_order')
            ->get()
            ->map(fn (NavigationItem $item): array => $item->toNavArray())
            ->all();
    }
}
