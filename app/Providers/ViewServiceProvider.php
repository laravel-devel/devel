<?php

namespace App\Providers;

use App\Services\Seo\MetaTags;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Main site-wide page title
        MetaTags::setTag('title', config('app.name'));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Page meta tags
        View::composer('seo._metatags', function ($view) {
            $view->with('_metatags', MetaTags::getTags());
        });
    }
}
