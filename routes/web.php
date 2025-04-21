<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('tasks.index');
})->name('home');

// Publicly accessible tasks index
Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('tasks', TaskController::class)->except(['index']);
    Route::post('tasks/{task}', [TaskController::class, 'restore'])
        ->name('tasks.restore');
    Route::post('tasks/{task}/request', [TaskController::class, 'requestTask'])
        ->name('tasks.request');
    Route::post('tasks/{task}/assign', [TaskController::class, 'assignUser'])
        ->name('tasks.assign');
    Route::get('tasks/{task}/request/users', [TaskController::class, 'requestedUsers'])
        ->name('tasks.requested.users');

    // Admin-only routes
    Route::middleware('role:ADMIN')->group(function () {
        Route::resource('users', UserController::class)->only(['index', 'update', 'destroy']);
        Route::post('users/{user}', [UserController::class, 'restore'])
            ->name('users.restore');

        Route::resource('roles', RoleController::class)->only(['index', 'store', 'update', 'destroy']);
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
