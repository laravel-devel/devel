<?php

namespace Devel\Database\Seeders;

use Devel\Models\Auth\Permission;
use Devel\Models\Auth\Role;

class UserRolesSeeder extends Seeder
{
    protected $roles = [
        'user' => [
            'name' => 'User',
            'default' => true,
        ],
        'root' => [
            'name' => 'Root',
        ],
        'admin' => [
            'name' => 'Admin',
            'permissions' => [
                'site.edit_settings',
            ],
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->roles as $key => $name) {
            if (is_string($name)) {
                Role::create([
                    'key' => $key,
                    'name' => $name,
                ]);
            } else {
                $role = Role::create([
                    'key' => $key,
                    'name' => $name['name'],
                    'default' => (isset($name['default']) && $name['default']),
                ]);

                if (isset($name['permissions'])) {
                    foreach ($name['permissions'] as $permission) {
                        $role->permissions()->attach($permission);
                    }
                }

                // The root role has ALL the possible permissions
                if ($key === 'root') {
                    $role->permissions()->attach(Permission::all());
                }
            }
        }
    }
}
