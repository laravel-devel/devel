<?php

Route::group([
    'prefix' => config('develdashboard.dashboard_uri') . '/' . config('site.slug'),
], function() {
    /**
     * Module settings
     */
    Route::get('/settings', [
        'as' => 'site.settings.edit',
        'uses' => 'SettingsController@edit',
        'dashboardMenu' => 'Site->Settings',
        'permissions' => 'site.edit-settings',
    ]);

    Route::post('/settings', [
        'as' => 'site.settings.update',
        'uses' => 'SettingsController@update',
        'permissions' => 'site.edit-settings',
    ]);
});
