<?php

namespace Devel\Core\Tests\Feature;

use Devel\Core\Tests\TestCase;
use Devel\Core\Entities\Auth\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Devel\Core\Database\Seeders\DevelCoreDatabaseSeeder;
use Devel\Core\Entities\Settings;
use Illuminate\Support\Facades\Route;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function settings_can_be_set_and_read()
    {
        $key = 'test-setting';
        $value = 'test value';

        $this->assertNull(Settings::read($key));

        Settings::set($key, $value);

        $this->assertEquals($value, Settings::read($key));
    }
}
