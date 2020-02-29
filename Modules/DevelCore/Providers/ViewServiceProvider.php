<?php

namespace Modules\DevelCore\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\DevelCore\Services\MetaTags;

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
        try {
            MetaTags::setTag('title', setting('site-name', ''));
        } catch (\Exception $e) {
            MetaTags::setTag('title', config('app.name', ''));
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Page meta tags
        View::composer('develcore::seo._metatags', function ($view) {
            $view->with('_metatags', MetaTags::getTags());
        });
    }
}
