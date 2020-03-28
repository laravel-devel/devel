<?php

namespace Devel\Database\Seeders;

use Illuminate\Support\Facades\Schema;
use Devel\Models\Auth\Permission;
use Devel\Models\Auth\Role;
use Devel\Models\Auth\User;
use Devel\Models\Settings;

class DevelDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate all the tables first
        Permission::query()->delete();
        Role::query()->delete();
        Settings::query()->delete();

        Schema::disableForeignKeyConstraints();

        Permission::truncate();
        Role::truncate();
        Settings::truncate();

        Schema::enableForeignKeyConstraints();

        // Then seed the tables
        $this->call(UserPermissionsSeeder::class);
        $this->call(UserRolesSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(SettingsSeeder::class);
    }
}
