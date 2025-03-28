<?php

use App\Http\Controllers\DashboardJobController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobViewController;


Route::middleware('auth','verified')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('dashboard')->middleware('auth','verified')->group(function () {
    Route::get('/', [DashboardJobController::class, 'index'])->name('dashboard');
    Route::get('/jobs', [DashboardJobController::class, 'allJobs'])->name('dashboard.jobs.index');
    Route::get('/jobs/create', [DashboardJobController::class, 'create'])->name('dashboard.jobs.create');
    Route::get('/jobs/edit/{job}', [DashboardJobController::class, 'edit'])->name('dashboard.jobs.edit');
    Route::get('/jobs/{job}', [DashboardJobController::class, 'show'])->name('dashboard.jobs.show');
    Route::post('/jobs', [DashboardJobController::class, 'store'])->name('dashboard.jobs.store');
    Route::PUT('/jobs/{job}', [DashboardJobController::class, 'update'])->name('dashboard.jobs.update');
    Route::delete('/jobs/destroy{job}', [DashboardJobController::class, 'destroy'])->name('dashboard.jobs.destroy');
});



Route::get('/', [JobViewController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [JobViewController::class, 'show'])->name('jobs.show');


require __DIR__ . '/auth.php';