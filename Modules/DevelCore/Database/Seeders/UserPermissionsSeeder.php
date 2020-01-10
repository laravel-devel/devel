<?php

namespace Modules\DevelCore\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Modules\DevelCore\Entities\Auth\Permission;

class UserPermissionsSeeder extends Seeder
{
    protected $permissions = [
        'admin_dashboard.access' => 'Access Admin Dashboard',

        // TODO: Debug
        'admin_dashboard.access1' => 'Access Admin Dashboard1',
        'admin_dashboard.access2' => 'Access Admin Dashboard2',
        'admin_dashboard.access3' => 'Access Admin Dashboard3',
        'admin_dashboard.access4' => 'Access Admin Dashboard4',
        'admin_dashboard.access5' => 'Access Admin Dashboard5',
        'admin_dashboard.access6' => 'Access Admin Dashboard6',
        'admin_dashboard.access7' => 'Access Admin Dashboard7',
        'admin_dashboard.access8' => 'Access Admin Dashboard8',
        'admin_dashboard.access9' => 'Access Admin Dashboard9',
        'admin_dashboard.access10' => 'Access Admin Dashboard10',
        'admin_dashboard.access11' => 'Access Admin Dashboard11',
        'admin_dashboard.access12' => 'Access Admin Dashboard12',
        'admin_dashboard.access13' => 'Access Admin Dashboard13',
        'admin_dashboard.access14' => 'Access Admin Dashboard14',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->permissions as $permission => $name) {
            Permission::create([
                'key' => $permission,
                'name' => $name,
            ]);
        }
    }
}
