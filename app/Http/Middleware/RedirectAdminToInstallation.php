<?php

namespace App\Http\Middleware;

use App\Filament\Pages\SiteInstallation;
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
      'filament.admin.pages.site-installation',
      'filament.admin.auth.logout',
      'livewire.update',
    )) {
      return $next($request);
    }

    if ($request->is('admin', 'admin/*', 'livewire/*')) {
      return redirect()->to(SiteInstallation::getUrl());
    }

    return $next($request);
  }
}
