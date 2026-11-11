@extends('layouts.role', ['role' => 'mahasiswa'])

@section('title', 'Dashboard Mahasiswa')

@section('content')

@php
    /*
    |----------------------------------------------------------------------
    | DATA TURUNAN — dihitung di view agar controller tidak perlu diubah
    |----------------------------------------------------------------------
    */
    $namaDepan = explode(' ', trim($user->name))[0];

    // Sapaan otomatis sesuai jam saat ini
    $jamServer = (int) now()->format('G');
    if ($jamServer >= 4 && $jamServer < 11) {
        $sapaan = 'Selamat pagi';
    } elseif ($jamServer < 15) {
        $sapaan = 'Selamat siang';
    } elseif ($jamServer < 18) {
        $sapaan = 'Selamat sore';
    } else {
        $sapaan = 'Selamat malam';
    }

    // Nama hari & bulan berbahasa Indonesia
    $hariIndo  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $bulanIndo = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    $tglIndo = fn ($tanggal) => $tanggal->format('d') . ' ' . $bulanIndo[(int) $tanggal->format('n')] . ' ' . $tanggal->format('Y');

    // Progres periode magang
    $mulai     = $magang && $magang->tanggal_mulai ? \Carbon\Carbon::parse($magang->tanggal_mulai) : null;
    $selesai   = $magang && $magang->tanggal_selesai ? \Carbon\Carbon::parse($magang->tanggal_selesai) : null;
    $hariIni   = now();
    $totalHari = ($mulai && $selesai) ? (int) $mulai->diffInDays($selesai) + 1 : 0;

    $hariBerjalan = 0;
    if ($hariIni->gte($mulai)) {
        $hariBerjalan = (int) $mulai->diffInDays($hariIni) + 1;
    }
    if ($hariIni->gt($selesai)) {
        $hariBerjalan = $totalHari;
    }
    $hariBerjalan   = max(0, min($hariBerjalan, $totalHari));
    $sisaHari       = max(0, $totalHari - $hariBerjalan);
    $progressMagang = $totalHari > 0 ? (int) round($hariBerjalan / $totalHari * 100) : 0;

    // Status periode magang
    if ($hariIni->lt($mulai)) {
        $statusMagang = ['Belum Dimulai', 'bg-amber-100 text-amber-700'];
    } elseif ($hariIni->gt($selesai)) {
        $statusMagang = ['Periode Selesai', 'bg-gray-200 text-gray-600'];
    } else {
        $statusMagang = ['Sedang Berlangsung', 'bg-emerald-100 text-emerald-700'];
    }

    // Durasi kerja hari ini (muncul otomatis jika jam masuk + jam pulang lengkap)
    $durasiKerja = null;
    if ($presensiHariIni && $presensiHariIni->jam_masuk && $presensiHariIni->jam_pulang) {
        $menit       = (int) abs(\Carbon\Carbon::parse($presensiHariIni->jam_pulang)->diffInMinutes(\Carbon\Carbon::parse($presensiHariIni->jam_masuk)));
        $durasiKerja = intdiv($menit, 60) . ' jam ' . ($menit % 60) . ' menit';
    }

    // Cek ketersediaan route — link otomatis menyesuaikan fitur yang sudah ada
    $adaLogbook = Route::has('mahasiswa.logbook.index');
    $adaIzin    = Route::has('mahasiswa.izin.index');
    $adaRiwayat = Route::has('mahasiswa.presensi.riwayat');
    $adaProfil  = Route::has('mahasiswa.profil');

    // Item menu cepat
    $menuCepat = [
        [
            'judul'  => 'Isi Logbook',
            'desk'   => 'Catat aktivitas harian magang',
            'ikon'   => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
            'ikonBg' => 'bg-green-50 text-green-600',
            'href'   => $adaLogbook ? route('mahasiswa.logbook.index') : '#',
        ],
        [
            'judul'  => 'Ajukan Izin',
            'desk'   => 'Kirim permohonan izin / cuti',
            'ikon'   => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'ikonBg' => 'bg-orange-50 text-orange-600',
            'href'   => $adaIzin ? route('mahasiswa.izin.index') : '#',
        ],
        [
            'judul'  => 'Riwayat Presensi',
            'desk'   => 'Lihat rekap kehadiran Anda',
            'ikon'   => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            'ikonBg' => 'bg-indigo-50 text-indigo-600',
            'href'   => $adaRiwayat ? route('mahasiswa.presensi.riwayat') : '#',
        ],
        [
            'judul'  => 'Profil Saya',
            'desk'   => 'Perbarui data diri & kontak',
            'ikon'   => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
            'ikonBg' => 'bg-purple-50 text-purple-600',
            'href'   => $adaProfil ? route('mahasiswa.profil') : '#',
        ],
    ];
@endphp

