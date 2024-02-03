<?php

use Roxayl\MondeGC\Http\Controllers;
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

// Laravel UI authentication routes (deprecated)...
Route::get('login', fn() => redirect(url('connexion.php')))->name('login');
Route::post('login', fn() => redirect(url('connexion.php')));
Route::post('logout', fn() => redirect(url('connexion.php?doLogout=true'))) // FIXME: nécessite de passer le jeton CSRF
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
Route::get('page/{page}-{url}', [Controllers\PageController::class, 'index'])->name('page.show');

/*
|--------------------------------------------------------------------------
| Pays
|--------------------------------------------------------------------------
*/
Route::get('pays', fn() => redirect('Page-carte.php#liste-pays'))->name('pays.index');
Route::get('pays/{pays}-{paysSlug}', [Controllers\PaysController::class, 'show'])->name('pays.show');
Route::match(['put', 'patch'], 'pays/manage-subdivisions/{pays}', [Controllers\PaysController::class, 'manageSubdivisions'])
    ->name('pays.manage-subdivisions');
Route::get('pays/history/{pays}', [Controllers\PaysController::class, 'history'])->name('pays.history');
Route::get('pays/diff/{version1}/{version2?}', [Controllers\PaysController::class, 'diff'])
    ->name('pays.diff');

/*
|--------------------------------------------------------------------------
| Subdivision
|--------------------------------------------------------------------------
*/
Route::get('pays/{paysId}-{paysSlug}/{subdivisionTypeName}/{subdivision}-{subdivisionSlug}',
    [Controllers\SubdivisionController::class, 'show'])->name('subdivision.show');

/*
|--------------------------------------------------------------------------
| Ville
|--------------------------------------------------------------------------
*/
Route::get('ville/history/{ville}', [Controllers\VilleController::class, 'history'])->name('ville.history');
Route::get('ville/diff/{version1}/{version2?}', [Controllers\VilleController::class, 'diff'])
    ->name('ville.diff');

/*
|--------------------------------------------------------------------------
| Organisation
|--------------------------------------------------------------------------
*/
Route::get('organisation/{id}-{slug}', [Controllers\OrganisationController::class, 'show'])
    ->name('organisation.showslug');
Route::resource('organisation', Controllers\OrganisationController::class);
Route::get('organisation/{organisation}/migrate', [Controllers\OrganisationController::class, 'migrate'])
    ->name('organisation.migrate');
Route::get('organisation/{organisation}/delete', [Controllers\OrganisationController::class, 'delete'])
    ->name('organisation.delete');
Route::match(['put', 'patch'], 'organisation/{organisation}/migrate', [Controllers\OrganisationController::class, 'runMigration'])
    ->name('organisation.run-migration');
Route::get('organisation/history/{organisation}', [Controllers\OrganisationController::class, 'history'])
    ->name('organisation.history');
Route::get('organisation/diff/{version1}/{version2?}', [Controllers\OrganisationController::class, 'diff'])
    ->name('organisation.diff');

/*
|--------------------------------------------------------------------------
| OrganisationMember
|--------------------------------------------------------------------------
*/
Route::get('organisation/{organisationId}/join', [Controllers\OrganisationMemberController::class, 'join'])
    ->name('organisation-member.join');
Route::post('organisation/{organisationId}/join', [Controllers\OrganisationMemberController::class, 'store'])
    ->name('organisation-member.store');
Route::get('organisation/{organisationId}/invite', [Controllers\OrganisationMemberController::class, 'invite'])
    ->name('organisation-member.invite');
Route::post('organisation/{organisationId}/invite', [Controllers\OrganisationMemberController::class, 'sendInvitation'])
    ->name('organisation-member.send-invitation');
Route::get('organisation-member/{id}/edit', [Controllers\OrganisationMemberController::class, 'edit'])
    ->name('organisation-member.edit');
