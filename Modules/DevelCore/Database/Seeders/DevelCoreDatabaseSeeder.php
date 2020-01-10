<?php

namespace Modules\DevelCore\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Modules\DevelCore\Entities\Auth\Permission;
use Modules\DevelCore\Entities\Auth\Role;
use Modules\DevelCore\Entities\Auth\User;

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
        Schema::disableForeignKeyConstraints();

        User::truncate();
        Permission::truncate();
        Role::truncate();

        Schema::enableForeignKeyConstraints();

        // Then seed the tables
        $this->call(UserPermissionsSeeder::class);
        $this->call(UserRolesSeeder::class);
        $this->call(UsersSeeder::class);
    }
}
