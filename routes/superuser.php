<?php

use App\Http\Controllers\SuperUser\DashboardController;
use App\Http\Controllers\SuperUser\PermohonanController;
use App\Http\Controllers\SuperUser\PengaturanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:super_user'])
    ->prefix('superuser')
    ->name('superuser.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Fitur Permohonan
        Route::get('/permohonan', [PermohonanController::class, 'index'])->name('permohonan.index');
        Route::post('/permohonan/{permohonan}/approve', [PermohonanController::class, 'approve'])->name('permohonan.approve');
        Route::post('/permohonan/{permohonan}/reject', [PermohonanController::class, 'reject'])->name('permohonan.reject');
        
        // Fitur Pengaturan
        Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    });