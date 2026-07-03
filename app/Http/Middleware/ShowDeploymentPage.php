<?php

namespace App\Http\Middleware;

use App\Support\SiteDeploymentState;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Affiche la page publique de déploiement tant que le site n'est pas installé.
 */
class ShowDeploymentPage
{
  /**
   * Intercepte les requêtes publiques pendant le déploiement initial.
   *
   * @param Request $request Requête HTTP entrante
   * @param Closure(Request): Response $next Suite du pipeline
   */
  public function handle(Request $request, Closure $next): Response
  {
    if (! SiteDeploymentState::isDeploying()) {
      return $next($request);
    }

    if ($request->is(
      'admin',
      'admin/*',
      'admin/install',
      'admin/install/*',
      'public/admin',
      'public/admin/*',
      'up',
      'livewire/*',
    )) {
      return $next($request);
    }

    return response()->view('public.deployment', [], 503);
  }
}
