@extends('layouts.role', ['role' => 'mahasiswa'])

@section('title', 'Presensi Harian')

@section('content')

@php
    $masukSudah  = $presensiHariIni && $presensiHariIni->jam_masuk;
    $pulangSudah = $presensiHariIni && $presensiHariIni->jam_pulang;

    $durasiHariIni = null;
    if ($masukSudah && $pulangSudah) {
        $menit         = (int) abs(\Carbon\Carbon::parse($presensiHariIni->jam_pulang)->diffInMinutes(\Carbon\Carbon::parse($presensiHariIni->jam_masuk)));
        $durasiHariIni = intdiv($menit, 60) . ' jam ' . ($menit % 60) . ' menit';
    }
@endphp

<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    [x-cloak] { display: none !important; }
</style>

<div x-data="presensiApp()" x-init="init()" class="max-w-5xl mx-auto space-y-8">

    {{-- HERO EXHIBITION PLACARD: JAM & BATAS WAKTU --}}
    <div class="relative overflow-hidden bg-zinc-950 text-white rounded-3xl shadow-2xl p-8 lg:p-10 border border-zinc-800">
        {{-- Subtle Ambient Glow --}}
        <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full bg-emerald-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8 z-10">
            <div>
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-zinc-900 border border-zinc-800 text-[11px] font-mono-tag text-zinc-300">
                    @if(today()->isWeekend())
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        WEEKEND // LIBUR PRESENSI
                    @elseif($pulangSudah)
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        COMPLETED // PRESENSI LENGKAP HARI INI
                    @elseif($masukSudah)
                        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                        IN STUDIO // JANGAN LUPA CHECK-OUT KELUAR
                    @else
                        <span class="w-2 h-2 rounded-full bg-zinc-400 animate-pulse"></span>
                        AWAITING CHECK-IN
                    @endif
                </span>

                <h1 class="mt-4 text-3xl sm:text-4xl font-extrabold text-zinc-100 tracking-tight">
                    Presensi Kehadiran Geospasial
                </h1>
                <p class="text-zinc-400 mt-2 text-xs sm:text-sm max-w-xl leading-relaxed">
                    Verifikasi kehadiran presisi berbasis radius geolokasi kantor BPS Kota Surabaya.
                </p>
                
                <div class="mt-6 flex flex-wrap gap-2.5 font-mono-tag text-xs">
                    <span class="inline-flex items-center gap-2 bg-zinc-900/90 border border-zinc-800 text-zinc-200 px-3.5 py-1.5 rounded-xl">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        BATAS MASUK: <b>07:30 WIB</b>
                    </span>
                    <span class="inline-flex items-center gap-2 bg-zinc-900/90 border border-zinc-800 text-zinc-300 px-3.5 py-1.5 rounded-xl">
                        <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                        BATAS KELUAR: <b>16:00 WIB</b> <span class="text-zinc-400">(JUMAT 16:30)</span>
                    </span>
                </div>
            </div>

            <div class="lg:text-right bg-zinc-900/60 border border-zinc-800 p-6 rounded-2xl font-mono-tag backdrop-blur-sm min-w-[220px]">
                <p class="text-[10px] text-zinc-400 uppercase tracking-widest font-semibold">WAKTU AKTIF [WIB]</p>
                <div class="mt-1 text-3xl lg:text-4xl font-extrabold text-white tabular-nums tracking-wide" x-text="jamSekarang">--:--:--</div>
                <div class="text-xs text-zinc-400 mt-2" x-text="tanggalSekarang">-</div>
            </div>
        </div>
    </div>

    {{-- STATUS PERIODE MAGANG --}}
    @php
        $isMagangValid = false;
        $magangMessage = null;
        $magangBadgeType = 'warning';

        if (!isset($magang) || !$magang) {
            $magangMessage = 'Anda belum memiliki periode magang terdaftar. Silakan hubungi admin kurator.';
            $magangBadgeType = 'error';
        } elseif (today()->lt($magang->tanggal_mulai)) {
            $magangMessage = 'Periode magang Anda dimulai pada ' . $magang->tanggal_mulai->translatedFormat('d F Y') . '. Presensi baru dapat dilakukan mulai tanggal tersebut.';
            $magangBadgeType = 'info';
        } elseif (today()->gt($magang->tanggal_selesai) || $magang->status !== \App\Enums\StatusMagang::Active) {
            $magangMessage = 'Periode magang Anda telah berakhir pada ' . $magang->tanggal_selesai->translatedFormat('d F Y') . ' (Status: Nonaktif). Hubungi admin jika ingin mengaktifkan kembali periode magang Anda.';
            $magangBadgeType = 'error';
        } else {
            $isMagangValid = true;
        }
    @endphp

    @if(!$isMagangValid)
        <div class="flex items-start gap-3.5 p-5 rounded-2xl border font-mono-tag text-xs {{ $magangBadgeType === 'error' ? 'bg-rose-50 border-rose-200 text-rose-800' : 'bg-amber-50 border-amber-200 text-amber-800' }}">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5 {{ $magangBadgeType === 'error' ? 'text-rose-500' : 'text-amber-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <p class="font-bold">// {{ $magangBadgeType === 'error' ? 'AKUN MAGANG NONAKTIF / BERAKHIR' : 'PERIODE MAGANG BELUM DIMULAI' }}</p>
                <p class="mt-1 opacity-90 leading-relaxed">{{ $magangMessage }}</p>
            </div>
        </div>
    @endif

    {{-- STATUS GPS & GEOLOKASI (Placard Style) --}}
    <div class="bg-white rounded-3xl border border-zinc-200/80 p-5 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4 font-mono-tag">
        <div class="flex items-center gap-3.5 flex-1 min-w-0">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0"
                 :class="gpsError ? 'bg-rose-50 text-rose-600 border border-rose-200' : (lat ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-amber-50 text-amber-600 border border-amber-200')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-zinc-950" x-text="gpsError ? '// GPS ERROR' : (lat ? '// GEOLOCATION LOCKED' : '// SEARCHING GPS...')"></p>
                <p class="text-[11px] text-zinc-500 truncate mt-0.5" x-text="gpsError ? gpsError : (lat ? `KOORDINAT: ${lat.toFixed(5)}, ${lng.toFixed(5)}` : 'Aktifkan izin lokasi pada perangkat Anda')"></p>
            </div>
        </div>
        <button type="button" @click="getLocation()"
                class="self-start sm:self-auto px-4 py-2 text-xs font-semibold rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-800 transition">
            REFRESH GPS
        </button>
    </div>

    {{-- GRID AKSI PRESENSI --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- 1. Presensi Masuk --}}
        <div class="bg-white p-7 rounded-3xl border border-zinc-200/80 shadow-sm flex flex-col justify-between hover:border-zinc-300 transition-colors">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-wider">GATE IN // PAGI</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono-tag bg-zinc-100 text-zinc-600 border border-zinc-200">
                        MAX 07:30
                    </span>
                </div>
                <h2 class="text-base font-extrabold text-zinc-950 tracking-tight">Presensi Masuk</h2>
                <p class="text-xs text-zinc-500 mt-1">Lakukan konfirmasi kehadiran saat tiba di kantor.</p>
            </div>

            <div class="mt-8 font-mono-tag">
                @if($masukSudah)
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl text-center">
                        <div class="w-7 h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center mx-auto mb-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="text-xs font-bold text-emerald-950">TERCATAT MASUK</p>
                        <p class="text-xs text-emerald-800 mt-1">Pukul <b>{{ substr($presensiHariIni->jam_masuk, 0, 5) }}</b> WIB</p>
                    </div>
                @else
                    @if(today()->isWeekend())
                        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-center">
                            <p class="text-xs font-bold text-amber-900">AKHIR PEKAN</p>
                            <p class="text-[11px] text-amber-700 mt-1">Presensi libur pada hari Sabtu &amp; Minggu.</p>
                        </div>
                    @elseif(!$isMagangValid)
                        <div class="p-4 rounded-2xl bg-zinc-100 border border-zinc-200 text-center">
                            <p class="text-xs font-semibold text-zinc-600">PRESENSI NONAKTIF</p>
                            <p class="text-[11px] text-zinc-400 mt-1">Periode magang belum aktif atau telah berakhir</p>
                        </div>
                    @else
                        <form action="{{ route('mahasiswa.presensi.masuk') }}" method="POST" @submit.prevent="submitForm($event)">
                            @csrf
                            <input type="hidden" name="lat" x-model="lat">
                            <input type="hidden" name="lng" x-model="lng">
                            <button type="submit" :disabled="isLoading || !lat"
                                    class="w-full py-3 px-4 bg-zinc-950 hover:bg-zinc-800 text-white font-semibold text-xs rounded-xl transition shadow-sm hover:shadow disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!isLoading">ABSEN MASUK SEKARANG</span>
                                <span x-show="isLoading" x-cloak>MEMPROSES...</span>
                            </button>
                            <p class="text-[11px] text-zinc-400 mt-2 text-center" x-show="!lat && !gpsError" x-cloak>Menunggu koordinat GPS...</p>
                            <p class="text-[11px] text-rose-500 mt-2 text-center" x-show="gpsError" x-cloak>Klik "Refresh GPS" di atas.</p>
                        </form>
                    @endif
                @endif
            </div>
        </div>

        {{-- 2. Presensi Keluar --}}
        <div class="bg-white p-7 rounded-3xl border border-zinc-200/80 shadow-sm flex flex-col justify-between hover:border-zinc-300 transition-colors">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-wider">GATE OUT // SORE</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono-tag bg-zinc-100 text-zinc-600 border border-zinc-200">
                        MIN 16:00
                    </span>
                </div>
                <h2 class="text-base font-extrabold text-zinc-950 tracking-tight">Presensi Keluar</h2>
                <p class="text-xs text-zinc-500 mt-1">Catat kepulangan saat jam kerja magang berakhir.</p>
            </div>

            <div class="mt-8 font-mono-tag">
                @if($pulangSudah)
                    <div class="bg-zinc-950 text-white border border-zinc-800 p-4 rounded-2xl text-center">
                        <div class="w-7 h-7 rounded-full bg-emerald-500 text-zinc-950 flex items-center justify-center mx-auto mb-2 font-bold">
                            &check;
                        </div>
                        <p class="text-xs font-bold text-zinc-100">TERCATAT KELUAR</p>
                        <p class="text-xs text-zinc-300 mt-1">Pukul <b>{{ substr($presensiHariIni->jam_pulang, 0, 5) }}</b> WIB</p>
                        @if($durasiHariIni)
                            <p class="text-[11px] text-zinc-400 mt-1 pt-1 border-t border-zinc-800">Durasi: {{ $durasiHariIni }}</p>
                        @endif
                    </div>
                @elseif(!$masukSudah)
                    <div class="bg-zinc-50 border border-zinc-200 text-zinc-500 p-4 rounded-2xl text-center text-xs">
                        BELUM CHECK-IN
                        <p class="text-[11px] text-zinc-400 mt-1">Lakukan absen masuk terlebih dahulu.</p>
                    </div>
                @else
                    <form action="{{ route('mahasiswa.presensi.keluar') }}" method="POST" @submit.prevent="submitForm($event)">
                        @csrf
                        <input type="hidden" name="lat" x-model="lat">
                        <input type="hidden" name="lng" x-model="lng">
                        <button type="submit" :disabled="isLoading || !lat"
                                class="w-full py-3 px-4 bg-zinc-950 hover:bg-zinc-800 text-white font-semibold text-xs rounded-xl transition shadow-sm hover:shadow disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!isLoading">ABSEN KELUAR SEKARANG</span>
                            <span x-show="isLoading" x-cloak>MEMPROSES...</span>
                        </button>
                        <p class="text-[11px] text-zinc-400 mt-2 text-center">Pastikan logbook hari ini telah diisi.</p>
                    </form>
                @endif
            </div>
        </div>

        {{-- 3. Presensi Kamera (Luar Kantor) --}}
        <div class="bg-white p-7 rounded-3xl border border-zinc-200/80 shadow-sm flex flex-col justify-between hover:border-zinc-300 transition-colors">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-wider">OFFSITE PASS</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono-tag bg-zinc-100 text-zinc-600 border border-zinc-200">
                        KAMERA
                    </span>
                </div>
                <h2 class="text-base font-extrabold text-zinc-950 tracking-tight">Presensi Luar Kantor</h2>
                <p class="text-xs text-zinc-500 mt-1 leading-relaxed">
                    Khusus penugasan survei luar atau perizinan dinas khusus dengan verifikasi foto kamera.
                </p>
            </div>

            <div class="mt-8 font-mono-tag">
                @if(today()->isWeekend())
                    <button type="button" disabled
                            class="w-full py-3 px-4 bg-zinc-100 text-zinc-400 font-semibold text-xs rounded-xl cursor-not-allowed border border-zinc-200">
                        LIBUR AKHIR PEKAN
                    </button>
                @else
                    <button type="button" @click="openCamera()"
                            class="w-full py-3 px-4 bg-zinc-100 hover:bg-zinc-200 text-zinc-900 border border-zinc-300 font-semibold text-xs rounded-xl transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>BUKA KAMERA PRESENSI</span>
                    </button>
                @endif
            </div>
        </div>

    </div>

    {{-- RIWAYAT PRESENSI (Gallery Ledger) --}}
    <div id="riwayat-presensi-container"
         data-async-container
         @click="SiBookAsync.handleClick($event, 'riwayat-presensi-container')"
         class="bg-white rounded-3xl border border-zinc-200/80 shadow-sm overflow-hidden">
        
        <div class="p-6 sm:p-8 border-b border-zinc-100 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <div>
                <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-widest block mb-0.5">LEDGER ARCHIVE</span>
                <h2 class="text-base font-extrabold text-zinc-950 tracking-tight">Riwayat Presensi Bulanan</h2>
            </div>
            
            <form method="GET" action="{{ url()->current() }}" 
                  @submit.prevent="SiBookAsync.handleFormSubmit($event, 'riwayat-presensi-container')"
                  class="flex items-center gap-2 font-mono-tag text-xs">
                <input type="month" name="bulan" value="{{ request('bulan', now()->format('Y-m')) }}"
                       @change="SiBookAsync.handleFormSubmit($event, 'riwayat-presensi-container')"
                       class="text-xs border border-zinc-200 rounded-xl px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-zinc-950 cursor-pointer text-zinc-800">
                @if(request('bulan'))
                    <a href="{{ url()->current() }}" class="text-xs text-zinc-400 hover:text-zinc-900 underline">RESET</a>
                @endif
            </form>
        </div>

        {{-- Placard Akumulasi Keterlambatan Magang (Tanggungan Waktu yang Harus Diselesaikan) --}}
        <div class="p-6 bg-zinc-50/80 border-b border-zinc-100 font-mono-tag">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 {{ ($totalMenitTerlambat ?? 0) > 0 ? 'bg-rose-100 text-rose-700 border border-rose-200' : 'bg-emerald-100 text-emerald-700 border border-emerald-200' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs font-bold text-zinc-950 uppercase tracking-wider">// REKAPITULASI KETERLAMBATAN</span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full {{ ($totalMenitTerlambat ?? 0) > 0 ? 'bg-rose-50 text-rose-700 border border-rose-200 font-semibold' : 'bg-emerald-50 text-emerald-700 border border-emerald-200 font-semibold' }}">
                                {{ ($totalMenitTerlambat ?? 0) > 0 ? 'ADA TANGGUNGAN WAKTU' : 'TERTIB / NIHIL' }}
                            </span>
                        </div>
                        <p class="text-[11px] text-zinc-500 mt-1 leading-relaxed">
                            Batas masuk: <b class="text-zinc-800">07:30 WIB</b>. Seluruh menit keterlambatan direkap sejak hari pertama magang dan wajib diselesaikan.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-6 md:border-l md:border-zinc-200 md:pl-6">
                    <div>
                        <span class="text-[10px] text-zinc-400 uppercase tracking-widest block">TOTAL KETERLAMBATAN</span>
                        <div class="flex items-baseline gap-1.5">
                            <span class="text-xl font-extrabold {{ ($totalMenitTerlambat ?? 0) > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                {{ $totalMenitTerlambat ?? 0 }}
                            </span>
                            <span class="text-xs font-semibold text-zinc-600">Menit</span>
                        </div>
                        @if(($totalMenitTerlambat ?? 0) >= 60)
                            <span class="text-[10px] text-zinc-400 block mt-0.5">
                                ({{ intdiv($totalMenitTerlambat, 60) }} jam {{ $totalMenitTerlambat % 60 }} menit)
                            </span>
                        @endif
                    </div>
                    <div class="border-l border-zinc-200 pl-4">
                        <span class="text-[10px] text-zinc-400 uppercase tracking-widest block">HARI TERLAMBAT</span>
                        <div class="flex items-baseline gap-1.5">
                            <span class="text-xl font-extrabold text-zinc-900">
                                {{ $totalHariTerlambat ?? 0 }}
                            </span>
                            <span class="text-xs font-semibold text-zinc-600">Hari</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs font-mono-tag">
                <thead>
                    <tr class="border-b border-zinc-100 text-zinc-400 uppercase tracking-wider bg-zinc-50/50">
                        <th class="py-3.5 px-6">Tanggal</th>
                        <th class="py-3.5 px-4">Jam Masuk</th>
                        <th class="py-3.5 px-4">Jam Pulang</th>
                        <th class="py-3.5 px-4">Durasi</th>
                        <th class="py-3.5 px-6 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 text-zinc-800">
                    @forelse($riwayat as $row)
                        @php
                            $durasiBaris = null;
                            if ($row->jam_masuk && $row->jam_pulang) {
                                $menitBaris = (int) abs(\Carbon\Carbon::parse($row->jam_pulang)->diffInMinutes(\Carbon\Carbon::parse($row->jam_masuk)));
                                $durasiBaris = intdiv($menitBaris, 60) . 'j ' . ($menitBaris % 60) . 'm';
                            }
                        @endphp
                        <tr class="hover:bg-zinc-50/60 transition">
                            <td class="py-4 px-6 font-bold text-zinc-950">
                                {{ \Carbon\Carbon::parse($row->tanggal)->isoFormat('D MMM Y') }}
                            </td>
                            <td class="py-4 px-4 text-zinc-700">
                                @if($row->jam_masuk)
                                    <span class="font-medium text-zinc-900">{{ substr($row->jam_masuk, 0, 5) }}</span>
                                    @if($row->menit_terlambat > 0)
                                        <span class="block text-[10px] font-semibold text-rose-600 mt-0.5">
                                            +{{ $row->menit_terlambat }} mnt terlambat
                                        </span>
                                    @endif
                                @else
                                    <span class="text-zinc-300">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-zinc-700">
                                @if($row->jam_pulang)
                                    <span class="font-medium text-zinc-900">{{ substr($row->jam_pulang, 0, 5) }}</span>
                                @else
                                    <span class="text-zinc-300">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-zinc-500">
                                {{ $durasiBaris ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                @if($row->status_verifikasi === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        PENDING
                                    </span>
                                @else
                                    <div class="inline-flex items-center gap-1.5">
                                        @if($row->status == 'terlambat' || $row->menit_terlambat > 0)
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                TERLAMBAT ({{ $row->menit_terlambat }} MNT)
                                            </span>
                                        @elseif($row->status == 'izin')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">IZIN</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">TEPAT WAKTU</span>
                                        @endif

                                        @if($row->keterangan == 'Pulang Terlalu Cepat')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">PULANG CEPAT</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-14 text-center">
                                <div class="flex flex-col items-center justify-center text-zinc-400 font-mono-tag">
                                    <p class="text-xs font-bold text-zinc-600">// BELUM ADA RIWAYAT PRESENSI</p>
                                    <p class="text-[11px] text-zinc-400 mt-1">Riwayat tercatat otomatis setelah presensi dilakukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-5 border-t border-zinc-100 bg-zinc-50/50">
            {{ $riwayat->withQueryString()->links() }}
        </div>
    </div>

    {{-- MODAL KAMERA --}}
    <div x-show="cameraOpen" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-950/70 backdrop-blur-sm p-4"
         @keydown.escape.window="closeCamera()">

        <div class="relative bg-white rounded-3xl max-w-md w-full overflow-hidden shadow-2xl border border-zinc-200"
             @click.outside="closeCamera()">

            <div class="p-5 border-b border-zinc-100 flex justify-between items-center bg-zinc-50/60 font-mono-tag">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-xl bg-zinc-950 text-white flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-xs text-zinc-900">// KAMERA PRESENSI OFFSITE</h3>
                </div>
                <button type="button" @click="closeCamera()" class="text-zinc-400 hover:text-zinc-950 p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-6">
                {{-- Video Stream --}}
                <div class="relative bg-zinc-950 rounded-2xl overflow-hidden aspect-video mb-4" x-show="!fotoCaptured">
                    <video x-ref="video" autoplay playsinline class="w-full h-full object-cover"></video>
                    <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-black/70 text-white text-[10px] font-mono-tag backdrop-blur-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> LIVE CAM
                    </span>
                    <p class="absolute bottom-3 inset-x-0 text-center text-rose-300 text-xs px-3 font-mono-tag" x-show="cameraError" x-text="cameraError"></p>
                </div>

                {{-- Preview Foto --}}
                <div class="relative bg-zinc-950 rounded-2xl overflow-hidden aspect-video mb-4" x-show="fotoCaptured" x-cloak>
                    <img :src="fotoData" class="w-full h-full object-cover">
                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-lg bg-emerald-600 text-white text-[10px] font-mono-tag font-bold">
                        CAPTURED
                    </span>
                </div>

                <form action="{{ route('mahasiswa.presensi.kamera') }}" method="POST" @submit.prevent="submitKamera($event)">
                    @csrf
                    <input type="hidden" name="foto" x-model="fotoData">

                    <div class="mb-4" x-show="!fotoCaptured">
                        <button type="button" @click="ambilFoto()"
                                class="w-full py-3 bg-zinc-950 hover:bg-zinc-800 text-white font-mono-tag font-semibold text-xs rounded-xl transition shadow-sm">
                            AMBIL FOTO SEKARANG
                        </button>
                    </div>

                    <div x-show="fotoCaptured" x-cloak class="space-y-4">
                        <div>
                            <label class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">Alasan di Luar Kantor</label>
                            <textarea name="alasan" rows="2" required
                                      class="w-full border border-zinc-200 rounded-xl p-3 text-xs text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-950"
                                      placeholder="Contoh: Tugas dinas luar atau survei lapangan..."></textarea>
                        </div>

                        <div class="flex gap-3">
                            <button type="button" @click="retakeFoto()"
                                    class="w-1/3 py-2.5 bg-zinc-100 text-zinc-700 font-mono-tag font-semibold text-xs rounded-xl hover:bg-zinc-200 transition">
                                ULANGI
                            </button>
                            <button type="submit" :disabled="isLoading"
                                    class="w-2/3 py-2.5 bg-zinc-950 hover:bg-zinc-800 text-white font-mono-tag font-semibold text-xs rounded-xl transition shadow-sm disabled:opacity-50">
                                <span x-show="!isLoading">KIRIM PRESENSI</span>
                                <span x-show="isLoading" x-cloak>MENGIRIM...</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function presensiApp() {
        return {
            jamSekarang: '',
            tanggalSekarang: '',
            lat: null,
            lng: null,
            gpsError: null,
            isLoading: false,
            cameraOpen: false,
            cameraError: null,
            fotoCaptured: false,
            fotoData: null,
            stream: null,

            init() {
                const updateClock = () => {
                    const now = new Date();
                    this.jamSekarang = now.toLocaleTimeString('id-ID', { hour12: false });
                    this.tanggalSekarang = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                };
                updateClock();
                setInterval(updateClock, 1000);

                this.getLocation();
            },

            getLocation() {
                this.gpsError = null;
                this.lat = null;
                this.lng = null;

                if (!navigator.geolocation) {
                    this.gpsError = 'Browser Anda tidak mendukung Geolocation.';
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        this.lat = position.coords.latitude;
                        this.lng = position.coords.longitude;
                    },
                    (error) => {
                        this.gpsError = 'Gagal mendapatkan lokasi. Pastikan izin lokasi aktif.';
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            },

            submitForm(event) {
                if (this.isLoading) { event.preventDefault(); return; }
                this.isLoading = true;
                event.target.submit();
            },

            async openCamera() {
                this.cameraOpen = true;
                this.fotoCaptured = false;
                this.fotoData = null;
                this.cameraError = null;

                if (this.stream) {
                    this.stream.getTracks().forEach(track => track.stop());
                }

                try {
                    this.stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                    this.$refs.video.srcObject = this.stream;
                } catch (err) {
                    this.cameraError = 'Tidak dapat mengakses kamera: ' + err.message;
                }
            },

            closeCamera() {
                this.cameraOpen = false;
                if (this.stream) {
                    this.stream.getTracks().forEach(track => track.stop());
                }
            },

            ambilFoto() {
                const video = this.$refs.video;
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                canvas.getContext('2d').drawImage(video, 0, 0);

                this.fotoData = canvas.toDataURL('image/jpeg', 0.8);
                this.fotoCaptured = true;

                if (this.stream) {
                    this.stream.getTracks().forEach(track => track.stop());
                }
            },

            retakeFoto() {
                this.fotoCaptured = false;
                this.fotoData = null;
                this.openCamera();
            },

            submitKamera(event) {
                if (!this.fotoCaptured) { event.preventDefault(); return; }
                if (this.isLoading) { event.preventDefault(); return; }
                this.isLoading = true;
                event.target.submit();
            }
        }
    }
</script>

@endsection
