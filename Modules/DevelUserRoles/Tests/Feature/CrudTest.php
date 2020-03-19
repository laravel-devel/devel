<?php

namespace Modules\DevelUserRoles\Tests\Feature;

use Devel\Core\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Devel\Core\Database\Seeders\DevelCoreDatabaseSeeder;
use Devel\Core\Entities\Auth\Role;
use Devel\Core\Entities\Auth\User;
use Modules\DevelDashboard\Database\Seeders\DevelDashboardDatabaseSeeder;

class CrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DevelCoreDatabaseSeeder::class);
        $this->seed(DevelDashboardDatabaseSeeder::class);

        $this->root = User::find(1);
    }

    /** @test */
    public function roots_can_create_user_roles()
    {
        $data = [
            'key' => 'test',
            'name' => 'Test',
            'permissions' => ['admin_dashboard.access'],
        ];

        $this->assertDatabaseMissing('user_roles', ['key' => $data['key']]);

        $this->actingAs($this->root)
            ->postJson(route('dashboard.develuserroles.roles.store'), $data)
            ->assertStatus(201);

        $this->assertDatabaseHas('user_roles', ['key' => $data['key']]);

        $role = Role::where('key', $data['key'])->first();

        $this->assertTrue($role->permissions->contains($data['permissions'][0]));
    }

    /** @test */
    public function roots_can_view_user_roles()
    {
        $role = factory(Role::class)->create();

        $this->actingAs($this->root)
            ->get(route('dashboard.develuserroles.roles.edit', $role->key))
            ->assertStatus(200);
    }

    /** @test */
    public function roots_can_edit_user_roles()
    {
        $role = factory(Role::class)->create();

        $data = [
            'key' => 'test',
            'name' => 'Test',
            'permissions' => ['admin_dashboard.access'],
        ];

        $this->actingAs($this->root)
            ->post(route('dashboard.develuserroles.roles.update', $role->key), $data)
            ->assertStatus(200);

        $role = Role::where('key', $data['key'])->first();

        $this->assertEquals([
            'key' => $data['key'],
            'name' => $data['name'],
        ], [
            'key' => $role['key'],
            'name' => $role['name'],
        ]);

        $this->assertTrue($role->permissions->contains($data['permissions'][0]));
    }

    /** @test */
    public function roots_can_delete_user_roles()
    {
        $role = factory(Role::class)->create();

        $this->actingAs($this->root)
            ->delete(route('dashboard.develuserroles.roles.destroy', $role->key))
            ->assertStatus(200);

        $this->assertDatabaseMissing('user_roles', ['key' => $role->key]);
    }
}
