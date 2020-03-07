<?php

namespace Modules\DevelUsers\Tests\Feature;

use Modules\DevelCore\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Modules\DevelCore\Database\Seeders\DevelCoreDatabaseSeeder;
use Modules\DevelCore\Entities\Auth\User;
use Modules\DevelUsers\Database\Seeders\DevelUsersDatabaseSeeder;

class CrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DevelCoreDatabaseSeeder::class);
        $this->seed(DevelUsersDatabaseSeeder::class);

        $this->root = User::find(1);
    }

    /** @test */
    public function roots_can_create_users()
    {
        $data = [
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => 'qwerty1234',
            'roles' => ['user'],
        ];

        $this->assertDatabaseMissing('users', ['email' => $data['email']]);

        $this->actingAs($this->root)
            ->post(route('dashboard.develusers.users.store'), $data)
            ->assertStatus(201);

        $this->assertDatabaseHas('users', ['email' => $data['email']]);

        // Assert the role was assigned to the user
        $user = User::where('email', $data['email'])->first();

        $this->assertCount(1, $user->roles);
        $this->assertEquals($data['roles'][0], $user->roles->pluck('key')[0]);
    }

    /** @test */
    public function roots_can_view_users()
    {
        $user = factory(User::class)->create();

        $this->actingAs($this->root)
            ->get(route('dashboard.develusers.users.edit', $user->id))
            ->assertStatus(200);
    }

    /** @test */
    public function roots_can_edit_users()
    {
        $user = factory(User::class)->create();

        $data = [
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => 'qwerty1234',
        ];

        $this->actingAs($this->root)
            ->post(route('dashboard.develusers.users.update', $user->id), $data)
            ->assertStatus(200);

        $user = $user->refresh();

        $this->assertEquals([
            'id' => $user->id,
            'name' => $data['name'],
            'email' => $data['email'],
        ], $user->toArray());

        $this->assertTrue(Hash::check($data['password'], $user->password));
    }

    /** @test */
    public function roots_can_delete_users()
    {
        $user = factory(User::class)->create();

        $this->actingAs($this->root)
            ->delete(route('dashboard.develusers.users.destroy', $user->id))
            ->assertStatus(200);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function roots_can_grant_personal_permissions_to_users()
    {
        $user = factory(User::class)->create();

        $this->assertCount(0, $user->permissions);

        $data = [
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => 'qwerty1234',
            'permissions' => ['users.add', 'users.edit'],
        ];

        $this->actingAs($this->root)
            ->post(route('dashboard.develusers.users.update', $user->id), $data)
            ->assertStatus(200);

        $user = $user->refresh();

        $this->assertCount(count($data['permissions']), $user->permissions);

        $this->assertEquals(
            $data['permissions'],
            $user->permissions->pluck('key')->toArray()
        );
    }
}
