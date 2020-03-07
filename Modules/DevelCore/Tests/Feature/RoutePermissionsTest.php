<?php

namespace Modules\DevelCore\Tests\Feature;

use Modules\DevelCore\Tests\TestCase;
use Modules\DevelCore\Entities\Auth\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\DevelCore\Database\Seeders\DevelCoreDatabaseSeeder;

class RoutePermissionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DevelCoreDatabaseSeeder::class);

        $this->root = factory(User::class)->create();
        $this->user = factory(User::class)->create();

        $this->root->roles()->attach('root');
        $this->user->roles()->attach('user');
    }

    // /** @test */
    // public function certain_routes_require_certain_permissions_to_be_accessed()
    // {
        
    // }

    // /** @test */
    // public function users_without_a_route_permissions_get_redirected()
    // {
    //     // Dashboard
    //     $this->actingAs($this->admin)
    //         ->get(route('dashboard.index'))
    //         ->assertStatus(200);

    //     // Public site
    //     $this->actingAs($this->user)
    //         ->get(route('dashboard.index'))
    //         ->assertStatus(302)
    //         ->assertRedirect('/');
    // }
}
