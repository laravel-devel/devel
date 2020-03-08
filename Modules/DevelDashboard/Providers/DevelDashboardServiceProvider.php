<?php

namespace Modules\DevelDashboard\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\View;
use Modules\DevelDashboard\Services\SidebarMenu;

class DevelDashboardServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->registerCommands();
        $this->loadMigrationsFrom(module_path('DevelDashboard', 'Database/Migrations'));
        $this->addSidebarMenuItems();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('DevelDashboard', 'Config/config.php') => config_path('develdashboard.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('DevelDashboard', 'Config/config.php'), 'develdashboard'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/develdashboard');

        $sourcePath = module_path('DevelDashboard', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/develdashboard';
        }, \Config::get('view.paths')), [$sourcePath]), 'develdashboard');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/develdashboard');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'develdashboard');
        } else {
            $this->loadTranslationsFrom(module_path('DevelDashboard', 'Resources/lang'), 'develdashboard');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(module_path('DevelDashboard', 'Database/factories'));
        }
    }

    /**
     * Register the module's console commands.
     *
     * @return void
     */
    public function registerCommands()
    {
        $this->commands([
            //
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    /**
     * Add items to the dashboard sidebar menu from all the modules.
     *
     * @return void
     */
    protected function addSidebarMenuItems()
    {
        // Add dashboard menu items
        View::composer('develdashboard::_sidebar', function ($view) {
            foreach (\Route::getRoutes() as $route) {
                // Check if the user has permissions to access the route
                $permissions = $route->getAction()['permissions'] ?? [];

                if (!is_array($permissions) && !is_string($permissions)) {
                    throw new \Exception('The route permissions should be set in as a string or an array of strings.');
                }

                if (!auth()->check() || !auth()->user()->hasPermissions($permissions)) {
                    continue;
                }

                if (isset($route->getAction()['dashboardSidebar'])) {
                    $parts = explode('->', $route->getAction()['dashboardSidebar']);

                    if (count($parts) < 2) {
                        throw new \Exception("Invalid dashboard sidebar menu entry for route \"{$route->uri}\".");
                    }

                    SidebarMenu::addItem(
                        $parts[0],
                        array_slice($parts, 1),
                        $route->uri
                    );
                }
            }

            $view->with('sidebarMenu', SidebarMenu::getSortedItems());
        });
    }
}
