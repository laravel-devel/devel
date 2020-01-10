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
    Route::get('/', 'DashboardController@index')->name('index');
});
