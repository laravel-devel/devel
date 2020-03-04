<?php

namespace Extensions\Modules\Providers;

use Illuminate\Support\ServiceProvider;
use Extensions\Modules\Contracts\RepositoryInterface;
use Extensions\Modules\Laravel\LaravelFileRepository;

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