{{-- Animasi & efek tambahan --}}
<style>
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes floatY {
        0%, 100% { transform: translateY(0); }
        50%      { transform: translateY(-10px); }
    }
    .anim-fade-up  { animation: fadeUp .55s ease-out both; }
    .anim-delay-1  { animation-delay: .10s; }
    .anim-delay-2  { animation-delay: .20s; }
    .anim-delay-3  { animation-delay: .32s; }
    .anim-float    { animation: floatY 7s ease-in-out infinite; }
</style>

<!-- ================= HERO / SAMBUTAN ================= -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700 shadow-xl shadow-indigo-200/60 p-7 lg:p-9 mb-8 anim-fade-up">
    <!-- Dekorasi latar -->
    <div class="absolute -top-20 -right-16 w-72 h-72 bg-white/10 rounded-full blur-2xl"></div>
    <div class="absolute -bottom-24 -left-12 w-64 h-64 bg-fuchsia-400/20 rounded-full blur-2xl anim-float"></div>
    <div class="absolute top-8 right-1/3 w-24 h-24 bg-indigo-400/30 rounded-full blur-xl"></div>

    <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
        <!-- Sapaan -->
        <div>
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/15 text-white text-xs font-semibold backdrop-blur-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Status Magang: {{ $statusMagang[0] }}
            </span>
            <h2 class="mt-4 text-2xl lg:text-3xl font-extrabold text-white leading-snug">
                {{ $sapaan }}, {{ $namaDepan }}! 👋
            </h2>
            <p class="text-indigo-100 mt-2 text-sm lg:text-base">
                Semangat menjalani aktivitas magang hari ini di BPS Kota Surabaya. Jangan lupa absen &amp; isi logbook ya!
            </p>
            <div class="flex flex-wrap gap-2 mt-5">
                <span class="px-3 py-1.5 rounded-lg bg-white/10 text-white text-xs font-medium">📅 Hari ke-{{ $hariBerjalan }} dari {{ $totalHari }} hari</span>
                <span class="px-3 py-1.5 rounded-lg bg-white/10 text-white text-xs font-medium">⏳ Sisa {{ $sisaHari }} hari lagi</span>
                <span class="px-3 py-1.5 rounded-lg bg-white/10 text-white text-xs font-medium">🎯 Progres {{ $progressMagang }}%</span>
            </div>
        </div>

        <!-- Jam Live -->
        <div class="lg:text-right">
            <p class="text-indigo-200 text-[11px] font-semibold uppercase tracking-[0.2em]">Waktu Indonesia Barat</p>
            <p id="liveClock" class="mt-1 text-4xl lg:text-5xl font-extrabold text-white tabular-nums">--:--:--</p>
            <p class="text-indigo-100 text-sm mt-2">
                {{ $hariIndo[(int) $hariIni->format('w')] }}, {{ $tglIndo($hariIni) }}
            </p>
        </div>
    </div>
</div>

