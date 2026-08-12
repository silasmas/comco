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

    $path = $request->path();

    if (
      str_starts_with($path, 'livewire-')
      || str_starts_with($path, 'livewire/')
      || $request->hasHeader('X-Livewire')
      || $request->is(
        'admin',
        'admin/*',
        'admin/install',
        'admin/install/*',
        'public/admin',
        'public/admin/*',
        'up',
        'css/*',
        'js/*',
        'fonts/*',
      )
    ) {
      return $next($request);
    }

    return response()->view('public.deployment', [], 503);
  }
}
