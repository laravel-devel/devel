<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\DevelCore\Http\Controllers\Controller;

class HomepageController extends Controller
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
            'description' => 'Welcome to ' . setting('site-name') . '!',
            'keywords' => 'devel, modular, laravel, site',
        ]);

        return view('site.home');
    }
}
