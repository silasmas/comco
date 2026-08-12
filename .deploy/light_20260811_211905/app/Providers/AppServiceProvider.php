<?php

namespace App\Providers;

use App\Support\EServiceRegistry;
use App\Support\InstitutionSettings;
use App\Support\SiteDeploymentState;
use App\Support\SiteNavigation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\ViewErrorBag;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        App::setLocale(config('app.locale', 'fr'));
        Paginator::useBootstrapFive();
        SiteDeploymentState::syncLocalInstallationMarker();
        InstitutionSettings::applyToConfig();
        SiteNavigation::applyToConfig();
        EServiceRegistry::applyToConfig();

        View::composer('*', function ($view): void {
            if (! array_key_exists('errors', $view->getData())) {
                $view->with('errors', session()->get('errors', new ViewErrorBag));
            }
        });
    }
}
