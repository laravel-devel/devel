<?php

namespace Modules\DevelCore\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class DevelCoreServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('DevelCore', 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(ViewServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('DevelCore', 'Config/config.php') => config_path('develcore.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('DevelCore', 'Config/config.php'), 'develcore'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/develcore');

        $sourcePath = module_path('DevelCore', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/develcore';
        }, \Config::get('view.paths')), [$sourcePath]), 'develcore');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/develcore');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'develcore');
        } else {
            $this->loadTranslationsFrom(module_path('DevelCore', 'Resources/lang'), 'develcore');
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
            app(Factory::class)->load(module_path('DevelCore', 'Database/factories'));
        }
    }

    /**
     * Register additional console commands.
     *
     * @return void
     */
    public function registerCommands()
    {
        $this->commands([
            \Modules\DevelCore\Console\InstallCommand::class,
            \Modules\DevelCore\Console\ModuleInstallCommand::class,
            \Modules\DevelCore\Console\ModuleUninstallCommand::class,
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
