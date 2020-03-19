<?php

namespace Modules\DevelUserRoles\Tests\Feature;

use Devel\Core\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Devel\Core\Database\Seeders\DevelCoreDatabaseSeeder;
use Devel\Core\Entities\Auth\Role;
use Devel\Core\Entities\Auth\User;
use Modules\DevelDashboard\Database\Seeders\DevelDashboardDatabaseSeeder;

class RootRoleTest extends TestCase
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
    public function the_root_role_cannot_be_deleted()
    {
        $this->actingAs($this->root)
            ->delete(route('dashboard.develuserroles.roles.destroy', 'root'))
            ->assertStatus(409);

        $this->assertDatabaseHas('user_roles', ['key' => 'root']);
    }

    /** @test */
    public function the_root_role_cannot_be_altered()
    {
        $role = Role::findOrFail('root');

        $permissions = $role->permissions->pluck('key')->toArray();

        $data = [
            'key' => 'test',
            'name' => 'Test',
            'permissions' => ['admin_dashboard.access'],
        ];

        $this->actingAs($this->root)
            ->postJson(route('dashboard.develuserroles.roles.update', $role->key), $data)
            ->assertStatus(200);

        $role->refresh();

        $this->assertEquals([
            'key' => 'root',
            'name' => 'Root',
        ], [
            'key' => $role->key,
            'name' => $role->name,
        ]);
        
        $this->assertEquals(
            $permissions, $role->permissions->pluck('key')->toArray()
        );
    }
}
