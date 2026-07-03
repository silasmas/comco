<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\View\View;

/**
 * Contrôleur des pages de contenu statique publiques.
 */
class PageController extends Controller
{
  /**
   * Affiche une page rattachée à une section du menu COMCO.
   *
   * @param string $section Identifiant de section (ex: qui-sommes-nous)
   * @param string $slug Identifiant URL de la page
   * @return View Vue Blade de la page
   */
  public function showBySection(string $section, string $slug): View
  {
    $page = Page::query()
      ->published()
      ->where('section', $section)
      ->where('slug', $slug)
      ->firstOrFail();

    return $this->renderPage($page, $section);
  }

  /**
   * Prépare la vue d'une page avec ses métadonnées SEO.
   *
   * @param Page $page Modèle page
   * @param string|null $section Section active du menu
   * @return View Vue Blade de la page
   */
  private function renderPage(Page $page, ?string $section = null): View
  {
    return view('public.pages.show', [
      'page' => $page,
      'sectionLabel' => $section ? config("navigation.sections.{$section}") : null,
      'metaTitle' => $page->meta_title ?? $page->title,
      'metaDescription' => $page->meta_description ?? config('institution.seo.defaultDescription'),
    ]);
  }
}
