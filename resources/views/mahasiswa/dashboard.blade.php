@extends('layouts.role', ['role' => 'mahasiswa'])

@section('title', 'Creator Studio - Dashboard')

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
        $statusMagang = ['Belum Dimulai', 'bg-amber-50 text-amber-700 border-amber-200'];
    } elseif ($selesai && $hariIni->gt($selesai)) {
        $statusMagang = ['Periode Selesai', 'bg-zinc-100 text-zinc-700 border-zinc-200'];
    } else {
        $statusMagang = ['Sedang Berlangsung', 'bg-emerald-50 text-emerald-700 border-emerald-200'];
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
            'href'   => route('mahasiswa.presensi.index'),
            'tag'    => 'CHECK-IN',
        ],
        [
            'judul'  => 'Catatan Logbook',
            'desk'   => 'Rekam aktivitas karya harian',
            'ikon'   => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
            'href'   => route('mahasiswa.logbook.index'),
            'tag'    => 'LEDGER',
        ],
        [
            'judul'  => 'Permohonan Izin',
            'desk'   => 'Pengajuan pass izin / sakit',
            'ikon'   => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'href'   => route('mahasiswa.perizinan.index'),
            'tag'    => 'LEAVE PASS',
        ],
        [
            'judul'  => 'Profil Peserta',
            'desk'   => 'Kelola identitas dan akun',
            'ikon'   => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
            'href'   => route('profile.edit'),
            'tag'    => 'PROFILE',
        ],
    ];
@endphp

