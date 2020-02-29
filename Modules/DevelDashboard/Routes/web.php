<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Dashboard auth routes
Route::group([
    'namespace' => 'Auth',
    'as' => 'auth.',
], function () {
    Route::get('/login', 'LoginController@showLoginForm')->name('login');
    Route::post('/login', 'LoginController@login')->name('login.post');
    Route::post('/logout', 'LoginController@logout')->name('logout');

    Route::group(['middleware' => 'guest'], function () {
        Route::get('/forgot-password', 'ForgotPasswordController@showLinkRequestForm')->name('forgot-password');
        Route::post('/forgot-password', 'ForgotPasswordController@sendResetLinkEmail')->name('forgot-password.post');
        Route::get('/reset-password/{token}', 'ResetPasswordController@showResetForm')->name('reset-password');
        Route::post('/reset-password', 'ResetPasswordController@reset')->name('reset-password.post');
    });
});

// Dashboard routes
Route::group([
    'middleware' => [\Modules\DevelDashboard\Http\Middleware\DashboardAccess::class],
], function () {
    // Dashboard homepage
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
});
