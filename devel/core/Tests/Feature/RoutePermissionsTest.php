<?php

namespace Devel\Core\Tests\Feature;

use Devel\Core\Tests\TestCase;
use Devel\Core\Entities\Auth\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Devel\Core\Database\Seeders\DevelCoreDatabaseSeeder;
use Illuminate\Support\Facades\Route;
use Modules\DevelDashboard\Database\Seeders\DevelDashboardDatabaseSeeder;

class RoutePermissionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DevelCoreDatabaseSeeder::class);
        $this->seed(DevelDashboardDatabaseSeeder::class);

        $this->root = factory(User::class)->create();
        $this->user = factory(User::class)->create();

        $this->root->roles()->attach('root');
        $this->user->roles()->attach('user');
    }

    /** @test */
    public function certain_public_routes_require_certain_permissions_to_be_accessed()
    {
        // Create a public protected route
        Route::get('/protected', [
            'as' => 'some.route',
            'uses' => 'Devel\Core\Tests\TestController@index',
            'middleware' => \Devel\Core\Http\Middleware\CheckRoutePermissions::class,
            'permissions' => 'some.permission',
        ]);

        // Visit the homepage first
        $this->actingAs($this->user)
            ->get('/')
            ->assertStatus(200);

        // Visit the protected route while not having the permission
        $this->actingAs($this->user)
            ->get(route('some.route'))
            ->assertStatus(302)
            ->assertRedirect('/');
    }

    /** @test */
    public function certain_dashboard_routes_require_certain_permissions_to_be_accessed()
    {
        // Create a protected dashboard route
        Route::get(config('develdashboard.dashboard_uri') . '/protected', [
            'as' => 'some.route',
            'uses' => 'Devel\Core\Tests\TestController@index',
            'middleware' => \Devel\Core\Http\Middleware\CheckRoutePermissions::class,
            'permissions' => 'some.permission',
        ]);

        // Visit the dashboard home page first
        $this->actingAs($this->root)
            ->get(route('dashboard.index'))
            ->assertStatus(200);

        // Visit the protected route while not having the permission
        $this->actingAs($this->root)
            ->get(route('some.route'))
            ->assertStatus(302)
            ->assertRedirect(config('develdashboard.dashboard_uri'));
    }
}
