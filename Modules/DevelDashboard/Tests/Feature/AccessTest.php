<?php

namespace Modules\DevelDashboard\Tests\Feature;

use Tests\TestCase;
use Modules\DevelCore\Entities\Auth\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\DevelCore\Database\Seeders\DevelCoreDatabaseSeeder;

class AccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DevelCoreDatabaseSeeder::class);

        $this->admin = factory(User::class)->create();
        $this->user = factory(User::class)->create();

        $this->admin->roles()->attach('admin');
        $this->user->roles()->attach('user');
    }

    /** @test */
    public function not_everyone_allowed_to_access_admin_dashboard()
    {
        $this->actingAs($this->admin)
            ->get(route('dashboard.index'))
            ->assertStatus(200);

        $this->actingAs($this->user)
            ->get(route('dashboard.index'))
            ->assertStatus(302)
            ->assertRedirect('/');
    }
}