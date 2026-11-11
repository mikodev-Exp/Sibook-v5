<?php
use App\Http\Controllers\Mahasiswa\PresensiController;
use App\Http\Controllers\Mahasiswa\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:mahasiswa'])
    ->prefix('mahasiswa')
    ->name('mahasiswa.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Route Presensi dipindah ke dalam group agar terlindungi middleware
        Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
        Route::post('/presensi/masuk', [PresensiController::class, 'storeMasuk'])->name('presensi.masuk');
        Route::post('/presensi/keluar', [PresensiController::class, 'storeKeluar'])->name('presensi.keluar');
        Route::post('/presensi/kamera', [PresensiController::class, 'storeKamera'])->name('presensi.kamera');
    });