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
 * Laravel Authentification
 *****/
// Auth::routes();
// Authentication Routes...
Route::get('login', function() { return redirect(env('APP_DIR', '') . 'connexion.php'); })->name('login');
Route::post('login', function() { return redirect(env('APP_DIR', '') . 'connexion.php'); });
Route::post('logout', function() { return redirect(env('APP_DIR', '') . 'connexion.php?doLogout=true'); })->name('logout');
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

/* Default */
Route::get('/laravel', function() { return view('welcome'); });
Route::get('/home', 'HomeController@index')->name('home');

/* Page */
Route::get('/page/{page}-{url}', 'PageController@index')->name('page.show');

/* Organisation */
Route::get('organisation/{id}-{slug}', 'OrganisationController@show')->name('organisation.showslug');
Route::resource('organisation', 'OrganisationController');
Route::get('organisation/{organisation}/migrate', 'OrganisationController@migrate')->name('organisation.migrate');
Route::match(['put', 'patch'], 'organisation/{organisation}/migrate', 'OrganisationController@runMigration')->name('organisation.run-migration');

/* OrganisationMember */
Route::get('organisation/{organisation_id}/join', 'OrganisationMemberController@joinOrganisation')->name('organisation-member.join');
Route::post('organisation/{organisation_id}/join', 'OrganisationMemberController@store')->name('organisation-member.store');
Route::get('organisation/{organisation_id}/invite', 'OrganisationMemberController@invite')->name('organisation-member.invite');
Route::post('organisation/{organisation_id}/invite', 'OrganisationMemberController@sendInvitation')->name('organisation-member.send-invitation');
Route::get('organisation-member/{id}/edit', 'OrganisationMemberController@edit')->name('organisation-member.edit');
Route::match(['put', 'patch'], 'organisation-member/{id}', 'OrganisationMemberController@update')->name('organisation-member.update');
Route::get('organisation-member/{id}/delete', 'OrganisationMemberController@delete')->name('organisation-member.delete');
Route::delete('organisation-member/{id}', 'OrganisationMemberController@destroy')->name('organisation-member.destroy');

/* Infrastructure */
Route::get('infrastructure/{id}', 'InfrastructureController@show')->name('infrastructure.show');
Route::get('infrastructure/select-group/type:{infrastructurable_type}/id:{infrastructurable_id}', 'InfrastructureController@selectGroup')->name('infrastructure.select-group');
Route::get('infrastructure/create/type:{infrastructurable_type}/id:{infrastructurable_id}', 'InfrastructureController@create')->name('infrastructure.create');
Route::post('infrastructure/create', 'InfrastructureController@store')->name('infrastructure.store');
Route::get('infrastructure/{infrastructure_id}/edit', 'InfrastructureController@edit')->name('infrastructure.edit');
Route::match(['put', 'patch'], 'infrastructure/{infrastructure_id}', 'InfrastructureController@update')->name('infrastructure.update');
Route::get('infrastructure/{infrastructure_id}/delete', 'InfrastructureController@delete')->name('infrastructure.delete');
Route::delete('infrastructure/{infrastructure_id}', 'InfrastructureController@destroy')->name('infrastructure.destroy');

/* InfrastructureJudge */
Route::get('economy/infrastructure-judge', 'InfrastructureJudgeController@index')->name('infrastructure-judge.index');
Route::get('economy/infrastructure-judge/{infrastructure}', 'InfrastructureJudgeController@show')->name('infrastructure-judge.show');
Route::patch('economy/infrastructure-judge/{infrastructure}', 'InfrastructureJudgeController@judge')->name('infrastructure-judge.judge');

/* Search */
Route::get('search', 'SearchController@index')->name('search');

/* Map */
Route::get('map', 'MapController@explore')->name('map');

/* Notification */
Route::get('user/notifications', 'NotificationController@index')->name('notification');
Route::post('user/notifications/mark-as-read', 'NotificationController@markAsRead')->name('notification.mark-as-read');

/* DataExporter */
Route::get('data-export/temperance-pays', 'DataExporterController@temperancePays')->name('data-export.temperance-pays');
