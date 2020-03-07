<?php

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
|
| Here is where you can register the public (site) routes for your module.
|
*/

Route::get('/', 'HomepageController@index')->name('home');
