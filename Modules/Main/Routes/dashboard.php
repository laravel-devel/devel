<?php

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register the (admin) dashboard routes for your module.
|
*/

Route::group([
    'prefix' => config('main.slug'),
], function() {
    /**
     * Module settings
     */
    // Route::get('/settings', [
    //     'as' => 'main.settings.edit',
    //     'uses' => 'SettingsController@edit',
    //     'dashboardMenu' => 'Main->Settings',
    //     'permissions' => 'main.edit_settings',
    // ]);

    // Route::post('/settings', [
    //     'as' => 'main.settings.update',
    //     'uses' => 'SettingsController@update',
    //     'permissions' => 'main.edit_settings',
    // ]);
});
