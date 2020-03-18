<?php

namespace Modules\DevelDashboard\Database\Seeders;

use Devel\Core\Database\Seeders\Seeder;
use Illuminate\Database\Eloquent\Model;
use Devel\Core\Entities\Auth\Permission;
use Devel\Core\Entities\Auth\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $permission = Permission::create([
            'module' => 'DevelDashboard',
            'key' => 'admin_dashboard.access',
            'name' => 'Access Admin Dashboard',
        ]);

        Role::find('root')->permissions()->attach($permission);
        Role::find('admin')->permissions()->attach($permission);
    }

    /**
     * Revert the database seeds.
     *
     * @return void
     */
    public function revert()
    {
        Permission::where('module', 'DevelDashboard')->forceDelete();
    }
}
