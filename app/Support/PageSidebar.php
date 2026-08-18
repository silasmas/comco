<?php

namespace App\Support;

use App\Models\Page;

/**
 * Résout le menu latéral d'une page CMS depuis la navigation dynamique.
 */
class PageSidebar
{
    /**
     * Retourne la structure de sidebar pour une page, ou null si absente.
     *
     * @param  Page  $page  Page CMS courante
     * @return array{label: string, section: string, hubSlug: string|null, children: list<array<string, mixed>>}|null
     */
    public static function forPage(Page $page): ?array
    {
        $section = $page->section;

        if (! filled($section)) {
            return null;
        }

        $item = collect(config('navigation.main', []))
            ->first(function (array $entry) use ($section): bool {
                if (($entry['section'] ?? null) !== $section) {
                    return false;
                }

                return ! empty($entry['sidebar'])
                    || (isset($entry['children']) && filled($entry['slug'] ?? null));
            });

        if ($item === null) {
            $item = collect(config('navigation.main', []))
                ->first(function (array $entry) use ($section): bool {
                    return ($entry['section'] ?? null) === $section
                        && isset($entry['children']);
                });
        }

        if ($item === null) {
            return null;
        }

        return [
            'label' => (string) ($item['label'] ?? $section),
            'section' => (string) $section,
            'hubSlug' => filled($item['slug'] ?? null) ? (string) $item['slug'] : null,
            'children' => array_values($item['children'] ?? []),
        ];
    }

    /**
     * Indique si la page est la page hub du menu latéral de sa section.
     *
     * @param  Page  $page  Page CMS
     * @return bool True si page hub
     */
    public static function isHub(Page $page): bool
    {
        $sidebar = self::forPage($page);

        if ($sidebar === null || $sidebar['hubSlug'] === null) {
            return false;
        }

        return $sidebar['hubSlug'] === $page->slug;
    }
}
