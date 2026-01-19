<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PodcastController;
use App\Http\Controllers\CoachingController;
use App\Http\Controllers\AdminController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    
    // Podcast Routes
    Route::prefix('podcast')->group(function () {
        Route::get('/', [PodcastController::class, 'index'])->name('podcast.index');
        Route::post('/submit', [PodcastController::class, 'submit'])->name('podcast.submit');
        Route::delete('/{id}', [PodcastController::class, 'destroy'])->name('podcast.destroy');
    });
    
    // Coaching Routes
    Route::prefix('coaching')->group(function () {
        Route::get('/', [CoachingController::class, 'index'])->name('coaching.index');
        Route::post('/submit', [CoachingController::class, 'submit'])->name('coaching.submit');
        Route::delete('/{id}', [CoachingController::class, 'destroy'])->name('coaching.destroy');
    });
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/podcasts', [AdminController::class, 'podcasts'])->name('podcasts');
        Route::get('/coachings', [AdminController::class, 'coachings'])->name('coachings');
        Route::get('/reports/podcast', [AdminController::class, 'reportPodcast'])->name('reports.podcast');
        Route::get('/reports/coaching', [AdminController::class, 'reportCoaching'])->name('reports.coaching');
        
        Route::put('/podcasts/{id}/status', [AdminController::class, 'updatePodcastStatus'])->name('podcasts.status');
        Route::put('/coachings/{id}/status', [AdminController::class, 'updateCoachingStatus'])->name('coachings.status');
    });
});
