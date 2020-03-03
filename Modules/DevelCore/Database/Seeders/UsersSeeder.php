<?php

namespace Modules\DevelCore\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Modules\DevelCore\Entities\Auth\User;

class UsersSeeder extends Seeder
{
    protected $users = [
        //
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a root (superadmin)
        $root = factory(User::class)->create([
            'email' => config('devel.root.default_email'),
            'password' => Hash::make(config('devel.root.default_password')),
        ]);

        $root->roles()->attach('root');

        // Create other default users
        foreach ($this->users as $email => $roles) {
            $user = factory(User::class)->create([
                'email' => $email,
            ]);

            $user->roles()->attach($roles);
        }
    }
}
