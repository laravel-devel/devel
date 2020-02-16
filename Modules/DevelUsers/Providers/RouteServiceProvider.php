<?php

namespace Modules\DevelUsers\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace = 'Modules\DevelUsers\Http\Controllers';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->moduleNamespace)
            ->group(module_path('DevelUsers', '/Routes/web.php'));

        if (file_exists(module_path('DevelUsers', '/Routes/dashboard.php'))) {
            Route::middleware([
                'web',
                \Modules\DevelDashboard\Http\Middleware\DashboardAccess::class,
                \Modules\DevelDashboard\Http\Middleware\CheckDashboardPermissions::class,
            ])
            ->as('dashboard.')
            ->namespace($this->moduleNamespace)
            ->group(module_path('DevelUsers', '/Routes/dashboard.php'));
        }
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(module_path('DevelUsers', '/Routes/api.php'));
    }
}
