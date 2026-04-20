<?php

use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/admin/dashboard', [DashboardController::class, 'show'])
        ->defaults('role', 'admin')
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::get('/principal/dashboard', [DashboardController::class, 'show'])
        ->defaults('role', 'principal')
        ->middleware('role:principal')
        ->name('principal.dashboard');

    Route::get('/faculty/dashboard', [DashboardController::class, 'show'])
        ->defaults('role', 'faculty')
        ->middleware('role:faculty')
        ->name('faculty.dashboard');

    Route::get('/student/dashboard', [DashboardController::class, 'show'])
        ->defaults('role', 'student')
        ->middleware('role:student')
        ->name('student.dashboard');

    Route::get('/feedback', [FeedbackController::class, 'index'])
        ->name('feedback.index');

    Route::middleware('role:student')->group(function () {
        Route::get('/feedback/create', [FeedbackController::class, 'create'])
            ->name('feedback.create');
        Route::post('/feedback', [FeedbackController::class, 'store'])
            ->name('feedback.store');
    });

    Route::middleware('role:faculty,principal,admin')->group(function () {
        Route::patch('/feedback/{feedback}/approve', [FeedbackController::class, 'approve'])
            ->name('feedback.approve');
        Route::patch('/feedback/{feedback}/reject', [FeedbackController::class, 'reject'])
            ->name('feedback.reject');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/restore', [UserManagementController::class, 'restore'])->name('users.restore');
        Route::get('/calendar', [CalendarController::class, 'edit'])->name('calendar.edit');
        Route::patch('/calendar', [CalendarController::class, 'update'])->name('calendar.update');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
