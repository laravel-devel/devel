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
    'prefix' => config('develdashboard.dashboard_uri') . '/' . config('develuserroles.slug'),
], function() {
    Route::get('/', [
        'as' => 'dashboard.develuserroles.index',
        'uses' => 'RolesController@index',
        'dashboardMenu' => 'Users->' . config('develuserroles.display_name'),
    ]);

    Route::get('/list', [
        'as' => 'dashboard.develuserroles.get',
        'uses' => 'RolesController@get',
    ]);

    Route::get('/add', [
        'as' => 'dashboard.develuserroles.create',
        'uses' => 'RolesController@create',
    ]);

    Route::get('/{id}/edit', [
        'as' => 'dashboard.develuserroles.edit',
        'uses' => 'RolesController@edit',
    ]);

    Route::post('/', [
        'as' => 'dashboard.develuserroles.store',
        'uses' => 'RolesController@store',
    ]);

    Route::post('/{id}', [
        'as' => 'dashboard.develuserroles.update',
        'uses' => 'RolesController@update',
    ]);

    Route::delete('/{id}', [
        'as' => 'dashboard.develuserroles.destroy',
        'uses' => 'RolesController@destroy',
    ]);
});
