<?php

namespace App\Http\Middleware;

use App\Support\SiteDeploymentState;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirige l'administration vers la page d'installation lors du premier déploiement.
 */
class RedirectAdminToInstallation
{
  /**
   * Force l'accès à la page d'installation tant que le site n'est pas prêt.
   *
   * @param Request $request Requête HTTP entrante
   * @param Closure(Request): Response $next Suite du pipeline
   */
  public function handle(Request $request, Closure $next): Response
  {
    if (! SiteDeploymentState::requiresInstallation()) {
      return $next($request);
    }

    if ($request->routeIs(
      'comco.installation.show',
      'comco.install.*',
      'filament.admin.pages.installation-production',
    )) {
      return $next($request);
    }

    if ($request->is(
      SiteDeploymentState::installationPath(),
      SiteDeploymentState::adminPathPrefix() . '/install',
      SiteDeploymentState::adminPathPrefix() . '/install/*',
      'public/admin/site-installation',
      'public/admin/install',
      'public/admin/install/*',
    )) {
      return $next($request);
    }

    if ($request->is(
      'admin',
      'admin/*',
      'public/admin',
      'public/admin/*',
      'livewire/*',
    )) {
      return redirect()->to(SiteDeploymentState::installationPath());
    }

    return $next($request);
  }
}
