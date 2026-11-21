<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\SavedJobController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Guest-First Experience)
|--------------------------------------------------------------------------
*/
Route::get('/', [JobController::class, 'index'])->name('jobs.index');
Route::get('/govt-jobs', [JobController::class, 'govtHub'])->name('jobs.govt');
Route::get('/jobs/{slug}', [JobController::class, 'show'])->name('jobs.show');
Route::get('/jobs/{slug}/apply', [JobController::class, 'applyRedirect'])->name('jobs.apply');

// Bookmark/Save job (accessible to both guest and auth - guest gets redirected with intent preserved)
Route::post('/jobs/{id}/save', [SavedJobController::class, 'toggle'])->name('jobs.save');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| User Dashboard Routes (Protected by auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::delete('/saved-jobs/{id}', [SavedJobController::class, 'destroy'])->name('saved-jobs.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Control Panel Routes (Protected by auth + admin middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/scrape', [AdminController::class, 'runScraper'])->name('scrape');
    Route::post('/purge-expired', [AdminController::class, 'purgeExpired'])->name('purge.expired');
    Route::post('/validate-links', [AdminController::class, 'validateAllLinks'])->name('validate.links');
    Route::patch('/jobs/{id}/toggle-status', [AdminController::class, 'toggleJobStatus'])->name('jobs.toggle');
    Route::delete('/jobs/{id}', [AdminController::class, 'deleteJob'])->name('jobs.delete');
});
