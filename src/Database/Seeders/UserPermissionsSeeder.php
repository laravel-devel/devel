<?php

namespace Devel\Database\Seeders;

use Devel\Models\Auth\Permission;

class UserPermissionsSeeder extends Seeder
{
    protected $permissions = [
        'site.edit_settings' => 'Edit Site Settings',
        'site.manage_modules' => 'Manage Site Modules',

        'users.list' => 'List Users',
        'users.view' => 'View Users',
        'users.add' => 'Add Users',
        'users.edit' => 'Edit Users',
        'users.assign_roles' => 'Assign Roles',
        'users.grant_personal_permissions' => 'Grant Personal Permissions',
        'users.delete' => 'Delete Users',

        'user_roles.list' => 'List Roles',
        'user_roles.view' => 'View Roles',
        'user_roles.add' => 'Add Roles',
        'user_roles.edit' => 'Edit Roles',
        'user_roles.delete' => 'Delete Roles',
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
