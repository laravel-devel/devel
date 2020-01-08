<?php

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes
|--------------------------------------------------------------------------
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
    'middleware' => ['dashboard.access'],
], function () {
    Route::get('/', 'DashboardController@index')->name('index');
});
