<?php

namespace Modules\Main\Database\Seeders;

use Devel\Core\Database\Seeders\Seeder;
use Illuminate\Database\Eloquent\Model;

class MainDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(SettingsSeeder::class);
    }

    /**
     * Revert the changes made by the seeder.
     *
     * @return void
     */
    public function revert()
    {
        $this->uncall(SettingsSeeder::class);
    }
}
