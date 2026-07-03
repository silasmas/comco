<?php

namespace App\Providers;

use App\Support\SiteDeploymentState;
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

    View::composer('*', function ($view): void {
      if (! array_key_exists('errors', $view->getData())) {
        $view->with('errors', session()->get('errors', new ViewErrorBag()));
      }
    });
  }
}
