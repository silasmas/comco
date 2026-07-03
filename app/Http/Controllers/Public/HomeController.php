<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Contrôleur de la page d'accueil publique.
 */
class HomeController extends Controller
{
  /**
   * Affiche la page d'accueil institutionnelle.
   *
   * @return View Vue Blade de la page d'accueil
   */
  public function index(): View
  {
    return view('public.home.index', [
      'metaTitle' => config('institution.name'),
      'metaDescription' => config('institution.seo.defaultDescription'),
    ]);
  }
}
