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

// Site routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Dashboard auth routes
Route::group([
    'namespace' => 'Dashboard\Auth',
    'prefix' => 'dashboard',
    'as' => 'dashboard.auth.',
], function () {
    Route::get('/login', 'LoginController@showLoginForm')->name('login');
    Route::post('/login', 'LoginController@login')->name('login.post');
    Route::post('/logout', 'LoginController@logout')->name('logout');
});

// Dashboard routes
Route::group([
    'namespace' => 'Dashboard',
    'prefix' => 'dashboard',
    'middleware' => ['dashboard.access'],
    'as' => 'dashboard.',
], function () {
    Route::get('/', 'DashboardController@index')->name('index');
});
