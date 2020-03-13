<?php

namespace Modules\DevelUsers\Database\Seeders;

use Devel\Core\Database\Seeders\Seeder;
use Illuminate\Database\Eloquent\Model;

class DevelUsersDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");
        $this->call(UsersSeeder::class);
    }
}
