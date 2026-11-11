<?php

use App\Http\Controllers\Pendaftar\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:pendaftar'])
    ->prefix('pendaftar')
    ->name('pendaftar.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });