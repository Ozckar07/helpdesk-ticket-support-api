<?php

use App\Http\Controllers\Api\V1\Ticket\TicketController;
use App\Http\Controllers\Api\V1\Ticket\TicketMessageController;
use Illuminate\Support\Facades\Route;

Route::prefix('tickets')
    ->name('tickets.')
    ->group(function (): void {
        /*
        |--------------------------------------------------------------------------
        | Ticket CRUD
        |--------------------------------------------------------------------------
        */
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::post('/', [TicketController::class, 'store'])->name('store');
        Route::get('/{ticket:uuid}', [TicketController::class, 'show'])->name('show');
        Route::put('/{ticket:uuid}', [TicketController::class, 'update'])->name('update');
        Route::patch('/{ticket:uuid}', [TicketController::class, 'update'])->name('patch');

        /*
        |--------------------------------------------------------------------------
        | Ticket actions
        |--------------------------------------------------------------------------
        */
        Route::patch('/{ticket:uuid}/assign', [TicketController::class, 'assign'])->name('assign');
        Route::patch('/{ticket:uuid}/change-status', [TicketController::class, 'changeStatus'])->name('change-status');
        Route::patch('/{ticket:uuid}/change-priority', [TicketController::class, 'changePriority'])->name('change-priority');
        Route::patch('/{ticket:uuid}/change-category', [TicketController::class, 'changeCategory'])->name('change-category');

        /*
        |--------------------------------------------------------------------------
        | Ticket messages
        |--------------------------------------------------------------------------
        */
        Route::get('/{ticket:uuid}/messages', [TicketMessageController::class, 'index'])->name('messages.index');
        Route::post('/{ticket:uuid}/messages', [TicketMessageController::class, 'store'])->name('messages.store');
    });
