<?php

namespace Modules\DevelCore\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\DevelCore\Entities\Settings;

class SettingsSeeder extends Seeder
{
    protected $settings = [
        'site.name' => 'Devel',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->settings as $key => $value) {
            Settings::set($key, $value);
        }
    }
}
