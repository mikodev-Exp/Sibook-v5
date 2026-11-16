<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermohonanController;
use App\Http\Controllers\Admin\PresensiController;
use App\Http\Controllers\Admin\PerizinanController;
use App\Http\Controllers\Admin\UserRegisterController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/permohonan', [PermohonanController::class, 'index'])->name('permohonan.index');
        Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
        Route::get('/perizinan', [PerizinanController::class, 'index'])->name('perizinan.index');
        Route::get('/user-register', [UserRegisterController::class, 'index'])->name('user-register.index');
    });