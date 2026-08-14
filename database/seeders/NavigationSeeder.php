<?php

namespace Database\Seeders;

use App\Models\NavigationItem;
use Illuminate\Database\Seeder;

/**
 * Seeder de la navigation publique COMCO.
 *
 * La configuration `config/navigation.php` est la source de vérité :
 * chaque exécution reconstruit les menus pour éviter les doublons
 * après renommage d'intitulés.
 */
class NavigationSeeder extends Seeder
{
  /**
   * Importe la navigation depuis config/navigation.php.
   */
  public function run(): void
  {
    $this->seedMainMenu();
    $this->seedFooterMenu(NavigationItem::MENU_FOOTER_NAVIGATION, config('navigation.footer.navigation', []));
    $this->seedFooterMenu(NavigationItem::MENU_FOOTER_ESERVICES, config('navigation.footer.eServices', []));
    $this->seedFooterMenu(NavigationItem::MENU_FOOTER_QUICK, config('navigation.footer.quickLinks', []));
  }

  /**
   * Recrée le menu principal et ses sous-menus.
   */
  private function seedMainMenu(): void
  {
    NavigationItem::query()
      ->where('menu', NavigationItem::MENU_MAIN)
      ->whereNotNull('parent_id')
      ->delete();

    NavigationItem::query()
      ->where('menu', NavigationItem::MENU_MAIN)
      ->delete();

    foreach (config('navigation.main', []) as $index => $item) {
      if (isset($item['children'], $item['section'])) {
        $parent = NavigationItem::query()->create([
          'menu' => NavigationItem::MENU_MAIN,
          'parent_id' => null,
          'label' => $item['label'],
          'link_type' => NavigationItem::LINK_GROUP,
          'section' => $item['section'],
          'slug' => $item['slug'] ?? null,
          'sort_order' => $index,
          'is_active' => true,
        ]);

        foreach ($item['children'] as $childIndex => $child) {
          NavigationItem::query()->create([
            'menu' => NavigationItem::MENU_MAIN,
            'parent_id' => $parent->id,
            'label' => $child['label'],
            'link_type' => NavigationItem::LINK_SECTION,
            'section' => $item['section'],
            'slug' => $child['slug'],
            'sort_order' => $childIndex,
            'is_active' => true,
          ]);
        }

        continue;
      }

      NavigationItem::query()->create([
        'menu' => NavigationItem::MENU_MAIN,
        'parent_id' => null,
        'label' => $item['label'],
        'link_type' => $this->resolveLinkType($item),
        'route' => $item['route'] ?? null,
        'section' => $item['section'] ?? null,
        'slug' => $item['slug'] ?? null,
        'url' => $item['url'] ?? null,
        'sort_order' => $index,
        'is_active' => true,
      ]);
    }
  }

  /**
   * Recrée un menu de pied de page.
   *
   * @param  string  $menu  Identifiant du menu
   * @param  list<array<string, mixed>>  $items  Liens source
   */
  private function seedFooterMenu(string $menu, array $items): void
  {
    NavigationItem::query()
      ->where('menu', $menu)
      ->delete();

    foreach ($items as $index => $item) {
      NavigationItem::query()->create([
        'menu' => $menu,
        'parent_id' => null,
        'label' => $item['label'],
        'link_type' => $this->resolveLinkType($item),
        'route' => $item['route'] ?? null,
        'section' => $item['section'] ?? null,
        'slug' => $item['slug'] ?? null,
        'url' => $item['url'] ?? null,
        'sort_order' => $index,
        'is_active' => true,
      ]);
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
