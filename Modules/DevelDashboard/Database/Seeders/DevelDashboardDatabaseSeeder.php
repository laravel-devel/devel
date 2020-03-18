<?php

namespace Modules\DevelDashboard\Database\Seeders;

use Devel\Core\Database\Seeders\Seeder;
use Illuminate\Database\Eloquent\Model;

class DevelDashboardDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(PermissionsSeeder::class);
    }

    /**
     * Revert the database seeds.
     *
     * @return void
     */
    public function revert()
    {
        $this->uncall(PermissionsSeeder::class);
    }
}
