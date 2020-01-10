<?php

namespace Modules\DevelCore\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DevelCoreDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(UserPermissionsSeeder::class);
        $this->call(UserRolesSeeder::class);
        $this->call(UsersSeeder::class);
    }
}
