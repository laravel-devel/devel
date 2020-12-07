<?php

namespace Devel\Database\Seeders;

use Devel\Models\Auth\Permission;
use Devel\Models\Auth\Role;

class UserPermissionsSeeder extends Seeder
{
    protected $permissions = [
        'site.edit_settings' => 'Edit Site Settings',
        'site.manage_modules' => 'Manage Site Modules',

        'users.browse' => 'Browse Users',
        'users.view' => 'View Users',
        'users.add' => 'Add Users',
        'users.edit' => 'Edit Users',
        'users.assign_roles' => 'Assign Roles',
        'users.grant_personal_permissions' => 'Grant Personal Permissions',
        'users.delete' => 'Delete Users',

        'user_roles.browse' => 'Browse Roles',
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
        $rootRole = Role::where('key', 'root')->first();

        foreach ($this->permissions as $permission => $name) {
            $permission = Permission::updateOrCreate([
                'key' => $permission,
            ], [
                'name' => $name,
            ]);

            if ($rootRole && !$rootRole->permissions->contains($permission)) {
                $rootRole->permissions()->attach($permission);
            }
        } 
    }
}
