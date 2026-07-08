<?php

namespace Database\Seeders;

use App\Models\NavigationItem;
use Illuminate\Database\Seeder;

/**
 * Seeder de la navigation publique COMCO.
 */
class NavigationSeeder extends Seeder
{
    /**
     * Importe la navigation initiale depuis config/navigation.php.
     */
    public function run(): void
    {
        $this->seedMainMenu();
        $this->seedFooterMenu(NavigationItem::MENU_FOOTER_NAVIGATION, config('navigation.footer.navigation', []));
        $this->seedFooterMenu(NavigationItem::MENU_FOOTER_ESERVICES, config('navigation.footer.eServices', []));
        $this->seedFooterMenu(NavigationItem::MENU_FOOTER_QUICK, config('navigation.footer.quickLinks', []));
    }

    /**
     * Crée le menu principal et ses sous-menus.
     */
    private function seedMainMenu(): void
    {
        foreach (config('navigation.main', []) as $index => $item) {
            if (isset($item['children'], $item['section'])) {
                $parent = NavigationItem::query()->updateOrCreate(
                    [
                        'menu' => NavigationItem::MENU_MAIN,
                        'label' => $item['label'],
                        'section' => $item['section'],
                    ],
                    [
                        'link_type' => NavigationItem::LINK_GROUP,
                        'sort_order' => $index,
                        'is_active' => true,
                    ],
                );

                foreach ($item['children'] as $childIndex => $child) {
                    NavigationItem::query()->updateOrCreate(
                        [
                            'menu' => NavigationItem::MENU_MAIN,
                            'parent_id' => $parent->id,
                            'label' => $child['label'],
                            'slug' => $child['slug'],
                        ],
                        [
                            'link_type' => NavigationItem::LINK_SECTION,
                            'section' => $item['section'],
                            'sort_order' => $childIndex,
                            'is_active' => true,
                        ],
                    );
                }

                continue;
            }

            NavigationItem::query()->updateOrCreate(
                [
                    'menu' => NavigationItem::MENU_MAIN,
                    'label' => $item['label'],
                    'route' => $item['route'] ?? null,
                    'url' => $item['url'] ?? null,
                ],
                [
                    'link_type' => isset($item['route']) ? NavigationItem::LINK_ROUTE : NavigationItem::LINK_EXTERNAL,
                    'sort_order' => $index,
                    'is_active' => true,
                ],
            );
        }
    }

    /**
     * Crée un menu de pied de page.
     *
     * @param  string  $menu  Identifiant du menu
     * @param  list<array<string, mixed>>  $items  Liens source
     */
    private function seedFooterMenu(string $menu, array $items): void
    {
        foreach ($items as $index => $item) {
            NavigationItem::query()->updateOrCreate(
                [
                    'menu' => $menu,
                    'label' => $item['label'],
                    'route' => $item['route'] ?? null,
                    'section' => $item['section'] ?? null,
                    'slug' => $item['slug'] ?? null,
                    'url' => $item['url'] ?? null,
                ],
                [
                    'link_type' => $this->resolveLinkType($item),
                    'sort_order' => $index,
                    'is_active' => true,
                ],
            );
        }
    }

    /**
     * Détermine le type de lien d'un élément de navigation.
     *
     * @param  array<string, mixed>  $item  Élément source
     * @return string Type de lien
     */
    private function resolveLinkType(array $item): string
    {
        if (isset($item['route'])) {
            return NavigationItem::LINK_ROUTE;
        }

        if (isset($item['url'])) {
            return NavigationItem::LINK_EXTERNAL;
        }

        return NavigationItem::LINK_SECTION;
    }
}
