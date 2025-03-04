<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('users', UserController::class)->only(['index', 'update', 'destroy']);
    Route::post('users/{user}', [UserController::class, 'restore'])
        ->withTrashed()
        ->name('users.restore');

    Route::resource('tasks', TaskController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('tasks/{task}', [TaskController::class, 'restore'])
        ->withTrashed()
        ->name('tasks.restore');

    Route::get('tasks/{task}/request/users', [TaskController::class, 'requestedUsers'])
        ->name('tasks.requested.users');
    Route::post('tasks/{task}/request', [TaskController::class, 'requestTask'])
        ->name('tasks.request');
    Route::post('tasks/{task}/assign', [TaskController::class, 'assignUser'])
        ->name('tasks.assign');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
