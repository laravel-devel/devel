<?php

namespace Modules\Main\Http\Controllers;

use Devel\Core\Http\Controllers\Controller;

class HomepageController extends Controller
{
    /**
     * The site's homepage
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

        return view('main::public.home');
    }
}
