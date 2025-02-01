<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/dashboard', fn() => Inertia::render('Dashboard'))
        ->name('dashboard');

    Route::resource('users', UserController::class)->only(['index', 'update', 'destroy']);
    Route::post('users/{user}', [UserController::class, 'restore'])
        ->withTrashed()
        ->name('users.restore');

    Route::resource('tasks', TaskController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('tasks/{task}', [TaskController::class, 'restore'])
        ->withTrashed()
        ->name('tasks.restore');
    Route::post('tasks/{task}/claim', [TaskController::class, 'claim'])
        ->name('tasks.claim');
});