<div class="max-w-6xl mx-auto space-y-8">

    {{-- Hero Exhibition Placard (Obsidian Noir) --}}
    <div class="relative overflow-hidden rounded-3xl bg-zinc-950 text-white border border-zinc-800 p-8 lg:p-10 shadow-2xl">
        {{-- Subtle Ambient Glow --}}
        <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full bg-emerald-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-8 z-10">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-zinc-900 border border-zinc-800 text-[11px] font-mono-tag text-zinc-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>STUDIO STATUS: {{ strtoupper($statusMagang[0]) }}</span>
                </div>

                <h1 class="mt-4 text-3xl sm:text-4xl font-extrabold text-zinc-100 tracking-tight">
                    {{ $sapaan }}, <span class="text-white">{{ $namaDepan }}</span>
                </h1>

                <p class="text-zinc-400 text-xs sm:text-sm mt-2 max-w-xl leading-relaxed">
                    Selamat datang di Creator Studio BPS Kota Surabaya. Pantau alokasi waktu kerja, presensi harian berbasis geolokasi, dan kurasi catatan logbook Anda.
                </p>

                <div class="flex flex-wrap items-center gap-2.5 mt-6 font-mono-tag text-xs">
                    <span class="px-3.5 py-1.5 rounded-xl bg-zinc-900/90 border border-zinc-800 text-zinc-200">
                        HARI {{ $hariBerjalan }} / {{ $totalHari }}
                    </span>
                    <span class="px-3.5 py-1.5 rounded-xl bg-zinc-900/90 border border-zinc-800 text-zinc-300">
                        SISA {{ $sisaHari }} HARI
                    </span>
                    <span class="px-3.5 py-1.5 rounded-xl bg-emerald-950/60 border border-emerald-800/40 text-emerald-300">
                        PROGRESS {{ $progressMagang }}%
                    </span>
                </div>
            </div>

            <div class="md:text-right bg-zinc-900/60 border border-zinc-800 p-6 rounded-2xl font-mono-tag backdrop-blur-sm min-w-[220px]">
                <p class="text-[10px] text-zinc-400 uppercase tracking-widest font-semibold">WAKTU SERVER [WIB]</p>
                <div id="liveClock" class="mt-1 text-3xl sm:text-4xl font-extrabold text-white tabular-nums tracking-wide">--:--:--</div>
                <p class="text-zinc-400 text-xs mt-2">
                    {{ $hariIndo[(int) $hariIni->format('w')] }}, {{ $tglIndo($hariIni) }}
                </p>
            </div>
        </div>

        {{-- Progress Bar Tracker --}}
        <div class="mt-8 pt-6 border-t border-zinc-900">
            <div class="flex items-center justify-between text-xs font-mono-tag text-zinc-400 mb-2">
                <span>TIMELINE PERIODE MAGANG</span>
                <span>{{ $progressMagang }}% SELESAI</span>
            </div>
            <div class="w-full bg-zinc-900 h-2 rounded-full overflow-hidden p-0.5 border border-zinc-800">
                <div class="bg-white h-full rounded-full transition-all duration-700" style="width: {{ $progressMagang }}%"></div>
            </div>
        </div>
    </div>

    {{-- Grid: Profil Ringkas + Status Presensi Hari Ini --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Profil Singkat (Artist Placard) --}}
        <div class="bg-white rounded-3xl border border-zinc-200/80 p-7 shadow-sm flex flex-col justify-between hover:border-zinc-300 transition-colors">
            <div>
                <div class="flex items-center gap-3.5 mb-6 pb-5 border-b border-zinc-100">
                    <div class="w-12 h-12 rounded-2xl bg-zinc-950 text-white font-mono-tag font-bold text-sm flex items-center justify-center shrink-0 shadow-sm">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-widest block mb-0.5">ARTIST / INTERN</span>
                        <h3 class="text-sm font-bold text-zinc-950 truncate">{{ $user->name }}</h3>
                        <span class="inline-flex items-center gap-1 text-[11px] font-mono-tag text-emerald-600 font-semibold mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            ACTIVE MEMBER
                        </span>
                    </div>
                </div>

                <div class="space-y-3.5 text-xs font-mono-tag">
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-zinc-400">EMAIL</span>
                        <span class="font-bold text-zinc-900 text-right truncate max-w-[180px]">{{ $user->email }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-zinc-400">KAMPUS</span>
                        <span class="font-semibold text-zinc-800 text-right truncate max-w-[180px]">{{ $user->mahasiswaProfile->universitas ?? '-' }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-zinc-400">JURUSAN</span>
                        <span class="font-semibold text-zinc-800 text-right truncate max-w-[180px]">{{ $user->mahasiswaProfile->jurusan ?? ($user->mahasiswaProfile->program_studi ?? '-') }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-zinc-400">INSTITUSI</span>
                        <span class="font-semibold text-zinc-800 text-right">BPS Surabaya</span>
                    </div>
                    <div class="flex items-start justify-between gap-2 pt-3 border-t border-zinc-100">
                        <span class="text-zinc-400">PERIODE</span>
                        <span class="font-semibold text-zinc-900 text-right">
                            {{ $mulai ? $mulai->translatedFormat('d M') : '-' }} &ndash; {{ $selesai ? $selesai->translatedFormat('d M Y') : '-' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="pt-5 mt-6 border-t border-zinc-100">
                <a href="{{ route('profile.edit') }}" 
                   class="inline-flex items-center justify-between w-full py-2.5 px-4 rounded-xl bg-zinc-50 hover:bg-zinc-100 border border-zinc-200 text-xs font-mono-tag font-semibold text-zinc-800 transition">
                    <span>KELOLA PROFIL</span>
                    <span class="text-zinc-400">&rarr;</span>
                </a>
            </div>
        </div>

        {{-- Status Presensi Masuk & Keluar Hari Ini --}}
        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">

            {{-- Kartu Presensi Masuk --}}
            <div class="bg-white rounded-3xl border border-zinc-200/80 p-7 shadow-sm flex flex-col justify-between hover:border-zinc-300 transition-colors">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-wider">GATE IN // 07:30</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono-tag font-semibold bg-zinc-100 text-zinc-600 border border-zinc-200">
                            PAGI
                        </span>
                    </div>
                    <h3 class="font-extrabold text-zinc-950 text-base tracking-tight">Presensi Masuk</h3>
                    <p class="text-xs text-zinc-500 mt-1">Verifikasi lokasi kedatangan di kantor BPS.</p>
                </div>

                <div class="mt-8">
                    @if($presensiHariIni && $presensiHariIni->jam_masuk)
                        <div class="p-4 bg-emerald-50 border border-emerald-200/80 rounded-2xl font-mono-tag">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span class="text-xs font-bold text-emerald-800">TERCATAT MASUK</span>
                            </div>
                            <p class="text-xs text-emerald-900 mt-1.5 font-bold">Pukul {{ $presensiHariIni->jam_masuk }} WIB</p>
                        </div>
                    @else
                        <a href="{{ route('mahasiswa.presensi.index') }}"
                           class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 bg-zinc-950 hover:bg-zinc-800 text-white font-mono-tag font-semibold text-xs rounded-xl transition shadow-sm hover:shadow">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>LAKUKAN PRESENSI MASUK</span>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Kartu Presensi Keluar --}}
            <div class="bg-white rounded-3xl border border-zinc-200/80 p-7 shadow-sm flex flex-col justify-between hover:border-zinc-300 transition-colors">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-wider">GATE OUT // 16:00</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono-tag font-semibold bg-zinc-100 text-zinc-600 border border-zinc-200">
                            SORE
                        </span>
                    </div>
                    <h3 class="font-extrabold text-zinc-950 text-base tracking-tight">Presensi Keluar</h3>
                    <p class="text-xs text-zinc-500 mt-1">Catat kepulangan saat jam kerja berakhir.</p>
                </div>

                <div class="mt-8">
                    @if($presensiHariIni && $presensiHariIni->jam_pulang)
                        <div class="p-4 bg-zinc-950 text-white rounded-2xl font-mono-tag border border-zinc-800">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                <span class="text-xs font-bold text-zinc-100">TERCATAT KELUAR</span>
                            </div>
                            <p class="text-xs text-zinc-300 mt-1.5 font-bold">
                                Pukul {{ $presensiHariIni->jam_pulang }} WIB
                                @if($durasiKerja)
                                    <span class="text-[11px] font-normal text-zinc-400 block mt-0.5">Durasi: {{ $durasiKerja }}</span>
                                @endif
                            </p>
                        </div>
                    @elseif(!$presensiHariIni || !$presensiHariIni->jam_masuk)
                        <div class="p-3.5 bg-zinc-50 border border-zinc-200/80 rounded-2xl text-center font-mono-tag">
                            <p class="text-[11px] text-zinc-400">BELUM CHECK-IN MASUK</p>
                        </div>
                    @elseif(!$logbookHariIni)
                        <a href="{{ route('mahasiswa.logbook.index') }}"
                           class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 bg-amber-500 hover:bg-amber-600 text-white font-mono-tag font-semibold text-xs rounded-xl transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>ISI LOGBOOK DULU</span>
                        </a>
                    @else
                        <a href="{{ route('mahasiswa.presensi.index') }}"
                           class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 bg-zinc-950 hover:bg-zinc-800 text-white font-mono-tag font-semibold text-xs rounded-xl transition shadow-sm hover:shadow">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                            </svg>
                            <span>LAKUKAN PRESENSI KELUAR</span>
                        </a>
                    @endif
                </div>
            </div>

        </div>

    </div>

    {{-- 4 Stat Studio Metrics --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Presensi --}}
        <div class="bg-white rounded-3xl border border-zinc-200/80 p-6 shadow-sm flex flex-col justify-between hover:border-zinc-300 transition-colors">
            <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-wider block mb-2">PRESENSI HADIR</span>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-zinc-950 font-mono-tag">{{ $totalPresensi }}</span>
                <span class="text-xs font-mono-tag text-zinc-400">HARI</span>
            </div>
            <div class="w-full bg-zinc-100 h-1.5 rounded-full mt-4 overflow-hidden">
                <div class="bg-zinc-950 h-full rounded-full" style="width: {{ $totalHari > 0 ? min(100, round(($totalPresensi / $totalHari) * 100)) : 0 }}%"></div>
            </div>
        </div>

        {{-- Total Logbook --}}
        <div class="bg-white rounded-3xl border border-zinc-200/80 p-6 shadow-sm flex flex-col justify-between hover:border-zinc-300 transition-colors">
            <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-wider block mb-2">ENTRI LOGBOOK</span>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-zinc-950 font-mono-tag">{{ $totalLogbook }}</span>
                <span class="text-xs font-mono-tag text-zinc-400">AKTIVITAS</span>
            </div>
            <div class="w-full bg-zinc-100 h-1.5 rounded-full mt-4 overflow-hidden">
                <div class="bg-zinc-950 h-full rounded-full" style="width: {{ min(100, $totalLogbook * 5) }}%"></div>
            </div>
        </div>

        {{-- Total Izin --}}
        <div class="bg-white rounded-3xl border border-zinc-200/80 p-6 shadow-sm flex flex-col justify-between hover:border-zinc-300 transition-colors">
            <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-wider block mb-2">IZIN DISETUJUI</span>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-zinc-950 font-mono-tag">{{ $totalIzin }}</span>
                <span class="text-xs font-mono-tag text-zinc-400">PENGAJUAN</span>
            </div>
            <div class="w-full bg-zinc-100 h-1.5 rounded-full mt-4 overflow-hidden">
                <div class="bg-blue-600 h-full rounded-full" style="width: {{ min(100, $totalIzin * 20) }}%"></div>
            </div>
        </div>

        {{-- Rata-rata Masuk --}}
        <div class="bg-white rounded-3xl border border-zinc-200/80 p-6 shadow-sm flex flex-col justify-between hover:border-zinc-300 transition-colors">
            <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-wider block mb-2">RATA-RATA MASUK</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-zinc-950 font-mono-tag">{{ $rataRataFormat }}</span>
                <span class="text-xs font-mono-tag text-zinc-400">WIB</span>
            </div>
            <div class="w-full bg-zinc-100 h-1.5 rounded-full mt-4 overflow-hidden">
                <div class="bg-emerald-600 h-full rounded-full" style="width: 100%"></div>
            </div>
        </div>
    </div>

    {{-- Menu Akses Cepat --}}
    <div class="bg-white rounded-3xl border border-zinc-200/80 p-8 shadow-sm">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-zinc-100">
            <div>
                <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-widest block mb-0.5">NAVIGATION</span>
                <h2 class="text-base font-extrabold text-zinc-950 tracking-tight">Akses Cepat Studio</h2>
            </div>
            <span class="text-xs font-mono-tag text-zinc-400">QUICK ACTIONS</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($menuCepat as $menu)
                <a href="{{ $menu['href'] }}" 
                   class="flex flex-col justify-between p-5 rounded-2xl border border-zinc-200/80 hover:border-zinc-950 hover:shadow-md transition-all duration-300 group bg-zinc-50/50 hover:bg-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl bg-zinc-950 text-white flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $menu['ikon'] }}"/>
                            </svg>
                        </div>
                        <span class="text-[10px] font-mono-tag text-zinc-400">[{{ $menu['tag'] }}]</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-zinc-900 group-hover:text-zinc-950 transition">{{ $menu['judul'] }}</p>
                        <p class="text-[11px] text-zinc-500 mt-0.5 line-clamp-1">{{ $menu['desk'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Logbook Terbaru (Activity Ledger) --}}
    @if(isset($logbookTerbaru) && $logbookTerbaru->isNotEmpty())
        <div class="bg-white rounded-3xl border border-zinc-200/80 p-8 shadow-sm">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-zinc-100">
                <div>
                    <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-widest block mb-0.5">ACTIVITY STREAM</span>
                    <h2 class="text-base font-extrabold text-zinc-950 tracking-tight">Catatan Logbook Terakhir</h2>
                </div>
                <a href="{{ route('mahasiswa.logbook.index') }}" class="text-xs font-mono-tag font-semibold text-zinc-950 hover:underline">
                    LIHAT SEMUA &rarr;
                </a>
            </div>

            <div class="divide-y divide-zinc-100">
                @foreach($logbookTerbaru as $lb)
                    <div class="py-4 flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2.5">
                                <span class="text-xs font-bold text-zinc-950 truncate">{{ $lb->kegiatan }}</span>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-mono-tag bg-zinc-100 text-zinc-600 border border-zinc-200">
                                    {{ $lb->tim }}
                                </span>
                            </div>
                            <p class="text-xs text-zinc-500 mt-1 truncate">{{ $lb->deskripsi }}</p>
                        </div>
                        <div class="text-right shrink-0 font-mono-tag">
                            <span class="text-xs text-zinc-400">{{ \Carbon\Carbon::parse($lb->tanggal)->isoFormat('D MMM Y') }}</span>
                            <div class="mt-1">
                                @if($lb->status === \App\Enums\StatusLogbook::Draft || $lb->status === 'draft')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">DRAFT</span>
                                @elseif($lb->status === \App\Enums\StatusLogbook::Approved || $lb->status === 'approved')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">APPROVED</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-zinc-100 text-zinc-700 border border-zinc-200">SUBMITTED</span>
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
