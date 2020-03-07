<?php

namespace Devel\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Devel\Core\Entities\Auth\Permission;

class UserPermissionsSeeder extends Seeder
{
    protected $permissions = [
        'admin_dashboard.access' => 'Access Admin Dashboard',
        'site.edit_settings' => 'Edit Site Settings',
        'site.manage_modules' => 'Manage Site Modules',
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
