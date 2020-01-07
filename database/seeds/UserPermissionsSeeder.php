<?php

use App\Models\Auth\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class UserPermissionsSeeder extends Seeder
{
    protected $permissions = [
        'admin_dashboard.access' => 'Access Admin Dashboard',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Permission::truncate();

        foreach ($this->permissions as $permission => $name) {
            Permission::create([
                'key' => $permission,
                'name' => $name,
            ]);
        }

        Schema::enableForeignKeyConstraints();
    }
}
