<?php

namespace Devel\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Devel\Core\Entities\Auth\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a root (superadmin)
        $root = User::forceCreate([
            'name' => 'Root',
            'email' => config('devel.root.default_email'),
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make(config('devel.root.default_password')),
        ]);

        $root->roles()->attach('root');
    }
}
