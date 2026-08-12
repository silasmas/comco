<?php

namespace App\Http\Middleware;

use App\Support\MaintenanceMode;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Affiche la page de maintenance lorsque le mode est activé depuis le dashboard.
 */
class ShowMaintenancePage
{
  public const PREVIEW_SESSION_KEY = 'maintenance.preview_bypass';

  /**
   * Intercepte les requêtes publiques pendant la maintenance.
   *
   * @param  Request  $request  Requête HTTP entrante
   * @param  Closure(Request): Response  $next  Suite du pipeline
   * @return Response Réponse HTTP (page 503 ou suite normale)
   */
  public function handle(Request $request, Closure $next): Response
  {
    if (! MaintenanceMode::isEnabled()) {
      return $next($request);
    }

    if ($this->shouldBypass($request)) {
      return $next($request);
    }

    return response()->view('public.maintenance', [
      'title' => MaintenanceMode::title(),
      'message' => MaintenanceMode::message(),
      'isPreview' => false,
      'institution' => config('institution.shortName', 'COMCO'),
      'contactEmail' => config('institution.contact.email'),
    ], 503);
  }

  /**
   * Indique si la requête doit ignorer la page de maintenance.
   *
   * L'admin, Livewire et les assets restent accessibles. Le site public
   * réel n'est visible que via le bypass de prévisualisation explicite.
   *
   * @param  Request  $request  Requête HTTP entrante
   * @return bool True si la requête ne doit pas être bloquée
   */
  private function shouldBypass(Request $request): bool
  {
    if ($request->hasHeader('X-Livewire')) {
      return true;
    }

    $path = $request->path();

    if (str_starts_with($path, 'livewire')) {
      return true;
    }

    if ($this->isStaticAsset($path)) {
      return true;
    }

    if ($request->is(
      'admin',
      'admin/*',
      'admin/install',
      'admin/install/*',
      'public/admin',
      'public/admin/*',
      'up',
      'maintenance-preview',
    )) {
      return true;
    }

    return Auth::check() && $request->session()->get(self::PREVIEW_SESSION_KEY) === true;
  }

  /**
   * Indique si le chemin correspond à un asset statique public.
   *
   * @param  string  $path  Chemin URL relatif
   * @return bool True pour CSS, JS, images, polices, thème, etc.
   */
  private function isStaticAsset(string $path): bool
  {
    return str_starts_with($path, 'theme/')
      || str_starts_with($path, 'assets/')
      || str_starts_with($path, 'vendors/')
      || str_starts_with($path, 'build/')
      || str_starts_with($path, 'storage/')
      || str_starts_with($path, 'css/')
      || str_starts_with($path, 'js/')
      || str_starts_with($path, 'fonts/')
      || str_starts_with($path, 'vendor/');
  }
}
