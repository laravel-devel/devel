<?php

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register the (admin) dashboard routes for your module.
|
*/

/**
 * Dashboard homepage
 */
Route::get('/', 'DashboardController@index')->name('index');

/**
 * General site settings
 */
Route::get('/settings', [
    'as' => 'settings.edit',
    'uses' => 'SettingsController@edit',
    'dashboardMenu' => 'Site->Settings',
    'permissions' => 'site.edit_settings',
]);

Route::post('/settings', [
    'as' => 'settings.update',
    'uses' => 'SettingsController@update',
    'permissions' => 'site.edit_settings',
]);

/**
 * Module Management
 */
Route::get('/modules', [
    'as' => 'modules.index',
    'uses' => 'ModulesController@index',
    'dashboardMenu' => 'Site->Modules',
    'permissions' => 'site.manage_modules',
]);

Route::post('/modules/{alias}', [
    'as' => 'modules.toggle-enabled',
    'uses' => 'ModulesController@toggleEnabled',
    'permissions' => 'site.manage_modules',
]);