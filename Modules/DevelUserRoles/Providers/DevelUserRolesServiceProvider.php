<?php

namespace Modules\DevelUserRoles\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class DevelUserRolesServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('DevelUserRoles', 'Database/Migrations'));
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
            module_path('DevelUserRoles', 'Config/config.php') => config_path('develuserroles.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('DevelUserRoles', 'Config/config.php'), 'develuserroles'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/develuserroles');

        $sourcePath = module_path('DevelUserRoles', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/develuserroles';
        }, \Config::get('view.paths')), [$sourcePath]), 'develuserroles');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/develuserroles');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'develuserroles');
        } else {
            $this->loadTranslationsFrom(module_path('DevelUserRoles', 'Resources/lang'), 'develuserroles');
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
            app(Factory::class)->load(module_path('DevelUserRoles', 'Database/factories'));
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
}
