<?php

use App\Http\Controllers\Pendaftar\DashboardController;
use App\Http\Controllers\Pendaftar\RegisterController; // <-- Tambahkan ini
use Illuminate\Support\Facades\Route;

// Rute untuk pendaftaran (Bisa diakses tanpa login)
Route::get('/register-magang', [RegisterController::class, 'showForm'])->name('pendaftar.register');
Route::post('/register-magang', [RegisterController::class, 'store'])->name('pendaftar.register.store');

// Rute setelah login (Dashboard)
Route::middleware(['auth', 'verified', 'role:pendaftar'])
    ->prefix('pendaftar')
    ->name('pendaftar.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });