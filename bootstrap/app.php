<?php

use App\Filament\Pages\SiteInstallation;
use App\Http\Middleware\ShowDeploymentPage;
use App\Support\SiteDeploymentState;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prependToGroup('web', [
            ShowDeploymentPage::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        $exceptions->render(function (QueryException $exception, Request $request) {
            if (SiteDeploymentState::isInstalled()) {
                return null;
            }

            if ($request->is('admin', 'admin/*', 'livewire/*')) {
                return redirect()->to(SiteInstallation::getUrl());
            }

            if (! $request->is('up')) {
                return response()->view('public.deployment', [], 503);
            }

            return null;
        });
    })->create();
