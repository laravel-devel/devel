<?php

Route::group([
    'prefix' => config('develdashboard.dashboard_uri') . '/' . config('develuserroles.slug'),
], function() {
    Route::get('/', [
        'as' => 'develuserroles.index',
        'uses' => 'RolesController@index',
        'dashboardMenu' => 'Users->' . config('develuserroles.display_name'),
        'permissions' => 'devel_user_roles.list',
    ]);

    Route::get('/list', [
        'as' => 'develuserroles.get',
        'uses' => 'RolesController@get',
        'permissions' => 'devel_user_roles.list',
    ]);

    Route::get('/add', [
        'as' => 'develuserroles.create',
        'uses' => 'RolesController@create',
        'permissions' => 'devel_user_roles.add',
    ]);

    Route::get('/{id}/edit', [
        'as' => 'develuserroles.edit',
        'uses' => 'RolesController@edit',
        'permissions' => 'devel_user_roles.edit',
    ]);

    Route::post('/', [
        'as' => 'develuserroles.store',
        'uses' => 'RolesController@store',
        'permissions' => 'devel_user_roles.add',
    ]);

    Route::post('/{id}', [
        'as' => 'develuserroles.update',
        'uses' => 'RolesController@update',
        'permissions' => 'devel_user_roles.edit',
    ]);

    Route::delete('/{id}', [
        'as' => 'develuserroles.destroy',
        'uses' => 'RolesController@destroy',
        'permissions' => 'devel_user_roles.delete',
    ]);
});
