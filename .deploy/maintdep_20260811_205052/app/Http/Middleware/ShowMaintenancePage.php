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

        if (Auth::check()) {
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
            'maintenance-preview',
        )) {
            return $next($request);
        }

        return response()->view('public.maintenance', [
            'title' => MaintenanceMode::title(),
            'message' => MaintenanceMode::message(),
            'isPreview' => false,
        ], 503);
    }
}
