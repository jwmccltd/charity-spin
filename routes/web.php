<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\FileUploadController;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::post('upload-audio', [FileUploadController::class, 'upload'])->name('files.upload');
    Route::get('create-campaign', [CampaignController::class, 'create'])->name('campaigns.create');
});

require __DIR__.'/settings.php';
