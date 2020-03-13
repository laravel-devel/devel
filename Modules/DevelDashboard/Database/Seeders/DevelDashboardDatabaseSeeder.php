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
     * Revert the changes made by the seeder.
     *
     * @return void
     */
    public function revert(): void
    {
        $this->uncall(PermissionsSeeder::class);
    }
}
