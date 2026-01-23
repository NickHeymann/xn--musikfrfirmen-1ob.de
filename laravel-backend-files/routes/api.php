<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;

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

// Pages API
Route::prefix('pages')->group(function () {
    Route::get('/', [PageController::class, 'index']);          // List all pages
    Route::get('/{slug}', [PageController::class, 'show']);     // Get page by slug
    Route::post('/', [PageController::class, 'store']);         // Create new page
    Route::put('/{slug}', [PageController::class, 'update']);   // Update page
    Route::delete('/{slug}', [PageController::class, 'destroy']); // Delete page

    // Media upload (legacy)
    Route::post('/media', [MediaController::class, 'upload']);  // Upload images
});

// Media API (temp upload workflow)
Route::prefix('media')->group(function () {
    Route::post('/upload-temp', [MediaController::class, 'uploadTemp']);     // Upload to temp
    Route::post('/commit-temp', [MediaController::class, 'commitTemp']);     // Move temp to permanent
    Route::delete('/temp/{tempId}', [MediaController::class, 'deleteTemp']); // Delete temp file
});
