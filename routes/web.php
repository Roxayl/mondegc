<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

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

/*
|--------------------------------------------------------------------------
| Routes prédéfinies
|--------------------------------------------------------------------------
|
| Routes par défaut de Laravel, avec l'authentification scaffoldé.
| Dans la mesure où l'application n'utilise pas l'authentification de Laravel,
| on redirige vers les pages de connexion/déconnexion du site legacy.
|
*/

Route::get('laravel', fn() => view('welcome'));

// Laravel UI authentication routes (deprecated)...
Route::get('login', fn() => url('connexion.php'))->name('login');
Route::post('login', fn() => url('connexion.php'));
Route::post('logout', fn() => url('connexion.php?doLogout=true')) // FIXME: nécessite de passer le jeton CSRF
    ->name('logout');

/*
|--------------------------------------------------------------------------
| CustomUser
|--------------------------------------------------------------------------
*/
Route::post('user/update-api-token/{user}', [Controllers\CustomUserController::class, 'updateToken'])
    ->name('user.update-api-token');

/*
|--------------------------------------------------------------------------
| Page
|--------------------------------------------------------------------------
*/
Route::get('page/{page}-{url}', 'PageController@index')->name('page.show');

/*
|--------------------------------------------------------------------------
| Organisation
|--------------------------------------------------------------------------
*/
Route::get('organisation/{id}-{slug}', 'OrganisationController@show')
    ->name('organisation.showslug');
Route::resource('organisation', 'OrganisationController');
Route::get('organisation/{organisation}/migrate', 'OrganisationController@migrate')
    ->name('organisation.migrate');
Route::match(['put', 'patch'], 'organisation/{organisation}/migrate', 'OrganisationController@runMigration')
    ->name('organisation.run-migration');

/*
|--------------------------------------------------------------------------
| OrganisationMember
|--------------------------------------------------------------------------
*/
Route::get('organisation/{organisation_id}/join', 'OrganisationMemberController@joinOrganisation')
    ->name('organisation-member.join');
Route::post('organisation/{organisation_id}/join', 'OrganisationMemberController@store')
    ->name('organisation-member.store');
Route::get('organisation/{organisation_id}/invite', 'OrganisationMemberController@invite')
    ->name('organisation-member.invite');
Route::post('organisation/{organisation_id}/invite', 'OrganisationMemberController@sendInvitation')
    ->name('organisation-member.send-invitation');
Route::get('organisation-member/{id}/edit', 'OrganisationMemberController@edit')
    ->name('organisation-member.edit');
Route::match(['put', 'patch'], 'organisation-member/{id}', 'OrganisationMemberController@update')
    ->name('organisation-member.update');
Route::get('organisation-member/{id}/delete', 'OrganisationMemberController@delete')
    ->name('organisation-member.delete');
Route::delete('organisation-member/{id}', 'OrganisationMemberController@destroy')
    ->name('organisation-member.destroy');

/*
|--------------------------------------------------------------------------
| Infrastructure
|--------------------------------------------------------------------------
*/
Route::get('infrastructure/{id}', 'InfrastructureController@show')->name('infrastructure.show');
Route::get('infrastructure/select-group/type:{infrastructurable_type}/id:{infrastructurable_id}',
    'InfrastructureController@selectGroup')->name('infrastructure.select-group');
Route::get('infrastructure/create/type:{infrastructurable_type}/id:{infrastructurable_id}',
    'InfrastructureController@create')->name('infrastructure.create');
Route::post('infrastructure/create', 'InfrastructureController@store')
    ->name('infrastructure.store');
Route::get('infrastructure/{infrastructure_id}/edit', 'InfrastructureController@edit')
    ->name('infrastructure.edit');
Route::match(['put', 'patch'], 'infrastructure/{infrastructure_id}', 'InfrastructureController@update')
    ->name('infrastructure.update');
Route::get('infrastructure/{infrastructure_id}/delete', 'InfrastructureController@delete')
    ->name('infrastructure.delete');
Route::delete('infrastructure/{infrastructure_id}', 'InfrastructureController@destroy')
    ->name('infrastructure.destroy');

/*
|--------------------------------------------------------------------------
| InfrastructureJudge
|--------------------------------------------------------------------------
*/
Route::get('economy/infrastructure-judge', 'InfrastructureJudgeController@index')
    ->name('infrastructure-judge.index');
Route::get('economy/infrastructure-judge/{infrastructure}', 'InfrastructureJudgeController@show')
    ->name('infrastructure-judge.show');
Route::patch('economy/infrastructure-judge/{infrastructure}', 'InfrastructureJudgeController@judge')
    ->name('infrastructure-judge.judge');

/*
|--------------------------------------------------------------------------
| Roleplay
|--------------------------------------------------------------------------
*/
Route::get('roleplay/roleplayables', [Controllers\RoleplayController::class, 'roleplayables'])
    ->name('roleplay.roleplayables');
Route::resource('roleplay', 'RoleplayController');
Route::get('roleplay/{roleplay}/organizers', [Controllers\RoleplayController::class, 'organizers'])
    ->name('roleplay.organizers');
Route::get('roleplay/{roleplay}/manage-organizers', [Controllers\RoleplayController::class, 'manageOrganizers'])
    ->name('roleplay.manage-organizers');
Route::get('roleplay/{roleplay}/add-organizer', [Controllers\RoleplayController::class, 'addOrganizer'])
    ->name('roleplay.add-organizer');
Route::post('roleplay/{roleplay}/create-organizer', [Controllers\RoleplayController::class, 'createOrganizer'])
    ->name('roleplay.create-organizer');
Route::delete('roleplay/{roleplay}/remove-organizer', [Controllers\RoleplayController::class, 'removeOrganizer'])
    ->name('roleplay.remove-organizer');

/*
|--------------------------------------------------------------------------
| Chapter
|--------------------------------------------------------------------------
*/
Route::get('chapter/create/{roleplay}', [Controllers\ChapterController::class, 'create'])
    ->name('chapter.create');
Route::post('chapter/{roleplay}', [Controllers\ChapterController::class, 'store'])
    ->name('chapter.store');
Route::get('chapter/create-button/{roleplay}', [Controllers\ChapterController::class, 'createButton'])
    ->name('chapter.create-button');
Route::resource('chapter', 'ChapterController')->except(['create', 'store']);

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/
Route::get('search', 'SearchController@index')->name('search');

/*
|--------------------------------------------------------------------------
| Map
|--------------------------------------------------------------------------
*/
Route::get('map', 'MapController@explore')->name('map');

/*
|--------------------------------------------------------------------------
| Notification
|--------------------------------------------------------------------------
*/
Route::get('user/notifications', 'NotificationController@index')->name('notification');
Route::post('user/notifications/mark-as-read', 'NotificationController@markAsRead')
    ->name('notification.mark-as-read');

/*
|--------------------------------------------------------------------------
| DataExporter
|--------------------------------------------------------------------------
*/
Route::get('data-export/temperance-pays', 'DataExporterController@temperancePays')
    ->name('data-export.temperance-pays');
