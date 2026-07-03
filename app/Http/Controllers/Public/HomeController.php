<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Support\SiteDeploymentState;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Contrôleur de la page d'accueil publique.
 */
class HomeController extends Controller
{
  /**
   * Affiche la page d'accueil institutionnelle.
   *
   * @return View|Response Vue Blade ou page de déploiement
   */
  public function index(): View|Response
  {
    if (SiteDeploymentState::isDeploying()) {
      return response()->view('public.deployment', [], 503);
    }

    return view('public.home.index', [
      'metaTitle' => config('institution.name'),
      'metaDescription' => config('institution.seo.defaultDescription'),
    ]);
  }
}
