<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->name('api.v1.')
    ->group(function (): void {
        Route::get('/health', function () {
            return response()->json([
                'success' => true,
                'message' => 'HelpDesk API is running.',
                'data' => [
                    'service' => config('app.name'),
                    'version' => 'v1',
                    'timestamp' => now()->toISOString(),
                ],
                'meta' => (object) [],
            ]);
        })->name('health');

        require base_path('routes/api/v1/auth.php');

        Route::middleware('auth:api')->group(function (): void {
            require base_path('routes/api/v1/admin.php');
            require base_path('routes/api/v1/tickets.php');
        });
    });
