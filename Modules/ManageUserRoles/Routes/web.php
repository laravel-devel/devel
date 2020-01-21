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

Route::group([
    'middleware' => [\Modules\DevelDashboard\Http\Middleware\DashboardAccess::class],
    'prefix' => config('develdashboard.dashboard_uri') . '/' . config('manageuserroles.slug'),
], function() {
    Route::get('/', [
        'as' => 'dashboard.manageuserroles.index',
        'uses' => 'RolesController@index',
        'dashboardMenu' => 'Users->' . config('manageuserroles.display_name'),
    ]);

    Route::get('/list', [
        'as' => 'dashboard.manageuserroles.get',
        'uses' => 'RolesController@get',
    ]);

    Route::get('/add', [
        'as' => 'dashboard.manageuserroles.create',
        'uses' => 'RolesController@create',
    ]);

    Route::get('/{id}/edit', [
        'as' => 'dashboard.manageuserroles.edit',
        'uses' => 'RolesController@edit',
    ]);

    Route::post('/', [
        'as' => 'dashboard.manageuserroles.store',
        'uses' => 'RolesController@store',
    ]);

    Route::post('/{id}', [
        'as' => 'dashboard.manageuserroles.update',
        'uses' => 'RolesController@update',
    ]);

    Route::delete('/{id}', [
        'as' => 'dashboard.manageuserroles.destroy',
        'uses' => 'RolesController@destroy',
    ]);
});
