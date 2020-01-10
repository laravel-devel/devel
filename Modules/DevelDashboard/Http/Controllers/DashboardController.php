<?php

namespace Modules\DevelDashboard\Http\Controllers;

use Modules\DevelCore\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Admin dashboard index
     *
     * @return void
     */
    public function index()
    {
        $this->setMeta('title', 'Dashboard');

        return view('develdashboard::index');
    }
}