Route::match(['put', 'patch'], 'organisation-member/{id}', [Controllers\OrganisationMemberController::class, 'update'])
    ->name('organisation-member.update');
Route::get('organisation-member/{id}/delete', [Controllers\OrganisationMemberController::class, 'delete'])
    ->name('organisation-member.delete');
Route::delete('organisation-member/{id}', [Controllers\OrganisationMemberController::class, 'destroy'])
    ->name('organisation-member.destroy');

/*
|--------------------------------------------------------------------------
| Infrastructure
|--------------------------------------------------------------------------
*/
Route::get('infrastructure/{id}', [Controllers\InfrastructureController::class, 'show'])->name('infrastructure.show');
Route::get('infrastructure/select-group/type:{infrastructurableType}/id:{infrastructurableId}',
    [Controllers\InfrastructureController::class, 'selectGroup'])->name('infrastructure.select-group');
Route::get('infrastructure/create/type:{infrastructurableType}/id:{infrastructurableId}',
    [Controllers\InfrastructureController::class, 'create'])->name('infrastructure.create');
Route::post('infrastructure/create', [Controllers\InfrastructureController::class, 'store'])
    ->name('infrastructure.store');
Route::get('infrastructure/{infrastructure_id}/edit', [Controllers\InfrastructureController::class, 'edit'])
    ->name('infrastructure.edit');
Route::match(['put', 'patch'], 'infrastructure/{infrastructure_id}', [Controllers\InfrastructureController::class, 'update'])
    ->name('infrastructure.update');
Route::get('infrastructure/{infrastructure_id}/delete', [Controllers\InfrastructureController::class, 'delete'])
    ->name('infrastructure.delete');
Route::delete('infrastructure/{infrastructure_id}', [Controllers\InfrastructureController::class, 'destroy'])
    ->name('infrastructure.destroy');

/*
|--------------------------------------------------------------------------
| InfrastructureJudge
|--------------------------------------------------------------------------
*/
Route::get('economy/infrastructure-judge', [Controllers\InfrastructureJudgeController::class, 'index'])
    ->name('infrastructure-judge.index');
Route::get('economy/infrastructure-judge/{infrastructure}', [Controllers\InfrastructureJudgeController::class, 'show'])
    ->name('infrastructure-judge.show');
Route::patch('economy/infrastructure-judge/{infrastructure}', [Controllers\InfrastructureJudgeController::class, 'judge'])
    ->name('infrastructure-judge.judge');

/*
|--------------------------------------------------------------------------
| Roleplay
|--------------------------------------------------------------------------
*/
Route::get('roleplay/roleplayables', [Controllers\RoleplayController::class, 'roleplayables'])
    ->name('roleplay.roleplayables');
Route::resource('roleplay', Controllers\RoleplayController::class);
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
Route::resource('chapter', Controllers\ChapterController::class)->except(['create', 'store']);

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
Route::resource('chapter-entry', Controllers\ChapterEntryController::class)
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

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/
Route::get('search', [Controllers\SearchController::class, 'index'])->name('search');

/*
|--------------------------------------------------------------------------
| Map
|--------------------------------------------------------------------------
*/
Route::get('map', [Controllers\MapController::class, 'explore'])->name('map');

/*
|--------------------------------------------------------------------------
| Notification
|--------------------------------------------------------------------------
*/
Route::get('user/notifications', [Controllers\NotificationController::class, 'index'])->name('notification');
Route::post('user/notifications/mark-as-read', [Controllers\NotificationController::class, 'markAsRead'])
    ->name('notification.mark-as-read');

/*
|--------------------------------------------------------------------------
| DataExporter
|--------------------------------------------------------------------------
*/
Route::get('data-export/temperance-pays', [Controllers\DataExporterController::class, 'temperancePays'])
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
Route::post('back-office/advanced-parameters/regenerate-influences',
    [Controllers\BackOfficeController::class, 'regenerateInfluences'])
    ->name('back-office.advanced-parameters.regenerate-influences');
