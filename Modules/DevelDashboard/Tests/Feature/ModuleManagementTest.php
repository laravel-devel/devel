<?php

namespace Modules\DevelDashboard\Tests\Feature;

use Modules\DevelCore\Tests\TestCase;
use Modules\DevelCore\Entities\Auth\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\DevelCore\Database\Seeders\DevelCoreDatabaseSeeder;
use Extensions\Modules\Facades\Module;

class ModuleManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DevelCoreDatabaseSeeder::class);

        $this->admin = factory(User::class)->create();
        $this->admin->roles()->attach('admin');
    }

    /** @test */
    public function admins_can_view_all_the_available_modules()
    {
        // The page should display all the modules except 'DevelCore' and
        // 'DevelDashboard'
        $expected = array_values(array_filter(array_keys(Module::all()), function ($key) {
            return (!in_array($key, ['DevelCore', 'DevelDashboard']));
        }));

        $response = $this->actingAs($this->admin)
            ->get(route('dashboard.modules.index'));

        $response->assertStatus(200);
        $response->assertViewHas('modules');

        $actual = $response->getOriginalContent()->getData()['modules'];

        $this->assertEquals($expected, array_keys($actual));
    }

    /** @test */
    public function modules_can_be_enabled_and_disabled()
    {
        // Find the first module
        $firstModule = '';

        foreach (Module::all() as $key => $module) {
            if (in_array($key, ['DevelCore', 'DevelDashboard'])) {
                continue;
            }

            $firstModule = $module;

            break;
        }

        // We're working with real modules state here, so we need to remember
        // the original value
        $enabled = $firstModule->isEnabled();

        $this->assertEquals($enabled, $firstModule->isEnabled());

        try {
            // Now try toggling the module's status
            $this->actingAs($this->admin)
                ->post(route('dashboard.modules.toggle-enabled', $firstModule->getAlias()))
                ->assertStatus(200);

            // Make sure it has changed
            $this->assertEquals(!$enabled, $firstModule->isEnabled());
        } catch (\Exception $e) {
            // Return the original value
            if ($enabled) {
                $firstModule->enable();
            } else {
                $firstModule->disable();
            }

            throw $e;
        }

        // Return the original value
        if ($enabled) {
            $firstModule->enable();
        } else {
            $firstModule->disable();
        }
    }

    /** @test */
    public function certain_modules_cannot_be_disabled()
    {
        // Only 'DevelCore' and 'DevelDashboard' cannot be disabled
        // Find the first module
        $firstModule = '';

        foreach (Module::all() as $key => $module) {
            if (in_array($key, ['DevelCore', 'DevelDashboard'])) {
                $firstModule = $module;

                break;
            }
        }

        if (!$firstModule) {
            throw 'Core modules not found in the app!';
        }

        // We're working with real modules state here, so we need to remember
        // the original value
        $enabled = $firstModule->isEnabled();

        $this->assertEquals($enabled, $firstModule->isEnabled());

        try {
            // Now try toggling the module's status
            $this->actingAs($this->admin)
                ->post(route('dashboard.modules.toggle-enabled', $firstModule->getAlias()))
                ->assertStatus(422);

            // Make sure nothing has changed
            $this->assertEquals($enabled, $firstModule->isEnabled());
        } catch (\Exception $e) {
            // Return the original value
            if ($enabled) {
                $firstModule->enable();
            } else {
                $firstModule->disable();
            }

            throw $e;
        }

        // Return the original value
        if ($enabled) {
            $firstModule->enable();
        } else {
            $firstModule->disable();
        }
    }
}
