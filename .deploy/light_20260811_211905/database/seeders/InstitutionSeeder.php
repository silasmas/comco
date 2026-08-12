<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Seeder des pages institutionnelles COMCO (menus + contenu documents client).
 */
class InstitutionSeeder extends Seeder
{
    /**
     * Exécute le seeding des pages publiques.
     */
    public function run(): void
    {
        $validSections = array_keys(config('navigation.sections'));
        $pageContents = config('pages-content');

        Page::query()->whereNotIn('section', $validSections)->delete();

        foreach ($pageContents as $section => $pages) {
            foreach ($pages as $slug => $content) {
                Page::query()->updateOrCreate(
                    [
                        'section' => $section,
                        'slug' => $slug,
                    ],
                    [
                        'title' => $content['title'],
                        'excerpt' => $content['excerpt'],
                        'body' => $content['body'],
                        'template' => config("page-templates.{$section}.{$slug}") ?? config('page-templates.default'),
                        'meta_title' => $content['title'].' | COMCO',
                        'meta_description' => $content['excerpt'],
                        'is_published' => true,
                        'published_at' => now(),
                    ]
                );
            }
        }

        Page::query()
            ->whereIn('section', $validSections)
            ->whereNotIn('slug', collect($pageContents)->flatMap(fn ($pages) => array_keys($pages))->all())
            ->delete();
    }
}
