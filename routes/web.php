<?php

use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/{course?}', DashboardController::class)->name('dashboard');
    Route::resource('courses', CourseController::class)->except(['show']);

    Route::resource('certificates', CertificateController::class)->except(['show', 'index']);
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');
    Route::post('/certificates/bulk-download', [CertificateController::class, 'bulkDownload'])->name('certificates.bulk-download');

    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::delete('/settings', [SettingsController::class, 'destroy'])->name('settings.destroy');
});
