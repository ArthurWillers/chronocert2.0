<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/{course?}', DashboardController::class)->name('dashboard');
    Route::resource('courses', CourseController::class)->except(['show']);
});
