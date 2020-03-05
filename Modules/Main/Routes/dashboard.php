<?php

Route::group([
    'prefix' => config('develdashboard.dashboard_uri') . '/' . config('main.slug'),
], function() {
    /**
     * Module settings
     */
    Route::get('/settings', [
        'as' => 'settings.edit',
        'uses' => 'SettingsController@edit',
        'dashboardMenu' => 'Main->Settings',
        'permissions' => 'main.edit_settings',
    ]);

    Route::post('/settings', [
        'as' => 'settings.update',
        'uses' => 'SettingsController@update',
        'permissions' => 'main.edit_settings',
    ]);
});
