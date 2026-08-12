<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Contrôleur de la page Plan du site.
 */
class SitemapController extends Controller
{
  /**
   * Affiche le plan du site à partir de la navigation publique.
   *
   * @return View Vue Blade du plan du site
   */
  public function show(): View
  {
    return view('public.pages.sitemap', [
      'metaTitle' => 'Plan du site | '.config('institution.name'),
      'metaDescription' => 'Plan du site de la Commission de la Concurrence (COMCO) — accès rapide aux rubriques institutionnelles.',
      'mainMenu' => config('navigation.main', []),
    ]);
  }
}
