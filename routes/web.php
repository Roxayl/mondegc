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

/*****
 * Authentification
 *****/
// Auth::routes();
// Authentication Routes...
Route::get('login', fn() => redirect('connexion.php'))->name('login');
Route::post('login', fn() => redirect('connexion.php'));
Route::post('logout', fn() => redirect('connexion.php?doLogout=true'))->name('logout');
// Registration Routes...
Route::get('register', 'Legacy\LegacySiteController@authFallback')->name('register');
Route::post('register', 'Legacy\LegacySiteController@authFallback');
// Password Reset Routes...
Route::get('password/reset', 'Legacy\LegacySiteController@authFallback');
Route::post('password/email', 'Legacy\LegacySiteController@authFallback');
Route::get('password/reset/{token}', 'Legacy\LegacySiteController@authFallback');
Route::post('password/reset', 'Legacy\LegacySiteController@authFallback');

/*****
 * Laravel
 *****/
Route::get('/laravel', fn() => view('welcome'));

Route::get('/home', 'HomeController@index')->name('home');

/*****
 * Legacy
 *****/
Route::any("/{path?}", "Legacy\LegacySiteController@index")->where( "path", ".*" );
