<?php

namespace Devel\Modules\Providers;

use Illuminate\Support\ServiceProvider;
use Devel\Modules\Contracts\RepositoryInterface;
use Devel\Modules\Laravel\LaravelFileRepository;

class ContractsServiceProvider extends ServiceProvider
{
    /**
     * Register some binding.
     */
    public function register()
    {
        $this->app->bind(RepositoryInterface::class, LaravelFileRepository::class);
    }
}
