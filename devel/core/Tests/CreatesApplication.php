<?php

namespace Devel\Core\Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../../../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        if ($app->configurationIsCached()) {
            Artisan::call('config:clear');

            return $this->createApplication();
        }

        return $app;
    }
}
