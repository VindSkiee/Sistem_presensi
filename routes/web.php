<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\AttendanceController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User Routes
Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::post('/user/attendance', [UserController::class, 'submitAttendance'])->name('user.attendance.submit');
    // routes/web.php
    Route::post('/schedules/{id}/upload-photo', [userController::class, 'uploadPhoto'])
        ->name('user.schedules.uploadPhoto');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/validate-attendance', [AdminController::class, 'validateAttendance'])->name('admin.attendance.validate');

    // Schedule Routes
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('admin.schedules.index');
    Route::get('/schedules/create', [ScheduleController::class, 'create'])->name('admin.schedules.create');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('admin.schedules.store');
    Route::get('/schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('admin.schedules.edit');
    Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('admin.schedules.update');
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('admin.schedules.destroy');
    Route::get('/validate-previous', [ScheduleController::class, 'showUnvalidatedSchedules'])->name('admin.validate.previous');
    Route::put('/validate-previous/{schedule}', [ScheduleController::class, 'updateUnvalidated'])->name('admin.validate.update');
    Route::get('/admin/generate-weekly', [ScheduleController::class, 'showGenerateWeeklyForm'])->name('admin.generate.weekly.form');
    Route::post('/admin/generate-weekly', [ScheduleController::class, 'generateWeekly'])->name('admin.generate.weekly');

    // Person Routes
    Route::get('/persons', [PersonController::class, 'index'])->name('admin.persons.index');
    Route::get('/persons/create', [PersonController::class, 'create'])->name('admin.persons.create');
    Route::post('/persons', [PersonController::class, 'store'])->name('admin.persons.store');
    Route::get('/persons/{person}/edit', [PersonController::class, 'edit'])->name('admin.persons.edit');
    Route::put('/persons/{person}', [PersonController::class, 'update'])->name('admin.persons.update');
    Route::delete('/persons/{person}', [PersonController::class, 'destroy'])->name('admin.persons.destroy');

    // Attendance History
    Route::get('/attendances/history', [AttendanceController::class, 'history'])->name('admin.attendances.history');
});

// Redirect root to appropriate dashboard
Route::redirect('/', '/login');
Route::redirect('/dashboard', '/user/dashboard');
