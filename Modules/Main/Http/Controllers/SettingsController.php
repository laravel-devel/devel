<?php

namespace Modules\Main\Http\Controllers;

use Illuminate\Http\Request;
use Modules\DevelDashboard\Http\Controllers\SettingsController as Controller;

class SettingsController extends Controller
{
    protected $groups = [
        // 'site' => [
        //     'name',
        // ],
    ];

    /**
     * Show the settings form
     *
     * @return void
     */
    public function edit()
    {
        return view('main::dashboard.settings.edit', [
            'form' => $this->form,
            'values' => $this->values,
        ]);
    }
}