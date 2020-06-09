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

Auth::routes();

Route::get('/laravel', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::any( "/{path?}", "Legacy\LegacySiteController@index" )->where( "path", ".*" );
