<?php

namespace App\Providers;

use App\Services\DashboardSidebarMenu;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // dd(\Module::toCollection());
        // dd(\Module::allEnabled()['Pages']);
        // dd($this->app->router->getRoutes());
        // dd($this->app);

        // Add dashboard menu items
        View::composer('dashboard._sidebar', function ($view) {
            foreach (\Route::getRoutes() as $route) {
                if (isset($route->action['dashboardMenu'])) {
                    $parts = explode('->', $route->action['dashboardMenu']);

                    if (count($parts) < 3) {
                        throw new \Exception("Invalid dashboard menu entry for route \"{$route->uri}\".");
                    }

                    DashboardSidebarMenu::addItems($parts[0], $parts[1], [
                        url($route->uri) => $parts[2],
                    ]);
                }
            }

            $view->with('sidebarMenu', DashboardSidebarMenu::getItems());
        });
    }
}
