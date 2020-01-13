<?php

namespace Modules\ManageUserRoles\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class ManageUserRolesServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('ManageUserRoles', 'Database/Migrations'));
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
            module_path('ManageUserRoles', 'Config/config.php') => config_path('manageuserroles.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('ManageUserRoles', 'Config/config.php'), 'manageuserroles'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/manageuserroles');

        $sourcePath = module_path('ManageUserRoles', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/manageuserroles';
        }, \Config::get('view.paths')), [$sourcePath]), 'manageuserroles');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/manageuserroles');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'manageuserroles');
        } else {
            $this->loadTranslationsFrom(module_path('ManageUserRoles', 'Resources/lang'), 'manageuserroles');
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
            app(Factory::class)->load(module_path('ManageUserRoles', 'Database/factories'));
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
