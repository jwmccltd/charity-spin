<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignsController;
use App\Http\Controllers\FileUploadController;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::inertia('create-campaign', 'campaigns/Create')->name('campaigns.create');
    Route::post('upload-audio', [FileUploadController::class, 'upload'])->name('files.upload');
    //Route::get('campaigns/create', [CampaignsController::class, 'create'])->name('campaigns.create');
});

require __DIR__.'/settings.php';
