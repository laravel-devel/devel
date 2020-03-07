<?php

namespace Modules\DevelUsers\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\DevelCore\Entities\Auth\Role;
use Modules\DevelCore\Entities\Auth\Permission;

class UsersSeeder extends Seeder
{
    protected $permissions = [
        'users.list' => 'List Users',
        'users.view' => 'View Users',
        'users.add' => 'Add Users',
        'users.edit' => 'Edit Users',
        'users.assign_roles' => 'Assign Roles',
        'users.grant_personal_permissions' => 'Grant Personal Permissions',
        'users.delete' => 'Delete Users',
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
