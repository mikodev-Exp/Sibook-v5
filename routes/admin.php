<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LogbookController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\PermohonanController;
use App\Http\Controllers\Admin\PresensiController;
use App\Http\Controllers\Admin\PerizinanController;
use App\Http\Controllers\Admin\PersyaratanMagangController;
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
        Route::patch('/perizinan/{perizinan}/status', [PerizinanController::class, 'updateStatus'])->name('perizinan.update-status');

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
        Route::patch('/mahasiswa/{user}/periode', [MahasiswaController::class, 'updatePeriode'])->name('mahasiswa.update-periode');

        // Permohonan Magang
        Route::get('/permohonan', [PermohonanController::class, 'index'])->name('permohonan.index');
        Route::get('/permohonan/{permohonan}', [PermohonanController::class, 'show'])->name('permohonan.show');
        Route::get('/permohonan/{permohonan}/dokumen/{dokumen}', [PermohonanController::class, 'dokumen'])->name('permohonan.dokumen');
        Route::patch('/permohonan/{permohonan}/status', [PermohonanController::class, 'updateStatus'])->name('permohonan.update-status');

        // Persyaratan & Kuota Magang
        Route::get('/persyaratan-magang', [PersyaratanMagangController::class, 'index'])->name('persyaratan-magang.index');
        Route::put('/persyaratan-magang/pengaturan', [PersyaratanMagangController::class, 'updatePengaturan'])->name('persyaratan-magang.pengaturan.update');
        Route::put('/persyaratan-magang/tautan', [PersyaratanMagangController::class, 'updateTautan'])->name('persyaratan-magang.tautan.update');
        Route::post('/persyaratan-magang/kelompok', [PersyaratanMagangController::class, 'storeKelompok'])->name('persyaratan-magang.kelompok.store');
        Route::put('/persyaratan-magang/kelompok/{group}', [PersyaratanMagangController::class, 'updateKelompok'])->name('persyaratan-magang.kelompok.update');
        Route::delete('/persyaratan-magang/kelompok/{group}', [PersyaratanMagangController::class, 'destroyKelompok'])->name('persyaratan-magang.kelompok.destroy');
        Route::post('/persyaratan-magang/kelompok/{group}/aktif', [PersyaratanMagangController::class, 'quickActive'])->name('persyaratan-magang.kelompok.aktif');
        Route::post('/persyaratan-magang/jurusan', [PersyaratanMagangController::class, 'storeJurusan'])->name('persyaratan-magang.jurusan.store');
        Route::put('/persyaratan-magang/jurusan/{major}', [PersyaratanMagangController::class, 'updateJurusan'])->name('persyaratan-magang.jurusan.update');
        Route::delete('/persyaratan-magang/jurusan/{major}', [PersyaratanMagangController::class, 'destroyJurusan'])->name('persyaratan-magang.jurusan.destroy');
        Route::post('/persyaratan-magang/jurusan/pindah', [PersyaratanMagangController::class, 'pindahJurusan'])->name('persyaratan-magang.jurusan.pindah');

        // Alias rute lama requirement-magang untuk kompatibilitas
        Route::get('/requirement-magang', [PersyaratanMagangController::class, 'index'])->name('requirement-magang.index');
        Route::put('/requirement-magang/settings', [PersyaratanMagangController::class, 'updateSettings'])->name('requirement-magang.settings.update');
        Route::put('/requirement-magang/links', [PersyaratanMagangController::class, 'updateLinks'])->name('requirement-magang.links.update');
        Route::post('/requirement-magang/groups', [PersyaratanMagangController::class, 'storeGroup'])->name('requirement-magang.groups.store');
        Route::put('/requirement-magang/groups/{group}', [PersyaratanMagangController::class, 'updateGroup'])->name('requirement-magang.groups.update');
        Route::delete('/requirement-magang/groups/{group}', [PersyaratanMagangController::class, 'destroyGroup'])->name('requirement-magang.groups.destroy');
        Route::post('/requirement-magang/groups/{group}/active', [PersyaratanMagangController::class, 'quickActive'])->name('requirement-magang.groups.active');
        Route::post('/requirement-magang/majors', [PersyaratanMagangController::class, 'storeMajor'])->name('requirement-magang.majors.store');
        Route::put('/requirement-magang/majors/{major}', [PersyaratanMagangController::class, 'updateMajor'])->name('requirement-magang.majors.update');
        Route::delete('/requirement-magang/majors/{major}', [PersyaratanMagangController::class, 'destroyMajor'])->name('requirement-magang.majors.destroy');
        Route::post('/requirement-magang/majors/reorder', [PersyaratanMagangController::class, 'reorderMajors'])->name('requirement-magang.majors.reorder');
    });
