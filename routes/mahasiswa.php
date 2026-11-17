<?php

use App\Http\Controllers\Mahasiswa\LogbookController;
use App\Http\Controllers\Mahasiswa\PresensiController;
use App\Http\Controllers\Mahasiswa\DashboardController;
use App\Http\Controllers\Mahasiswa\PerizinanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:mahasiswa'])
    ->prefix('mahasiswa')
    ->name('mahasiswa.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Presensi
        Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
        Route::post('/presensi/masuk', [PresensiController::class, 'storeMasuk'])->name('presensi.masuk');
        Route::post('/presensi/keluar', [PresensiController::class, 'storeKeluar'])->name('presensi.keluar');
        Route::post('/presensi/kamera', [PresensiController::class, 'storeKamera'])->name('presensi.kamera');

        // Logbook
        Route::get('/logbook', [LogbookController::class, 'index'])->name('logbook.index');
        Route::get('/logbook/create', [LogbookController::class, 'create'])->name('logbook.create');
        Route::post('/logbook', [LogbookController::class, 'store'])->name('logbook.store');
        Route::get('/logbook/{logbook}/edit', [LogbookController::class, 'edit'])->name('logbook.edit');
        Route::put('/logbook/{logbook}', [LogbookController::class, 'update'])->name('logbook.update');
        Route::delete('/logbook/{logbook}', [LogbookController::class, 'destroy'])->name('logbook.destroy');
        Route::get('/logbook/lanjutkan', [LogbookController::class, 'lanjutkan'])->name('logbook.lanjutkan');

        // Perizinan
        Route::get('/perizinan', [PerizinanController::class, 'index'])->name('perizinan.index');
        Route::post('/perizinan', [PerizinanController::class, 'store'])->name('perizinan.store');
    });
