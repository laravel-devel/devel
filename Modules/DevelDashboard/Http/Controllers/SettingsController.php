<?php

namespace Modules\DevelDashboard\Http\Controllers;

use Illuminate\Http\Request;
use Modules\DevelCore\Entities\Settings;
use Modules\DevelCore\Http\Controllers\Controller;

class SettingsController extends Controller
{
    protected $groups = [
        'site' => [
            'name',
        ],
    ];

    protected $form = [];

    protected $values = [];

    public function __construct()
    {
        $this->setMeta('title', 'Dashboard');
        $this->setMeta('title', 'Settings');

        // Input fields for the form
        foreach ($this->groups as $group => $keys) {
            $groupName = ucwords(str_replace('_', ' ', $group));
            $form[$groupName] = [];

            foreach ($keys as $key) {
                $key = "{$group}-{$key}";
                $setting = Settings::getObject($key);

                // A form field
                $this->form[$groupName][] = [
                    'type' => 'text',
                    'name' => "$key",
                    'label' => isset($setting)
                        ? $setting->name
                        : Settings::keyToName("$key"),
                ];

                // A value for the field
                $this->values[$key] = Settings::read($key);
            }
        }
    }

    /**
     * Show the settings form
     *
     * @return void
     */
    public function edit()
    {
        return view('develdashboard::settings.edit', [
            'form' => $this->form,
            'values' => $this->values,
        ]);
    }

    /**
     * Update the site settings
     *
     * @return void
     */
    public function update(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 1) === '_') {
                continue;
            }

            Settings::set($key, $value);
        }

        return response()->json([
            'notification' => [
                'message' => 'Settings were updated!',
                'type' => 'success',
            ],
        ]);
    }
}
