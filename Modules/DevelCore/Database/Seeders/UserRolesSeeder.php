<?php

namespace Modules\DevelCore\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Modules\DevelCore\Entities\Auth\Role;

class UserRolesSeeder extends Seeder
{
    protected $roles = [
        'user' => [
            'name' => 'User',
            'default' => true,
        ],
        'admin' => [
            'name' => 'Admin',
            'permissions' => [
                'admin_dashboard.access',
                'site.edit_settings',
                'site.manage_modules',
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
            }
        }
    }
}
