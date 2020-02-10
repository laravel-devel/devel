<?php

namespace Modules\DevelUserRoles\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\DevelCore\Entities\Auth\Role;
use Modules\DevelCore\Entities\Auth\Permission;

class UserRolesSeeder extends Seeder
{
    protected $permissions = [
        'user_roles.list-roles' => 'List Roles',
        'user_roles.add-roles' => 'Add Roles',
        'user_roles.edit-roles' => 'Edit Roles',
        'user_roles.delete-roles' => 'Delete Roles',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::find('admin');

        foreach ($this->permissions as $permission => $name) {
            $permission = Permission::firstOrCreate([
                'key' => $permission,
                'name' => $name,
            ]);

            if ($admin) {
                $admin->permissions()->attach($permission);
            }
        }
    }
}
