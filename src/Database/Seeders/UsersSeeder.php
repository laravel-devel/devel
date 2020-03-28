<?php

namespace Devel\Database\Seeders;

use Carbon\Carbon;
use Devel\Models\Auth\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userModel = config('auth.providers.users.model');

        // Create a root (superadmin) if there are no roots yet
        if (DB::table('devel_user_role')->where('role', 'root')->exists()) {
            return;
        }

        // Delete a matching user if any
        $userModel::where('email', config('devel.root.default_email'))
            ->delete();

        $root = $userModel::forceCreate([
            'name' => 'Root',
            'email' => config('devel.root.default_email'),
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make(config('devel.root.default_password')),
        ]);

        $root->roles()->attach('root');
    }
}