<!-- ================= PROFIL + PRESENSI ================= -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Profil Singkat -->
    <div class="anim-fade-up anim-delay-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col lg:col-span-1">
        <div class="flex items-center gap-4 mb-5">
            <div class="w-16 h-16 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-indigo-200">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800 leading-tight">{{ $user->name }}</h3>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700 mt-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Mahasiswa Magang
                </span>
            </div>
        </div>

        <div class="space-y-3 text-sm">
            <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-3">
                <span class="text-gray-500 flex items-center gap-2">✉️ Email</span>
                <span class="font-medium text-gray-800 text-right break-all">{{ $user->email }}</span>
            </div>
            <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-3">
                <span class="text-gray-500 flex items-center gap-2">📱 No. HP</span>
                <span class="font-medium text-gray-800">{{ $user->mahasiswaProfile->no_hp ?? '-' }}</span>
            </div>
            <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-3">
                <span class="text-gray-500 flex items-center gap-2">🏢 Lokasi Magang</span>
                <span class="font-medium text-gray-800 text-right">BPS Kota Surabaya</span>
            </div>
            <div class="flex items-start justify-between gap-4 pt-1">
                <span class="text-gray-500 flex items-center gap-2">📆 Periode Magang</span>
                <span class="font-medium text-gray-800 text-right">
                    {{ $tglIndo($mulai) }}<br>
                    <span class="text-xs text-gray-400">s/d</span><br>
                    {{ $tglIndo($selesai) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Kartu Presensi -->
    <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Tombol / Status Masuk -->
        <div class="anim-fade-up anim-delay-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col relative overflow-hidden group hover:shadow-lg transition-shadow duration-300">
            <div class="absolute -right-5 -top-5 w-28 h-28 bg-green-50 rounded-full opacity-60 group-hover:scale-125 transition-transform duration-500"></div>

            <div class="relative flex items-center gap-3 mb-1">
                <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Presensi Masuk</h3>
                    <p class="text-xs text-gray-500">Catat kehadiran saat tiba di kantor.</p>
                </div>
            </div>

            <div class="relative mt-auto pt-6">
                @if($presensiHariIni && $presensiHariIni->jam_masuk)
                    {{-- Sudah absen masuk: tampilkan informasi singkat --}}
                    <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-xl flex items-center gap-3">
                        <svg class="w-8 h-8 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <p class="font-semibold text-sm">✅ Tercatat Masuk</p>
                            <p class="text-xs mt-0.5">Anda sudah absen masuk pukul <b>{{ $presensiHariIni->jam_masuk }}</b>. Jangan lupa isi logbook sebelum pulang ya!</p>
                        </div>
                    </div>
                @else
                    {{-- Belum absen masuk: tampilkan tombol --}}
                    <button type="button" class="w-full py-4 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold rounded-xl transition-all duration-300 shadow-md shadow-green-200 hover:shadow-lg hover:shadow-green-300 hover:-translate-y-0.5 flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        Absen Masuk Sekarang
                    </button>
                    <p class="text-[11px] text-gray-400 text-center mt-2">Pastikan Anda berada di area kantor saat menekan tombol ini.</p>
                @endif
            </div>
        </div>

        <!-- Tombol / Status Keluar -->
        <div class="anim-fade-up anim-delay-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col relative overflow-hidden group hover:shadow-lg transition-shadow duration-300">
            <div class="absolute -right-5 -top-5 w-28 h-28 bg-blue-50 rounded-full opacity-60 group-hover:scale-125 transition-transform duration-500"></div>

            <div class="relative flex items-center gap-3 mb-1">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Presensi Keluar</h3>
                    <p class="text-xs text-gray-500">Catat kehadiran saat jam kerja berakhir.</p>
                </div>
            </div>

            <div class="relative mt-auto pt-6">
                @if($presensiHariIni && $presensiHariIni->jam_pulang)
                    {{-- Sudah absen keluar: tampilkan informasi singkat + durasi kerja --}}
                    <div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl flex items-center gap-3">
                        <svg class="w-8 h-8 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <p class="font-semibold text-sm">✅ Tercatat Keluar</p>
                            <p class="text-xs mt-0.5">
                                Anda absen pulang pukul <b>{{ $presensiHariIni->jam_pulang }}</b>
                                @if($durasiKerja)
                                    · Durasi kerja hari ini: <b>{{ $durasiKerja }}</b>
                                @endif
                                . Kerja bagus, sampai jumpa besok! 👍
                            </p>
                        </div>
                    </div>
                @elseif(!$presensiHariIni || !$presensiHariIni->jam_masuk)
                    {{-- Belum absen masuk --}}
                    <div class="bg-gray-50 border border-gray-200 text-gray-500 p-4 rounded-xl text-center text-sm font-medium">
                        Anda belum absen masuk hari ini.
                        <p class="text-xs font-normal mt-1">Lakukan presensi masuk terlebih dahulu.</p>
                    </div>
                @elseif(!$logbookHariIni)
                    {{-- Belum isi logbook: wajib dulu sebelum keluar --}}
                    <div class="bg-orange-50 border border-orange-200 text-orange-800 p-4 rounded-xl flex items-center gap-3">
                        <svg class="w-8 h-8 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <div>
                            <p class="font-semibold text-sm">Isi Logbook Dulu</p>
                            <p class="text-xs mt-0.5">Anda wajib mengisi logbook sebelum absen keluar.</p>
                            @if($adaLogbook)
                                <a href="{{ route('mahasiswa.logbook.index') }}" class="inline-flex items-center gap-1 mt-2 text-xs font-bold text-orange-700 underline underline-offset-2 hover:text-orange-900">
                                    Isi Logbook Sekarang →
                                </a>
                            @endif
                        </div>
                    </div>
                @else
                    {{-- Semua syarat terpenuhi: tampilkan tombol --}}
                    <button type="button" class="w-full py-4 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold rounded-xl transition-all duration-300 shadow-md shadow-blue-200 hover:shadow-lg hover:shadow-blue-300 hover:-translate-y-0.5 flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        Absen Keluar Sekarang
                    </button>
                    <p class="text-[11px] text-gray-400 text-center mt-2">Logbook hari ini sudah terisi. Terima kasih! 🙌</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ================= STATISTIK ================= -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8 anim-fade-up anim-delay-2">
    <!-- Total Presensi -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium">Total Presensi</p>
            <h4 class="text-2xl font-extrabold text-gray-800">{{ $totalPresensi }}</h4>
            <p class="text-[11px] text-gray-400">hari kehadiran tercatat</p>
        </div>
    </div>

    <!-- Total Logbook -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
        <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium">Total Logbook</p>
            <h4 class="text-2xl font-extrabold text-gray-800">{{ $totalLogbook }}</h4>
            <p class="text-[11px] text-gray-400">entri aktivitas harian</p>
        </div>
    </div>

    <!-- Total Izin -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium">Total Izin</p>
            <h4 class="text-2xl font-extrabold text-gray-800">{{ $totalIzin }}</h4>
            <p class="text-[11px] text-gray-400">pengajuan izin</p>
        </div>
    </div>

    <!-- Rata-rata Masuk -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
        <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium">Rata-rata Masuk</p>
            <h4 class="text-2xl font-extrabold text-gray-800">{{ $rataRataFormat }}</h4>
            <p class="text-[11px] text-gray-400">jam kehadiran harian</p>
        </div>
    </div>
</div>

<!-- ================= PROGRES MAGANG + MENU CEPAT ================= -->
<div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-8 anim-fade-up anim-delay-3">
    <!-- Progres Magang -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">📈 Progres Magang</h3>
            <span class="text-2xl font-extrabold text-indigo-600">{{ $progressMagang }}%</span>
        </div>

        <div class="w-full bg-indigo-100 rounded-full h-3 overflow-hidden">
            <div class="h-3 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 transition-all duration-700" style="width: {{ $progressMagang }}%;"></div>
        </div>
        <div class="flex justify-between mt-2 text-xs text-gray-400">
            <span>Mulai: {{ $tglIndo($mulai) }}</span>
            <span>Selesai: {{ $tglIndo($selesai) }}</span>
        </div>

        <div class="grid grid-cols-2 gap-3 mt-5">
            <div class="bg-indigo-50 rounded-xl p-3 text-center">
                <p class="text-xs text-gray-500">Hari Berjalan</p>
                <p class="text-lg font-bold text-indigo-700">{{ $hariBerjalan }} hari</p>
            </div>
            <div class="bg-purple-50 rounded-xl p-3 text-center">
                <p class="text-xs text-gray-500">Sisa Hari</p>
                <p class="text-lg font-bold text-purple-700">{{ $sisaHari }} hari</p>
            </div>
        </div>
    </div>

    <!-- Menu Cepat -->
    <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4">⚡ Menu Cepat</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach ($menuCepat as $menu)
                <a href="{{ $menu['href'] }}" class="flex items-center gap-3 p-4 rounded-xl border border-gray-100 hover:border-indigo-200 hover:bg-indigo-50/50 hover:-translate-y-0.5 transition-all duration-300 group">
                    <div class="w-11 h-11 rounded-xl {{ $menu['ikonBg'] }} flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['ikon'] }}"></path></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm text-gray-800">{{ $menu['judul'] }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $menu['desk'] }}</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-indigo-500 group-hover:translate-x-1 transition-all duration-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            @endforeach
        </div>
    </div>
</div>

<!-- ================= PENGINGAT ================= -->
<div class="anim-fade-up anim-delay-3 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-100 rounded-2xl p-5 flex items-start gap-3">
    <span class="text-xl leading-none">💡</span>
    <p class="text-sm text-amber-800">
        <b>Pengingat:</b> Logbook wajib diisi setiap hari sebelum menekan tombol <b>Absen Keluar</b>.
        Lengkapi aktivitas Anda hari ini agar presensi pulang tidak terhambat.
    </p>
</div>

<!-- ================= LOGBOOK TERAKHIR (OPSIONAL) =================
     Bagian ini otomatis muncul setelah fitur logbook selesai dibuat dan
     controller mengirim variabel $logbookTerbaru (koleksi 5 data terbaru). -->
@isset($logbookTerbaru)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-8 anim-fade-up">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">📝 Logbook Terakhir</h3>
            @if($adaLogbook)
                <a href="{{ route('mahasiswa.logbook.index') }}" class="text-sm text-indigo-600 font-medium hover:text-indigo-800">Lihat semua →</a>
            @endif
        </div>
        <div class="space-y-3">
            @forelse($logbookTerbaru as $item)
                <div class="flex items-start gap-3 p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="w-9 h-9 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0 text-xs font-bold">
                        {{ optional($item->created_at)->format('d') ?? '-' }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 line-clamp-2">{{ $item->kegiatan ?? $item->deskripsi ?? '-' }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ optional($item->created_at)->translatedFormat('d F Y') }}</p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 text-center py-4">Belum ada logbook. Mulai catat aktivitasmu hari ini! 🚀</p>
            @endforelse
        </div>
    </div>
@endisset

{{-- Jam live --}}
<script>
    (function () {
        var el = document.getElementById('liveClock');
        if (!el) return;
        function tick() {
            try {
                el.textContent = new Date().toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    timeZone: 'Asia/Jakarta'
                });
            } catch (e) {
                el.textContent = new Date().toLocaleTimeString('id-ID');
            }
        }
        tick();
        setInterval(tick, 1000);
    })();
</script>

@endsection