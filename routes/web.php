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
Route::get('layouttester', 'LayoutTesterController@index');

/* Page */
Route::get('/page/{page}-{url}', 'PageController@index')->name('page.show');

/* Organisation */
Route::get('organisation/{id}-{slug}', 'OrganisationController@show')->name('organisation.showslug');
Route::resource('organisation', 'OrganisationController');

/* OrganisationMember */
Route::get('organisation/{organisation_id}/join', 'OrganisationMemberController@joinOrganisation')->name('organisation-member.join');
Route::post('organisation/{organisation_id}/join', 'OrganisationMemberController@store')->name('organisation-member.store');
Route::get('organisation/{organisation_id}/invite', 'OrganisationMemberController@invite')->name('organisation-member.invite');
Route::post('organisation/{organisation_id}/invite', 'OrganisationMemberController@sendInvitation')->name('organisation-member.send-invitation');
Route::get('organisation-member/{id}/edit', 'OrganisationMemberController@edit')->name('organisation-member.edit');
Route::match(['put', 'patch'], 'organisation-member/{id}', 'OrganisationMemberController@update')->name('organisation-member.update');
Route::get('organisation-member/{id}/delete', 'OrganisationMemberController@delete')->name('organisation-member.delete');
Route::delete('organisation-member/{id}', 'OrganisationMemberController@destroy')->name('organisation-member.destroy');

/* Search */
Route::get('search', 'SearchController@index')->name('search');

/* Map */
Route::get('map-explore', 'MapController@explore')->name('map.explore');

/* Notification */
Route::get('user/notifications', 'NotificationController@index')->name('notification');
Route::post('user/notifications/mark-as-read', 'NotificationController@markAsRead')->name('notification.mark-as-read');

/* DataExporter */
Route::get('data-export/temperance-pays', 'DataExporterController@temperancePays')->name('data-export.temperance-pays');
Route::get('data-export/temperance-pays-all', 'DataExporterController@temperancePaysAll')->name('data-export.temperance-pays-all');
Route::get('data-export/temperance-organisation', 'DataExporterController@temperanceOrganisation')->name('data-export.temperance-organisation');


/*****
 * Craftable
 *****/

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::get('/admin/admin-users',                            'Admin\AdminUsersController@index');
    Route::get('/admin/admin-users/create',                     'Admin\AdminUsersController@create');
    Route::post('/admin/admin-users',                           'Admin\AdminUsersController@store');
    Route::get('/admin/admin-users/{adminUser}/edit',           'Admin\AdminUsersController@edit')->name('admin/admin-users/edit');
    Route::post('/admin/admin-users/{adminUser}',               'Admin\AdminUsersController@update')->name('admin/admin-users/update');
    Route::delete('/admin/admin-users/{adminUser}',             'Admin\AdminUsersController@destroy')->name('admin/admin-users/destroy');
    Route::get('/admin/admin-users/{adminUser}/resend-activation','Admin\AdminUsersController@resendActivationEmail')->name('admin/admin-users/resendActivationEmail');
});

/* Auto-generated profile routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::get('/admin/profile',                                'Admin\ProfileController@editProfile');
    Route::post('/admin/profile',                               'Admin\ProfileController@updateProfile');
    Route::get('/admin/password',                               'Admin\ProfileController@editPassword');
    Route::post('/admin/password',                              'Admin\ProfileController@updatePassword');
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::get('/admin/pages',                                  'Admin\PagesController@index');
    Route::get('/admin/pages/create',                           'Admin\PagesController@create');
    Route::post('/admin/pages',                                 'Admin\PagesController@store');
    Route::get('/admin/pages/{page}/edit',                      'Admin\PagesController@edit')->name('admin/pages/edit');
    Route::post('/admin/pages/bulk-destroy',                    'Admin\PagesController@bulkDestroy')->name('admin/pages/bulk-destroy');
    Route::post('/admin/pages/{page}',                          'Admin\PagesController@update')->name('admin/pages/update');
    Route::delete('/admin/pages/{page}',                        'Admin\PagesController@destroy')->name('admin/pages/destroy');
});


/*****
 * Legacy
 *****/
Route::any("/{path?}", "Legacy\LegacySiteController@index")->where("path", ".*");