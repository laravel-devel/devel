<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        if ($app->configurationIsCached()) {
            Artisan::call('config:clear');

            return $this->createApplication();
        }

        return $app;
    }
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }
}
