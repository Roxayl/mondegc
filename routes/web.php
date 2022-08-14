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
Route::get('user/roleplayables', [Controllers\CustomUserController::class, 'roleplayables'])
    ->name('user.roleplayables');
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
Route::get('organisation/{organisation}/delete', [Controllers\OrganisationController::class, 'delete'])
    ->name('organisation.delete');
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
Route::get('roleplay/confirm-close/{roleplay}', [Controllers\RoleplayController::class, 'confirmClose'])
    ->name('roleplay.confirm-close');
Route::match(['put', 'patch'], 'roleplay/close/{roleplay}', [Controllers\RoleplayController::class, 'close'])
    ->name('roleplay.close');
Route::get('roleplay/delete/{roleplay}', [Controllers\RoleplayController::class, 'delete'])
    ->name('roleplay.delete');
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
Route::get('chapter/diff/{version1}/{version2?}', [Controllers\ChapterController::class, 'diff'])
    ->name('chapter.diff');
Route::post('chapter/{roleplay}', [Controllers\ChapterController::class, 'store'])
    ->name('chapter.store');
Route::get('chapter/create-button/{roleplay}', [Controllers\ChapterController::class, 'createButton'])
    ->name('chapter.create-button');
Route::get('chapter/history/{chapter}', [Controllers\ChapterController::class, 'history'])
    ->name('chapter.history');
Route::get('chapter/delete/{chapter}', [Controllers\ChapterController::class, 'delete'])
    ->name('chapter.delete');
Route::resource('chapter', 'ChapterController')->except(['create', 'store']);

/*
|--------------------------------------------------------------------------
| ChapterEntry
|--------------------------------------------------------------------------
*/
Route::get('chapter-entry/create/{chapter}', [Controllers\ChapterEntryController::class, 'create'])
    ->name('chapter-entry.create');
Route::get('chapter-entry/create-button/{chapter}', [Controllers\ChapterEntryController::class, 'createButton'])
    ->name('chapter-entry.create-button');
Route::post('chapter-entry/{chapter}', [Controllers\ChapterEntryController::class, 'store'])
    ->name('chapter-entry.store');
Route::get('chapter-entry/delete/{entry}', [Controllers\ChapterEntryController::class, 'delete'])
    ->name('chapter-entry.delete');
Route::delete('chapter-entry/{entry}', [Controllers\ChapterEntryController::class, 'destroy'])
    ->name('chapter-entry.destroy');
Route::resource('chapter-entry', 'ChapterEntryController')
    ->except(['show', 'create', 'store', 'destroy']);

/*
|--------------------------------------------------------------------------
| ChapterResourceable
|--------------------------------------------------------------------------
*/
Route::get('chapter-resourceable/show/{chapter}', [Controllers\ChapterResourceableController::class, 'show'])
    ->name('chapter-resourceable.show');
Route::get('chapter-resourceable/manage/{chapter}', [Controllers\ChapterResourceableController::class, 'manage'])
    ->name('chapter-resourceable.manage');
Route::get('chapter-resourceable/create/{chapter}', [Controllers\ChapterResourceableController::class, 'create'])
    ->name('chapter-resourceable.create');
Route::post('chapter-resourceable/{chapter}', [Controllers\ChapterResourceableController::class, 'store'])
    ->name('chapter-resourceable.store');
Route::get('chapter-resourceable/edit/{chapterResourceable}',
    [Controllers\ChapterResourceableController::class, 'edit'])
    ->name('chapter-resourceable.edit');
Route::match(['put', 'patch'], 'chapter-resourceable/edit/{chapterResourceable}',
    [Controllers\ChapterResourceableController::class, 'update'])
    ->name('chapter-resourceable.update');
Route::get('chapter-resourceable/delete/{chapterResourceable}',
    [Controllers\ChapterResourceableController::class, 'delete'])
    ->name('chapter-resourceable.delete');
Route::delete('chapter-resourceable/{chapterResourceable}',
    [Controllers\ChapterResourceableController::class, 'destroy'])
    ->name('chapter-resourceable.destroy');

/*
|--------------------------------------------------------------------------
| Version
|--------------------------------------------------------------------------
*/
Route::post('version/revert/{version}', [Controllers\VersionController::class, 'revert'])
    ->name('version.revert');
Route::get('version/diff/{version1}/{version2?}/{key}', [Controllers\VersionController::class, 'diff'])
    ->name('version.diff');

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

/*
|--------------------------------------------------------------------------
| Back-office
|--------------------------------------------------------------------------
*/

Route::get('back-office/advanced-parameters', [Controllers\BackOfficeController::class, 'advancedParameters'])
    ->name('back-office.advanced-parameters');
Route::post('back-office/advanced-parameters/purge-cache', [Controllers\BackOfficeController::class, 'purgeCache'])
    ->name('back-office.advanced-parameters.purge-cache');
