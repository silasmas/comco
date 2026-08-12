<?php

namespace App\Support;

/**
 * Utilitaire de résolution des URLs de navigation.
 */
class NavigationUrl
{
  /**
   * Résout l'URL d'un élément de menu.
   *
   * @param array<string, mixed> $item Élément de navigation
   * @return string URL cible
   */
  public static function resolve(array $item): string
  {
    if (isset($item['route'])) {
      return route($item['route']);
    }

    if (isset($item['url'])) {
      return $item['url'];
    }

    if (isset($item['section'], $item['slug'])) {
      return route('sections.show', [
        'section' => $item['section'],
        'slug' => $item['slug'],
      ]);
    }

    return '#';
  }

  /**
   * Résout l'URL d'une page enfant à partir de la section parente.
   *
   * @param array<string, mixed> $parent Élément parent contenant la section
   * @param array<string, mixed> $child Élément enfant contenant le slug
   * @return string URL cible
   */
  public static function resolveChild(array $parent, array $child): string
  {
    return route('sections.show', [
      'section' => $parent['section'],
      'slug' => $child['slug'],
    ]);
  }
}
