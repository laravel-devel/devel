<?php

namespace Devel\Database\Seeders;

use Devel\Models\Settings;

class SettingsSeeder extends Seeder
{
    protected $settings = [
        'site-name' => [
            'name' => 'Site Name',
            'value' => 'Devel',
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Read site name from the defaults
        $settings['site-name']['value'] = config('app.name');

        foreach ($this->settings as $key => $data) {
            $parts = explode('-', $key);
            $group = $parts[0];

            array_shift($parts);
            $key = implode('.', $parts);

            if (!Settings::where('group', $group)->where('key', $key)->exists()) {
                Settings::create([
                    'group' => $group,
                    'key' => $key,
                    'name' => $data['name'],
                    'value' => $data['value'],
                ]);
            }
        }
    }
}
