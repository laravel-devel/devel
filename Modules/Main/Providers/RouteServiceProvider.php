<?php

namespace Modules\Main\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace = 'Modules\Main\Http\Controllers';

    public const HOME = '/';

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

        $this->mapDashboardRoutes();

        $this->mapPublicRoutes();
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
            ->group(module_path('Main', '/Routes/api.php'));
    }

    /**
     * Define the (admin) dashboard routes for the module.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapDashboardRoutes()
    {
        Route::middleware([
            'web',
            \Modules\DevelDashboard\Http\Middleware\DashboardAccess::class,
            \Devel\Core\Http\Middleware\CheckRoutePermissions::class,
        ])
        ->prefix(config('develdashboard.dashboard_uri'))
        ->as('dashboard.')
        ->namespace($this->moduleNamespace)
        ->group(module_path('Main', '/Routes/dashboard.php'));
    }

    /**
     * Define the public routes for the module.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapPublicRoutes()
    {
        Route::middleware([
            'web',
            \Devel\Core\Http\Middleware\CheckRoutePermissions::class,
        ])
        ->namespace($this->moduleNamespace)
        ->group(module_path('Main', '/Routes/public.php'));
    }
}
