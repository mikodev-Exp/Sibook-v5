<?php

use App\Http\Controllers\SuperUser\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:super_user'])
    ->prefix('superuser')
    ->name('superuser.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });