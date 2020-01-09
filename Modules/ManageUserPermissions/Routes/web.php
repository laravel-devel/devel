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

Route::prefix(config('app.dashboard_uri') . '/' . config('manageuserpermissions.slug'))->group(function() {
    Route::get('/', [
        'as' => 'dashboard.manageuserpermissions.index',
        'uses' => 'PermissionsController@index',
        'dashboardMenu' => 'Modules->' . config('manageuserpermissions.display_name'),
    ]);

    Route::get('/list', [
        'as' => 'dashboard.manageuserpermissions.get',
        'uses' => 'PermissionsController@get',
    ]);
});
