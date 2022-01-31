<?php

use App\Http\Controllers\Api;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth.api', 'throttle:12,1'])->group(function() {

    Route::get('/resourceable/fetch/{type}', [Api\ResourceableController::class, 'fetch'])
        ->name('api.resourceable.fetch');

    Route::get('/resource/fetch/{type}', [Api\ResourceController::class, 'fetch'])
        ->name('api.resource.fetch');

    Route::get('/admin/store-resource-history', [Api\AdminController::class, 'storeResourceHistory'])
        ->name('api.admin.store-resource-history');

});
