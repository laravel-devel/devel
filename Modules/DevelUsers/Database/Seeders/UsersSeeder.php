<?php

namespace Modules\DevelUsers\Database\Seeders;

use Devel\Core\Entities\Auth\Role;
use Devel\Core\Database\Seeders\Seeder;
use Devel\Core\Entities\Auth\Permission;

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
                'module' => 'DevelUsers',
            ]);

            if ($root && !$root->permissions->contains($permission)) {
                $root->permissions()->attach($permission);
            }
        }
    }

    /**
     * Revert the changes made by the seeder.
     *
     * @return void
     */
    public function revert()
    {
        foreach ($this->permissions as $permission => $name) {
            Permission::where('module', 'DevelUsers')
                ->where('key', $permission)
                ->forceDelete();
        }
    }
}
