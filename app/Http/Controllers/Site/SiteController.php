<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use Modules\DevelCore\Http\Controllers\Controller;

class SiteController extends Controller
{
    /**
     * Admin dashboard index
     *
     * @return void
     */
    public function index()
    {
        $this->setMeta([
            'title' => 'Homepage',
            'description' => 'Welcome to ' . config('app.name') . '!',
            'keywords' => 'modular, laravel, site',
        ]);
        
        return view('site.home');
    }
}
