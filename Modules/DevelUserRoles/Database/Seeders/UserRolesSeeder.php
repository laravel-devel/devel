<?php

namespace Modules\DevelUserRoles\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\DevelCore\Entities\Auth\Role;
use Modules\DevelCore\Entities\Auth\Permission;

class UserRolesSeeder extends Seeder
{
    protected $permissions = [
        'user_roles.list' => 'List Roles',
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
        $root = Role::find('root');

        foreach ($this->permissions as $permission => $name) {
            $permission = Permission::firstOrCreate([
                'key' => $permission,
                'name' => $name,
            ]);

            if ($root && !$root->permissions->contains($permission)) {
                $root->permissions()->attach($permission);
            }
        }
    }
}
