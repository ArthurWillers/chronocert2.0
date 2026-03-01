<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/{course?}', DashboardController::class)->name('dashboard');
    Route::resource('courses', CourseController::class)->except(['show']);

    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::delete('/settings', [SettingsController::class, 'destroy'])->name('settings.destroy');
});
