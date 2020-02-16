<?php

namespace Modules\DevelUsers\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\DevelCore\Entities\Auth\Role;
use Modules\DevelCore\Entities\Auth\Permission;

class UsersSeeder extends Seeder
{
    protected $permissions = [
        'users.list' => 'List Users',
        'users.add' => 'Add Users',
        'users.edit' => 'Edit Users',
        'users.delete' => 'Delete Users',
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
