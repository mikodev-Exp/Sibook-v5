@extends('layouts.role', ['role' => 'mahasiswa'])

@section('title', 'Dashboard Mahasiswa')

@section('content')

@php
    $namaDepan = explode(' ', trim($user->name))[0];

    // Sapaan otomatis sesuai jam
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

    $hariIndo  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $bulanIndo = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $tglIndo = fn ($tanggal) => $tanggal ? $tanggal->format('d') . ' ' . $bulanIndo[(int) $tanggal->format('n')] . ' ' . $tanggal->format('Y') : '-';

    // Progres periode magang
    $mulai     = $magang && $magang->tanggal_mulai ? \Carbon\Carbon::parse($magang->tanggal_mulai) : null;
    $selesai   = $magang && $magang->tanggal_selesai ? \Carbon\Carbon::parse($magang->tanggal_selesai) : null;
    $hariIni   = now();
    $totalHari = ($mulai && $selesai) ? (int) $mulai->diffInDays($selesai) + 1 : 0;

    $hariBerjalan = 0;
    if ($mulai && $hariIni->gte($mulai)) {
        $hariBerjalan = (int) $mulai->diffInDays($hariIni) + 1;
    }
    if ($selesai && $hariIni->gt($selesai)) {
        $hariBerjalan = $totalHari;
    }
    $hariBerjalan   = max(0, min($hariBerjalan, $totalHari));
    $sisaHari       = max(0, $totalHari - $hariBerjalan);
    $progressMagang = $totalHari > 0 ? (int) round($hariBerjalan / $totalHari * 100) : 0;

    if ($mulai && $hariIni->lt($mulai)) {
        $statusMagang = ['Belum Dimulai', 'bg-amber-100 text-amber-800 border-amber-200'];
    } elseif ($selesai && $hariIni->gt($selesai)) {
        $statusMagang = ['Periode Selesai', 'bg-gray-100 text-gray-700 border-gray-200'];
    } else {
        $statusMagang = ['Sedang Berlangsung', 'bg-emerald-100 text-emerald-800 border-emerald-200'];
    }

    $durasiKerja = null;
    if ($presensiHariIni && $presensiHariIni->jam_masuk && $presensiHariIni->jam_pulang) {
        $menit       = (int) abs(\Carbon\Carbon::parse($presensiHariIni->jam_pulang)->diffInMinutes(\Carbon\Carbon::parse($presensiHariIni->jam_masuk)));
        $durasiKerja = intdiv($menit, 60) . ' jam ' . ($menit % 60) . ' menit';
    }

    $menuCepat = [
        [
            'judul'  => 'Presensi Harian',
            'desk'   => 'Lakukan absen masuk / pulang',
            'ikon'   => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            'ikonBg' => 'bg-orange-50 text-orange-600',
            'href'   => route('mahasiswa.presensi.index'),
        ],
        [
            'judul'  => 'Isi Logbook',
            'desk'   => 'Catat aktivitas harian magang',
            'ikon'   => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
            'ikonBg' => 'bg-emerald-50 text-emerald-600',
            'href'   => route('mahasiswa.logbook.index'),
        ],
        [
            'judul'  => 'Pengajuan Izin',
            'desk'   => 'Permohonan izin atau sakit',
            'ikon'   => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'ikonBg' => 'bg-blue-50 text-blue-600',
            'href'   => route('mahasiswa.perizinan.index'),
        ],
        [
            'judul'  => 'Profil Saya',
            'desk'   => 'Perbarui data diri dan kontak',
            'ikon'   => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
            'ikonBg' => 'bg-purple-50 text-purple-600',
            'href'   => route('profile.edit'),
        ],
    ];
@endphp

