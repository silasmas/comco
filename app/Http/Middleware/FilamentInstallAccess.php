<?php

namespace App\Http\Middleware;

use App\Filament\Pages\SiteInstallation;
use App\Support\SiteDeploymentState;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Autorise l'accès à la page d'installation sans connexion lors du premier déploiement.
 */
class FilamentInstallAccess
{
  /**
   * Contourne l'authentification Filament pour la page d'installation initiale.
   *
   * @param Request $request Requête HTTP entrante
   * @param Closure(Request): Response $next Suite du pipeline
   */
  public function handle(Request $request, Closure $next): Response
  {
    if (
      SiteDeploymentState::requiresInstallation()
      && (
        $request->routeIs('filament.admin.pages.site-installation')
        || $request->is('livewire/*')
      )
    ) {
      return $next($request);
    }

    if (SiteDeploymentState::requiresInstallation()) {
      return redirect()->to(SiteInstallation::getUrl());
    }

    if (auth()->check()) {
      return $next($request);
    }

    return redirect()->guest(filament()->getLoginUrl());
  }
}
