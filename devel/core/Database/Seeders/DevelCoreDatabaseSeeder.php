<?php

namespace Devel\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Devel\Core\Entities\Auth\Permission;
use Devel\Core\Entities\Auth\Role;
use Devel\Core\Entities\Auth\User;
use Devel\Core\Entities\Settings;

class DevelCoreDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate all the tables first
        User::query()->delete();
        Permission::query()->delete();
        Role::query()->delete();
        Settings::query()->delete();

        Schema::disableForeignKeyConstraints();

        User::truncate();
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
