<?php

namespace Modules\DevelUserRoles\Database\Seeders;

use Devel\Core\Database\Seeders\Seeder;
use Illuminate\Database\Eloquent\Model;

class DevelUserRolesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(UserRolesSeeder::class);
    }
}
