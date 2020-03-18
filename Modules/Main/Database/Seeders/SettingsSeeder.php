<?php

namespace Modules\Main\Database\Seeders;

use Devel\Core\Entities\Auth\Role;
use Devel\Core\Database\Seeders\Seeder;
use Devel\Core\Entities\Auth\Permission;
use Devel\Core\Entities\Settings;

class SettingsSeeder extends Seeder
{
    protected $permissions = [
        // 'main.edit_settings' => 'Edit Module Settings',
    ];

    protected $settings = [
        // 'site-name' => [
        //     'name' => 'Site Name',
        //     'value' => 'Devel',
        // ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed the permission to edit the module's settings
        $root = Role::find('root');

        foreach ($this->permissions as $permission => $name) {
            $permission = Permission::firstOrCreate([
                'key' => $permission,
                'name' => $name,
                'module' => 'Main',
            ]);

            if ($root && !$root->permissions->contains($permission)) {
                $root->permissions()->attach($permission);
            }
        }

        // Seed the module's settings
        foreach ($this->settings as $key => $data) {
            $parts = explode('-', $key);
            $group = $parts[0];

            array_shift($parts);
            $key = implode('.', $parts);

            Settings::create([
                'group' => $group,
                'key' => $key,
                'name' => $data['name'],
                'value' => $data['value'],
            ]);
        }
    }

    /**
     * Revert the database seeds.
     *
     * @return void
     */
    public function revert()
    {
        foreach ($this->permissions as $permission => $name) {
            Permission::where('module', 'DevelUserRoles')
                ->where('key', $permission)
                ->forceDelete();
        }
    }
}