<div class="max-w-6xl mx-auto space-y-6">

    {{-- Hero Card Modern --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-orange-600 via-orange-500 to-amber-500 shadow-sm p-6 lg:p-8 text-white">
        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-6 z-10">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-300"></span>
                    <span>Status Magang: {{ $statusMagang[0] }}</span>
                </div>
                <h1 class="mt-3 text-2xl lg:text-3xl font-extrabold text-white tracking-tight">
                    {{ $sapaan }}, {{ $namaDepan }}
                </h1>
                <p class="text-orange-100 text-xs sm:text-sm mt-1 max-w-xl">
                    Semangat menjalani aktivitas magang di BPS Kota Surabaya. Jangan lupa melengkapi presensi dan mengisi logbook harian Anda.
                </p>

                <div class="flex flex-wrap items-center gap-2 mt-4 text-xs">
                    <span class="px-3 py-1.5 rounded-lg bg-white/15 text-white font-medium">
                        Hari ke-{{ $hariBerjalan }} dari {{ $totalHari }} hari
                    </span>
                    <span class="px-3 py-1.5 rounded-lg bg-white/15 text-white font-medium">
                        Sisa {{ $sisaHari }} hari
                    </span>
                    <span class="px-3 py-1.5 rounded-lg bg-white/15 text-white font-medium">
                        Progres {{ $progressMagang }}%
                    </span>
                </div>
            </div>

            <div class="md:text-right bg-white/10 md:bg-transparent p-4 md:p-0 rounded-xl backdrop-blur-sm md:backdrop-blur-none">
                <p class="text-orange-100 text-[11px] font-semibold uppercase tracking-wider">Waktu Indonesia Barat</p>
                <div id="liveClock" class="mt-1 text-3xl lg:text-4xl font-extrabold text-white tabular-nums tracking-wide">--:--:--</div>
                <p class="text-orange-100 text-xs mt-1">
                    {{ $hariIndo[(int) $hariIni->format('w')] }}, {{ $tglIndo($hariIni) }}
                </p>
            </div>
        </div>
    </div>

    {{-- Grid: Profil Ringkas + Status Presensi Hari Ini --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Profil Singkat --}}
        <div class="bg-white rounded-2xl border border-gray-150 p-6 shadow-[0_1px_3px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3.5 mb-5 pb-4 border-b border-gray-100">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 font-extrabold text-base flex items-center justify-center shrink-0">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm font-bold text-gray-900 truncate">{{ $user->name }}</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10.5px] font-semibold bg-gray-100 text-gray-700 mt-0.5">
                            Mahasiswa Magang
                        </span>
                    </div>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-gray-400 font-medium">Email:</span>
                        <span class="font-semibold text-gray-800 text-right truncate">{{ $user->email }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-gray-400 font-medium">Institusi:</span>
                        <span class="font-semibold text-gray-800 text-right">{{ $user->mahasiswaProfile->universitas ?? '-' }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-gray-400 font-medium">Jurusan:</span>
                        <span class="font-semibold text-gray-800 text-right">{{ $user->mahasiswaProfile->jurusan ?? ($user->mahasiswaProfile->program_studi ?? '-') }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-gray-400 font-medium">Lokasi:</span>
                        <span class="font-semibold text-gray-800 text-right">BPS Kota Surabaya</span>
                    </div>
                    <div class="flex items-start justify-between gap-2 pt-2 border-t border-gray-100">
                        <span class="text-gray-400 font-medium">Periode:</span>
                        <span class="font-semibold text-gray-800 text-right">
                            {{ $mulai ? $mulai->translatedFormat('d M Y') : '-' }} s/d {{ $selesai ? $selesai->translatedFormat('d M Y') : '-' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="pt-4 mt-4 border-t border-gray-100">
                <a href="{{ route('profile.edit') }}" class="text-xs font-semibold text-orange-600 hover:text-orange-700 flex items-center justify-between">
                    <span>Kelola Profil Akun</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- Status Presensi Masuk & Keluar Hari Ini --}}
        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- Kartu Presensi Masuk --}}
            <div class="bg-white rounded-2xl border border-gray-150 p-6 shadow-[0_1px_3px_rgba(0,0,0,0.02)] flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-400">Batas: 07:30 WIB</span>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm">Presensi Masuk</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Catat kehadiran saat tiba di kantor.</p>
                </div>

                <div class="mt-6">
                    @if($presensiHariIni && $presensiHariIni->jam_masuk)
                        <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span class="text-xs font-bold text-emerald-800">Tercatat Masuk</span>
                            </div>
                            <p class="text-xs text-emerald-700 mt-1 font-semibold">Pukul {{ $presensiHariIni->jam_masuk }} WIB</p>
                        </div>
                    @else
                        <a href="{{ route('mahasiswa.presensi.index') }}"
                           class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Lakukan Presensi Masuk</span>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Kartu Presensi Keluar --}}
            <div class="bg-white rounded-2xl border border-gray-150 p-6 shadow-[0_1px_3px_rgba(0,0,0,0.02)] flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-400">Batas: 16:00 WIB</span>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm">Presensi Keluar</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Catat kepulangan saat jam kerja berakhir.</p>
                </div>

                <div class="mt-6">
                    @if($presensiHariIni && $presensiHariIni->jam_pulang)
                        <div class="p-3 bg-blue-50 border border-blue-200 rounded-xl">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                <span class="text-xs font-bold text-blue-800">Tercatat Keluar</span>
                            </div>
                            <p class="text-xs text-blue-700 mt-1 font-semibold">
                                Pukul {{ $presensiHariIni->jam_pulang }} WIB
                                @if($durasiKerja)
                                    <span class="text-[11px] font-normal text-blue-600">({{ $durasiKerja }})</span>
                                @endif
                            </p>
                        </div>
                    @elseif(!$presensiHariIni || !$presensiHariIni->jam_masuk)
                        <div class="p-2.5 bg-gray-50 border border-gray-150 rounded-xl text-center">
                            <p class="text-[11px] text-gray-500">Belum presensi masuk hari ini</p>
                        </div>
                    @elseif(!$logbookHariIni)
                        <a href="{{ route('mahasiswa.logbook.index') }}"
                           class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 px-4 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>Isi Logbook Dulu</span>
                        </a>
                    @else
                        <a href="{{ route('mahasiswa.presensi.index') }}"
                           class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                            </svg>
                            <span>Lakukan Presensi Keluar</span>
                        </a>
                    @endif
                </div>
            </div>

        </div>

    </div>

    {{-- 4 Stat KPI Minimalis --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Presensi --}}
        <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-[0_1px_3px_rgba(0,0,0,0.02)] flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-gray-400">Total Presensi</p>
                <p class="text-xl font-extrabold text-gray-900 mt-0.5">{{ $totalPresensi }}</p>
                <p class="text-[10.5px] text-gray-400">hari hadir</p>
            </div>
        </div>

        {{-- Total Logbook --}}
        <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-[0_1px_3px_rgba(0,0,0,0.02)] flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-gray-400">Total Logbook</p>
                <p class="text-xl font-extrabold text-gray-900 mt-0.5">{{ $totalLogbook }}</p>
                <p class="text-[10.5px] text-gray-400">aktivitas tercatat</p>
            </div>
        </div>

        {{-- Total Izin --}}
        <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-[0_1px_3px_rgba(0,0,0,0.02)] flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-gray-400">Izin Disetujui</p>
                <p class="text-xl font-extrabold text-gray-900 mt-0.5">{{ $totalIzin }}</p>
                <p class="text-[10.5px] text-gray-400">pengajuan izin</p>
            </div>
        </div>

        {{-- Rata-rata Masuk --}}
        <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-[0_1px_3px_rgba(0,0,0,0.02)] flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-gray-400">Rata-rata Masuk</p>
                <p class="text-xl font-extrabold text-gray-900 mt-0.5">{{ $rataRataFormat }}</p>
                <p class="text-[10.5px] text-gray-400">WIB</p>
            </div>
        </div>
    </div>

    {{-- Menu Akses Cepat --}}
    <div class="bg-white rounded-2xl border border-gray-150 p-6 shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
        <h2 class="text-sm font-bold text-gray-900 mb-4">Akses Cepat</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
            @foreach ($menuCepat as $menu)
                <a href="{{ $menu['href'] }}" class="flex items-center gap-3 p-3.5 rounded-xl border border-gray-100 hover:border-orange-200 hover:bg-orange-50/40 transition group">
                    <div class="w-10 h-10 rounded-xl {{ $menu['ikonBg'] }} flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $menu['ikon'] }}"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-gray-900 group-hover:text-orange-600 transition">{{ $menu['judul'] }}</p>
                        <p class="text-[11px] text-gray-400 truncate">{{ $menu['desk'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Logbook Terbaru --}}
    @if(isset($logbookTerbaru) && $logbookTerbaru->isNotEmpty())
        <div class="bg-white rounded-2xl border border-gray-150 p-6 shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Aktivitas Logbook Terbaru</h2>
                    <p class="text-[11px] text-gray-400">Catatan kegiatan harian terakhir Anda</p>
                </div>
                <a href="{{ route('mahasiswa.logbook.index') }}" class="text-xs font-semibold text-orange-600 hover:text-orange-700">
                    Lihat Semua
                </a>
            </div>

            <div class="divide-y divide-gray-100">
                @foreach($logbookTerbaru as $lb)
                    <div class="py-3 flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-gray-800 truncate">{{ $lb->kegiatan }}</span>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-gray-100 text-gray-600">
                                    {{ $lb->tim }}
                                </span>
                            </div>
                            <p class="text-[11px] text-gray-400 mt-0.5 truncate">{{ $lb->deskripsi }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-xs font-medium text-gray-600">{{ \Carbon\Carbon::parse($lb->tanggal)->isoFormat('D MMM Y') }}</span>
                            <div class="mt-0.5">
                                @if($lb->status === \App\Enums\StatusLogbook::Draft || $lb->status === 'draft')
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">Draft</span>
                                @elseif($lb->status === \App\Enums\StatusLogbook::Approved || $lb->status === 'approved')
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Disetujui</span>
                                @else
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">Terkirim</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

<script>
    function updateClock() {
        const el = document.getElementById('liveClock');
        if (!el) return;
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        el.textContent = `${h}:${m}:${s}`;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endsection
