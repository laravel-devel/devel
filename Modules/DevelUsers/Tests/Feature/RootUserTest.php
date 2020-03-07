<?php

namespace Modules\DevelUsers\Tests\Feature;

use Devel\Core\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Devel\Core\Database\Seeders\DevelCoreDatabaseSeeder;
use Devel\Core\Entities\Auth\User;
use Modules\DevelUsers\Database\Seeders\DevelUsersDatabaseSeeder;

class RootUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DevelCoreDatabaseSeeder::class);
        $this->seed(DevelUsersDatabaseSeeder::class);

        $this->root = User::find(1);
        $this->admin = factory(User::class)->create();

        $this->admin->roles()->attach('admin');
        $this->admin->permissions()->attach('users.edit');
        $this->admin->permissions()->attach('users.delete');
    }

    /** @test */
    public function no_one_can_edit_the_root_user_except_for_the_root_itself()
    {
        $data = [
            'name' => 'Root',
            'email' => 'new@email.com',
            'password' => 'newpassword',
        ];

        $this->actingAs($this->admin)
            ->post(route('dashboard.develusers.users.update', $this->root->id), $data)
            ->assertStatus(409);

        // Assert the root user has not changed
        $this->root = $this->root->fresh();
        $this->assertEquals(config('devel.root.default_email'), $this->root->email);
        $this->assertNotEquals($data['name'], $this->root->name);
        $this->assertTrue(Hash::check(config('devel.root.default_password'), $this->root->password));

        $this->actingAs($this->root)
            ->post(route('dashboard.develusers.users.update', $this->root->id), $data)
            ->assertStatus(200);

        // Assert the root user has changed
        $this->root = $this->root->fresh();
        $this->assertEquals($data['email'], $this->root->email);
        $this->assertEquals($data['name'], $this->root->name);
        $this->assertTrue(Hash::check($data['password'], $this->root->password));
    }

    /** @test */
    public function the_root_user_profile_cannot_be_deleted()
    {
        // Model-level protection
        $this->assertFalse($this->root->delete());

        // Backend protection
        $this->actingAs($this->root)
            ->delete(route('dashboard.develusers.users.destroy', $this->root->id))
            ->assertStatus(409);

        $this->actingAs($this->admin)
            ->delete(route('dashboard.develusers.users.destroy', $this->root->id))
            ->assertStatus(409);
    }

    /** @test */
    public function the_root_user_roles_cannot_be_altered()
    {
        $data = [
            'name' => 'Root',
            'email' => 'new@email.com',
            'roles' => ['admin', 'user'],
        ];

        $this->actingAs($this->root)
            ->post(route('dashboard.develusers.users.update', $this->root->id), $data)
            ->assertStatus(200);

        // Assert the root user's roles are intact
        $this->root = $this->root->fresh();
        $this->assertCount(1, $this->root->roles);
        $this->assertTrue($this->root->roles->contains('root'));
    }

    /** @test */
    public function the_root_user_permissions_cannot_be_altered()
    {
        $data = [
            'name' => 'Root',
            'email' => 'new@email.com',
            'permissions' => ['users.edit', 'users.delete'],
        ];

        // The root has 0 direct permissions, all the permissions come from the
        // Root role...
        $this->actingAs($this->root)
            ->post(route('dashboard.develusers.users.update', $this->root->id), $data)
            ->assertStatus(200);

        // ... and this should not change
        $this->assertCount(0, $this->root->fresh()->permissions);
    }

    /** @test */
    public function the_root_role_cannot_be_assigned_to_anyone()
    {
        $data = [
            'name' => 'Second Root',
            'email' => 'new@email.com',
            'roles' => ['root', 'admin', 'user'],
        ];

        $this->actingAs($this->root)
            ->post(route('dashboard.develusers.users.update', $this->admin->id), $data)
            ->assertStatus(200);

        // Assert the root user's roles are intact
        $this->admin = $this->admin->fresh();
        $this->assertCount(2, $this->admin->roles);
        $this->assertFalse($this->admin->roles->contains('root'));
    }
}
