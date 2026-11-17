<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LogbookController;
use App\Http\Controllers\Admin\MahasiswaController;
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

        // Logbook Mahasiswa Magang
        Route::get('/logbook/export', [LogbookController::class, 'export'])->name('logbook.export');
        Route::get('/logbook', [LogbookController::class, 'index'])->name('logbook.index');
        Route::get('/logbook/{logbook}', [LogbookController::class, 'show'])->name('logbook.show');
        Route::post('/logbook/{logbook}/verify', [LogbookController::class, 'verify'])->name('logbook.verify');

        // Perizinan
        Route::get('/perizinan', [PerizinanController::class, 'index'])->name('perizinan.index');

        // Presensi
        Route::get('/presensi/export', [PresensiController::class, 'export'])->name('presensi.export');
        Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
        Route::get('/presensi/manual', [PresensiController::class, 'index'])->name('presensi.manual');

        // User Register
        Route::get('/user-register', [UserRegisterController::class, 'index'])->name('user-register.index');

        // Mahasiswa Magang
        Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
        Route::get('/mahasiswa/export', [MahasiswaController::class, 'exportAll'])->name('mahasiswa.export');
        Route::get('/mahasiswa/{user}/laporan', [MahasiswaController::class, 'laporan'])->name('mahasiswa.laporan');
        Route::get('/mahasiswa/{user}/laporan/export', [MahasiswaController::class, 'exportLaporan'])->name('mahasiswa.laporan.export');

        // Permohonan Magang
        Route::get('/permohonan', [PermohonanController::class, 'index'])->name('permohonan.index');
        Route::get('/permohonan/{permohonan}', [PermohonanController::class, 'show'])->name('permohonan.show');
        Route::patch('/permohonan/{permohonan}/status', [PermohonanController::class, 'updateStatus'])->name('permohonan.update-status');
    });
