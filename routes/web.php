<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DailyActivityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectTaskController;
use App\Http\Controllers\SprintController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::post('/calendar', [CalendarController::class, 'store'])->name('calendar.store');
    Route::put('/calendar/{calendarEvent}', [CalendarController::class, 'update'])->name('calendar.update');
    Route::delete('/calendar/{calendarEvent}', [CalendarController::class, 'destroy'])->name('calendar.destroy');
    Route::resource('users', UserController::class)->except(['show', 'destroy']);
    Route::put('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::resource('activities', DailyActivityController::class);
    Route::post('/activities/{activity}/comments', [DailyActivityController::class, 'comment'])->name('activities.comments.store');
    Route::resource('projects', ProjectController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('/projects/{project}/sprints', [SprintController::class, 'store'])->name('projects.sprints.store');
    Route::post('/projects/{project}/tasks', [ProjectTaskController::class, 'store'])->name('projects.tasks.store');
    Route::patch('/project-tasks/{projectTask}/status', [ProjectTaskController::class, 'updateStatus'])->name('project-tasks.status');
});
