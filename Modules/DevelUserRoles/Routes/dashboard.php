<?php

Route::group([
    'prefix' => config('develdashboard.dashboard_uri'),
], function() {
    /**
     * Modules\DevelCore\Entities\Auth\Role CRUD
     */
    Route::group([
        'prefix' => config('develuserroles.slug'),
    ], function () {
        Route::get('/', [
            'as' => 'develuserroles.index',
            'uses' => 'RolesController@index',
            'dashboardMenu' => 'Manage Users->' . config('develuserroles.display_name'),
            'permissions' => 'user_roles.list',
        ]);

        Route::get('/list', [
            'as' => 'develuserroles.get',
            'uses' => 'RolesController@get',
            'permissions' => 'user_roles.list',
        ]);

        Route::get('/add', [
            'as' => 'develuserroles.create',
            'uses' => 'RolesController@create',
            'permissions' => 'user_roles.add',
        ]);

        Route::get('/{id}/edit', [
            'as' => 'develuserroles.edit',
            'uses' => 'RolesController@edit',
            'permissions' => 'user_roles.edit',
        ]);

        Route::post('/', [
            'as' => 'develuserroles.store',
            'uses' => 'RolesController@store',
            'permissions' => 'user_roles.add',
        ]);

        Route::post('/{id}', [
            'as' => 'develuserroles.update',
            'uses' => 'RolesController@update',
            'permissions' => 'user_roles.edit',
        ]);

        Route::delete('/{id}', [
            'as' => 'develuserroles.destroy',
            'uses' => 'RolesController@destroy',
            'permissions' => 'user_roles.delete',
        ]);
    });
});
