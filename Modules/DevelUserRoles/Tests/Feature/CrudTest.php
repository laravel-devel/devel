<?php

namespace Modules\DevelUserRoles\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\DevelCore\Database\Seeders\DevelCoreDatabaseSeeder;
use Modules\DevelCore\Entities\Auth\Role;
use Modules\DevelCore\Entities\Auth\User;
use Modules\DevelUserRoles\Database\Seeders\DevelUserRolesDatabaseSeeder;

class CrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DevelCoreDatabaseSeeder::class);
        $this->seed(DevelUserRolesDatabaseSeeder::class);

        $this->root = User::find(1);
    }

    /** @test */
    public function admins_can_create_user_roles()
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
    public function admins_can_edit_user_roles()
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
    public function admins_can_delete_user_roles()
    {
        $role = factory(Role::class)->create();

        $this->actingAs($this->root)
            ->delete(route('dashboard.develuserroles.roles.destroy', $role->key))
            ->assertStatus(200);

        $this->assertDatabaseMissing('user_roles', ['key' => $role->key]);
    }
}
