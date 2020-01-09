<?php

namespace Modules\ManageUserPermissions\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class ManageUserPermissionsServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('ManageUserPermissions', 'Database/Migrations'));
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
            module_path('ManageUserPermissions', 'Config/config.php') => config_path('manageuserpermissions.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('ManageUserPermissions', 'Config/config.php'), 'manageuserpermissions'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/manageuserpermissions');

        $sourcePath = module_path('ManageUserPermissions', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/manageuserpermissions';
        }, \Config::get('view.paths')), [$sourcePath]), 'manageuserpermissions');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/manageuserpermissions');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'manageuserpermissions');
        } else {
            $this->loadTranslationsFrom(module_path('ManageUserPermissions', 'Resources/lang'), 'manageuserpermissions');
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
            app(Factory::class)->load(module_path('ManageUserPermissions', 'Database/factories'));
        }
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
}
