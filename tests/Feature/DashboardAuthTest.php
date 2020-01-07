<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Auth\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardAuthTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = factory(User::class)->create();
    }

    /** @test */
    public function users_can_log_in()
    {
        $this->assertGuest();

        $this->postJson(route('dashboard.auth.login.post', [
            'email' => $this->admin->email,
            'password' => 'qwerty',
        ]))->assertStatus(200);

        $this->assertAuthenticated();
    }

    /** @test */
    public function users_can_log_out()
    {
        $this->actingAs($this->admin);

        $this->postJson(route('dashboard.auth.logout'))->assertStatus(302);

        $this->assertGuest();
    }
}
