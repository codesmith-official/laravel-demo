<?php

use App\Http\Controllers\Api\DashboardStatsController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth:api'])->group(function (): void {
    Route::get('/dashboard/stats', DashboardStatsController::class)->name('api.dashboard.stats');
    Route::apiResource('users', UserController::class)->except(['create', 'edit'])->names('api.users');
});
