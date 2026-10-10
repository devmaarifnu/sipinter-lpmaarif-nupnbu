<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SyncController;
use App\Http\Controllers\Admin\VirtualNPSNController;
use App\Http\Controllers\Api\WatermarkWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('authverifytoken')->group(function() {
    Route::post('sync', [SyncController::class, 'bypassExistingData'])->name('sync.data');
    Route::get('clean-vnpsn', [VirtualNPSNController::class, 'checkAndRemoveUnusedVNPSN'])->name('vnpsn.clean');
});

/**
 * Webhook WM Service, memakai bearer token pada tabel access_token
 * (name = wm-service) seperti endpoint sync lainnya.
 */
Route::middleware('authverifytoken')->group(function () {
    Route::post('webhook/wm/akta', [WatermarkWebhookController::class, 'handleAkta'])->name('webhook.wm.akta');
});
