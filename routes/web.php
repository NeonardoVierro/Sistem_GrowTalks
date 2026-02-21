<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PodcastController;
use App\Http\Controllers\CoachingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VerifikatorPodcastController;
use App\Http\Controllers\VerifikatorCoachingController;
use App\Http\Controllers\AdminStaffController;

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
    Route::get('/profile', function () {
        return view('user.profil');
    })->name('user.profile')->middleware('auth');

    // Podcast Routes
    Route::prefix('podcast')->group(function () {
        Route::get('/', [PodcastController::class, 'index'])->name('podcast.index');
        Route::post('/submit', [PodcastController::class, 'submit'])->name('podcast.submit');
        Route::delete('/{id}', [PodcastController::class, 'destroy'])->name('podcast.destroy');
    });
    
    // Coaching Routes
    Route::prefix('coaching')->group(function () {
        Route::get('/', [CoachingController::class, 'index'])->name('coaching.index');
        Route::get('/detail/{date}', [CoachingController::class, 'detail'])->name('coaching.detail');
        Route::post('/submit', [CoachingController::class, 'submit'])->name('coaching.submit');
        Route::delete('/{id}', [CoachingController::class, 'destroy'])->name('coaching.destroy');
    });
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth:internal'])->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/users/{id}/toggle-status', [AdminController::class, 'toggleStatus'])->name('users.toggle-status');


    Route::get('/podcasts', [AdminController::class, 'podcasts'])->name('podcasts');
    Route::get('/podcasts/{id}', [AdminController::class, 'showPodcast'])
        ->name('podcasts.show');

    Route::put('/podcasts/{id}/status', [AdminController::class, 'updatePodcastStatus'])
        ->name('podcasts.status');

    Route::get('/podcasts', [AdminController::class, 'podcasts'])->name('podcasts');
    Route::get('/podcasts/{id}', [AdminController::class, 'showPodcast'])->name('podcast.show');
    Route::put('/podcasts/{id}/status', [AdminController::class, 'updatePodcastStatus'])->name('podcasts.status');

    Route::get('/coachings', [AdminController::class, 'coachings'])->name('coachings');
    Route::put('/coachings/{id}/status', [AdminController::class, 'updateCoachingStatus'])
        ->name('coachings.status');

    // Manage Hosts & Coaches
    Route::get('/staffs', [AdminStaffController::class, 'index'])->name('staffs.index');
    Route::get('/staffs/create', [AdminStaffController::class, 'create'])->name('staffs.create');
    Route::post('/staffs', [AdminStaffController::class, 'store'])->name('staffs.store');
    Route::get('/staffs/{id}/edit', [AdminStaffController::class, 'edit'])->name('staffs.edit');
    Route::put('/staffs/{id}', [AdminStaffController::class, 'update'])->name('staffs.update');
    Route::delete('/staffs/{id}', [AdminStaffController::class, 'destroy'])->name('staffs.destroy');
    Route::get('/reports/podcast', [AdminController::class, 'reportPodcast'])
        ->name('reports.podcast');
    Route::get('/reports/coaching', [AdminController::class, 'reportCoaching'])
        ->name('reports.coaching');
});

// Verifikator Podcast Routes
Route::prefix('verifikator-podcast')->name('verifikator-podcast.')->group(function () {
    Route::middleware(['auth:internal'])->group(function () {
        Route::get('/dashboard', [VerifikatorPodcastController::class, 'dashboard'])->name('dashboard');
        Route::get('/approval', [VerifikatorPodcastController::class, 'approval'])->name('approval');
        Route::get('/approval/{id}/form', [VerifikatorPodcastController::class, 'showApprovalForm'])->name('approval-form');
        Route::put('/approval/{id}/update', [VerifikatorPodcastController::class, 'updateApproval'])->name('update-approval');
        Route::get('/report', [VerifikatorPodcastController::class, 'report'])->name('report');
        Route::post('/podcast/{id}/upload-cover',[VerifikatorPodcastController::class, 'uploadCover'])->name('upload-cover');
        Route::delete('/podcast/{id}/delete-cover',[VerifikatorPodcastController::class, 'deleteCover'])->name('delete-cover');
    });
});

// Verifikator Coaching Routes
Route::prefix('verifikator-coaching')->name('verifikator-coaching.')->group(function () {
    Route::middleware(['auth:internal'])->group(function () {
        Route::get('/dashboard', [VerifikatorCoachingController::class, 'dashboard'])->name('dashboard');
        Route::get('/get-bookings-by-date', [VerifikatorCoachingController::class, 'getBookingsByDate'])->name('get-bookings-by-date');
        Route::get('/approval', [VerifikatorCoachingController::class, 'approval'])->name('approval');
        Route::get('/approval/{id}/form', [VerifikatorCoachingController::class, 'showApprovalForm'])->name('approval-form');
        Route::put('/approval/{id}/update', [VerifikatorCoachingController::class, 'updateApproval'])->name('update-approval');
        Route::get('/report', [VerifikatorCoachingController::class, 'report'])->name('report');
         Route::post('/coaching/{id}/upload-dokumentasi',
            [VerifikatorCoachingController::class, 'uploadDokumentasi']
        )->name('upload');
        Route::delete('/coaching/{id}/delete-documentation',
            [VerifikatorCoachingController::class, 'deleteDokumentasi']
        )->name('delete-documentation');
    });
});