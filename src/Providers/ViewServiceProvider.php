<?php

namespace Devel\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Devel\Services\MetaTags;

class ViewServiceProvider extends ServiceProvider
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
        // Main site-wide page title
        try {
            MetaTags::setTag('title', setting('site-name', ''));
        } catch (\Exception $e) {
            MetaTags::setTag('title', config('app.name', ''));
        }

        // Page meta tags
        View::composer('devel::seo._metatags', function ($view) {
            $view->with('_metatags', MetaTags::getTags());
        });
    }
}
