<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileProcessProgressController;
use App\Http\Controllers\CampaignController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/file-progress/{trackerKey}', [FileProcessProgressController::class, 'show']);
Route::get('/campaign/tracker/{trackerKey}', [CampaignController::class, 'getCampaignByTrackerKey']);
Route::get('/campaign/id/{id}', [CampaignController::class, 'getCampaignById']);
Route::get('/campaign/track/{id}', [CampaignController::class, 'getCampaignTrack']);
Route::get('/campaign/artwork/{id}', [CampaignController::class, 'getCampaignArtwork']);
