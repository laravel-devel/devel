<?php

Route::group([
    'prefix' => config('develdashboard.dashboard_uri') . '/' . config('main.slug'),
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
