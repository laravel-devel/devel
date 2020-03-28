<?php

namespace Devel\Dev\Tests\Feature;

use Devel\Dev\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Devel\Models\Settings;

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
