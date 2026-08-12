<?php

use App\Http\Middleware\ShowDeploymentPage;
use App\Http\Middleware\ShowMaintenancePage;
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
        $middleware->redirectGuestsTo('/admin/login');
        $middleware->prepend(ShowDeploymentPage::class);
        // Apres StartSession pour autoriser le bypass des admins authentifies.
        $middleware->web(append: [
            ShowMaintenancePage::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        $exceptions->render(function (QueryException $exception, Request $request) {
            return SiteDeploymentState::fallbackResponse($request);
        });

        $exceptions->render(function (PDOException $exception, Request $request) {
            return SiteDeploymentState::fallbackResponse($request);
        });
    })->create();
