<?php

namespace App\Http\Controllers\Dashboard;

use Modules\DevelCore\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        
        return view('dashboard.index');
    }
}
