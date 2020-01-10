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
        View::composer('develdashboard::_sidebar', function ($view) {
            foreach (\Route::getRoutes() as $route) {
                // TODO: Don't include an item if current user doesn't have
                // permissions to access it. The list of required permissions
                // will be also attached to the route and accessible in the same
                // way as 'dashboardMenu'.
                if (isset($route->action['dashboardMenu'])) {
                    $parts = explode('->', $route->action['dashboardMenu']);

                    if (count($parts) < 2) {
                        throw new \Exception("Invalid dashboard menu entry for route \"{$route->uri}\".");
                    }

                    DashboardSidebarMenu::addItem(
                        $parts[0], array_slice($parts, 1), $route->uri
                    );
                }
            }

            $view->with('sidebarMenu', DashboardSidebarMenu::getItems());
        });
    }
}
