<?php

namespace Modules\DevelCore\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Modules\DevelCore\Entities\Auth\User;

class UsersSeeder extends Seeder
{
    protected $users = [
        'user@example.com' => ['user'], // TODO: Remove after debugging
        'admin@example.com' => ['admin'],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->users as $email => $roles) {
            $user = factory(User::class)->create([
                'email' => $email,
            ]);

            $user->roles()->attach($roles);
        }
    }
}
