<?php

use App\Http\Controllers\Api\V1\Admin\CategoryController;
use App\Http\Controllers\Api\V1\Admin\PriorityController;
use App\Http\Controllers\Api\V1\Admin\StatusController;
use App\Http\Controllers\Api\V1\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */
        Route::prefix('users')->name('users.')->group(function (): void {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/assignable-agents', [UserController::class, 'assignableAgents'])->name('assignable-agents');
            Route::get('/{user:uuid}', [UserController::class, 'show'])->name('show');
            Route::put('/{user:uuid}', [UserController::class, 'update'])->name('update');
            Route::patch('/{user:uuid}', [UserController::class, 'update'])->name('patch');
            Route::delete('/{user:uuid}', [UserController::class, 'destroy'])->name('destroy');
        });

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */
        Route::prefix('categories')->name('categories.')->group(function (): void {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::get('/{category:uuid}', [CategoryController::class, 'show'])->name('show');
            Route::put('/{category:uuid}', [CategoryController::class, 'update'])->name('update');
            Route::patch('/{category:uuid}', [CategoryController::class, 'update'])->name('patch');
            Route::delete('/{category:uuid}', [CategoryController::class, 'destroy'])->name('destroy');
        });

        /*
        |--------------------------------------------------------------------------
        | Priorities
        |--------------------------------------------------------------------------
        */
        Route::prefix('priorities')->name('priorities.')->group(function (): void {
            Route::get('/', [PriorityController::class, 'index'])->name('index');
            Route::post('/', [PriorityController::class, 'store'])->name('store');
            Route::get('/{priority:uuid}', [PriorityController::class, 'show'])->name('show');
            Route::put('/{priority:uuid}', [PriorityController::class, 'update'])->name('update');
            Route::patch('/{priority:uuid}', [PriorityController::class, 'update'])->name('patch');
            Route::delete('/{priority:uuid}', [PriorityController::class, 'destroy'])->name('destroy');
        });

        /*
        |--------------------------------------------------------------------------
        | Statuses
        |--------------------------------------------------------------------------
        */
        Route::prefix('statuses')->name('statuses.')->group(function (): void {
            Route::get('/', [StatusController::class, 'index'])->name('index');
            Route::post('/', [StatusController::class, 'store'])->name('store');
            Route::get('/{status:uuid}', [StatusController::class, 'show'])->name('show');
            Route::put('/{status:uuid}', [StatusController::class, 'update'])->name('update');
            Route::patch('/{status:uuid}', [StatusController::class, 'update'])->name('patch');
            Route::delete('/{status:uuid}', [StatusController::class, 'destroy'])->name('destroy');
        });
    });
