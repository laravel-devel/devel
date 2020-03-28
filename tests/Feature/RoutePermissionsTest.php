<?php

namespace Devel\Dev\Tests\Feature;

use Devel\Dev\Tests\TestCase;
use Devel\Models\Auth\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Devel\Database\Seeders\DevelDatabaseSeeder;
use Devel\Modules\LaravelModulesServiceProvider;
use Illuminate\Support\Facades\Route;

class RoutePermissionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->resolveProvider(LaravelModulesServiceProvider::class)->register();

        $this->seed(DevelDatabaseSeeder::class);

        $this->root = factory(User::class)->create();
        $this->user = factory(User::class)->create();

        $this->root->roles()->attach('root');
        $this->user->roles()->attach('user');
    }

    /** @test */
    public function certain_public_routes_require_certain_permissions_to_be_accessed()
    {
        // Create public unprotected and protected routes
        Route::get('/', [
            'as' => 'tests-public-route',
            'uses' => 'Devel\Dev\Tests\TestController@index',
            'middleware' => \Devel\Http\Middleware\CheckRoutePermissions::class,
        ]);

        Route::get('/protected', [
            'as' => 'tests-protected-route',
            'uses' => 'Devel\Dev\Tests\TestController@index',
            'middleware' => \Devel\Http\Middleware\CheckRoutePermissions::class,
            'permissions' => 'some.permission',
        ]);

        // Visit the homepage first
        $this->actingAs($this->user)
            ->get(route('tests-public-route'))
            ->assertStatus(200);

        // Visit the protected route while not having the permission
        $this->actingAs($this->user)
            ->get(route('tests-protected-route'))
            ->assertStatus(302)
            ->assertRedirect('/');
    }
}
