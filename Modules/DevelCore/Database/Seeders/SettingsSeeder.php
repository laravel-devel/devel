<?php

namespace Modules\DevelCore\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\DevelCore\Entities\Settings;

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

            Settings::create([
                'group' => $group,
                'key' => $key,
                'name' => $data['name'],
                'value' => $data['value'],
            ]);
        }
    }
}
