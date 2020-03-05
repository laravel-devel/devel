<?php

namespace Modules\Site\Http\Controllers;

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
     * Show the site settings form
     *
     * @return void
     */
    public function edit()
    {
        return view('site::dashboard.settings.edit', [
            'form' => $this->form,
            'values' => $this->values,
        ]);
    }
}
