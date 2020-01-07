<?php

use App\Models\Auth\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class UsersSeeder extends Seeder
{
    protected $users = [
        'user@example.com' => ['user'],
        'admin@example.com' => ['admin'],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();

        foreach ($this->users as $email => $roles) {
            $user = factory(User::class)->create([
                'email' => $email,
            ]);
            
            $user->roles()->attach($roles);
        }

        Schema::enableForeignKeyConstraints();
    }
}
