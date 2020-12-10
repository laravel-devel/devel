<?php

namespace Devel\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class DevelServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerViews();
        $this->registerFactories();
        $this->registerCommands();
        $this->registerMigrations();
        $this->registerConfiguration();
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
     * Register configuration.
     *
     * @return void
     */
    public function registerConfiguration()
    {
        $sourcePath = __DIR__ . '/../Config/devel.php';
        $this->mergeConfigFrom($sourcePath, 'devel');

        $this->publishes([
            $sourcePath => config_path('devel.php'),
        ], 'devel-config');
    }

    /**
     * Register migrations.
     *
     * @return void
     */
    public function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $this->app->register(ViewServiceProvider::class);

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->loadViewsFrom($sourcePath, 'devel');

        $this->publishes([
            $sourcePath => resource_path('views/vendor/devel'),
        ]);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $sourcePath = __DIR__ . '/../Resources/lang';

        $this->loadTranslationsFrom($sourcePath, 'devel');

        $this->publishes([
            $sourcePath => resource_path('lang/vendor/devel'),
        ]);
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        $this->loadFactoriesFrom(__DIR__ . '/../Database/factories');
    }

    /**
     * Register additional console commands.
     *
     * @return void
     */
    public function registerCommands()
    {
        $this->commands([
            \Devel\Console\InstallCommand::class,
            \Devel\Console\UpdateCommand::class,
            \Devel\Console\ResetPasswordCommand::class,
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
