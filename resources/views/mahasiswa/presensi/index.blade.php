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

<div x-data="presensiApp()" x-init="init()" class="max-w-5xl mx-auto space-y-6">

    {{-- HERO: JAM & BATAS WAKTU --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-orange-600 via-orange-500 to-amber-500 rounded-2xl shadow-sm p-6 lg:p-8 text-white">
        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 z-10">
            <div>
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold backdrop-blur-sm">
                    @if($pulangSudah)
                        <span class="w-2 h-2 rounded-full bg-emerald-300"></span>
                        Presensi hari ini sudah lengkap
                    @elseif($masukSudah)
                        <span class="w-2 h-2 rounded-full bg-amber-300 animate-pulse"></span>
                        Sudah absen masuk — jangan lupa absen keluar
                    @else
                        <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                        Belum melakukan presensi hari ini
                    @endif
                </span>
                <h1 class="mt-3 text-2xl lg:text-3xl font-extrabold text-white tracking-tight flex items-center gap-2.5">
                    <span>Presensi Kehadiran</span>
                </h1>
                <p class="text-orange-100 mt-1 text-xs sm:text-sm">Catat kehadiran Anda setiap tiba &amp; sebelum pulang dari kantor.</p>
                
                <div class="mt-4 flex flex-wrap gap-2 text-xs">
                    <span class="inline-flex items-center gap-1.5 bg-white/15 text-white font-medium px-3 py-1.5 rounded-lg">
                        <span class="w-2 h-2 rounded-full bg-emerald-300"></span>
                        Batas Masuk: <b class="font-bold">07:30</b>
                    </span>
                    <span class="inline-flex items-center gap-1.5 bg-white/15 text-white font-medium px-3 py-1.5 rounded-lg">
                        <span class="w-2 h-2 rounded-full bg-blue-300"></span>
                        Batas Pulang: <b class="font-bold">16:00</b> <span class="text-orange-100">(Jumat 16:30)</span>
                    </span>
                </div>
            </div>

            <div class="lg:text-right bg-white/10 lg:bg-transparent p-4 lg:p-0 rounded-xl backdrop-blur-sm lg:backdrop-blur-none">
                <p class="text-orange-100 text-[11px] font-semibold uppercase tracking-wider">Waktu Indonesia Barat</p>
                <div class="mt-1 text-3xl lg:text-5xl font-extrabold text-white tabular-nums tracking-wide" x-text="jamSekarang">--:--:--</div>
                <div class="text-xs text-orange-100 mt-1" x-text="tanggalSekarang">-</div>
            </div>
        </div>
    </div>

    {{-- STATUS PERIODE MAGANG --}}
    @php
        $isMagangValid = false;
        $magangMessage = null;
        $magangBadgeType = 'warning';

        if (!isset($magang) || !$magang) {
            $magangMessage = 'Anda belum memiliki periode magang terdaftar. Silakan hubungi admin.';
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
        <div class="flex items-start gap-3 p-4 rounded-xl border {{ $magangBadgeType === 'error' ? 'bg-rose-50 border-rose-200 text-rose-800' : 'bg-amber-50 border-amber-200 text-amber-800' }}">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5 {{ $magangBadgeType === 'error' ? 'text-rose-500' : 'text-amber-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <p class="text-xs font-bold">{{ $magangBadgeType === 'error' ? 'Akun Magang Nonaktif / Berakhir' : 'Periode Magang Belum Dimulai' }}</p>
                <p class="text-xs mt-0.5 opacity-90 leading-relaxed">{{ $magangMessage }}</p>
            </div>
        </div>
    @endif

    {{-- STATUS GPS --}}
    <div class="bg-white rounded-2xl shadow-[0_1px_3px_rgba(0,0,0,0.02)] border border-gray-150 p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                 :class="gpsError ? 'bg-rose-50 text-rose-600' : (lat ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-gray-800" x-text="gpsError ? 'Lokasi Gagal Dideteksi' : (lat ? 'Lokasi Terdeteksi' : 'Mendeteksi Lokasi...')"></p>
                <p class="text-[11px] text-gray-500 truncate" x-text="gpsError ? gpsError : (lat ? `Koordinat: ${lat.toFixed(5)}, ${lng.toFixed(5)}` : 'Aktifkan izin lokasi pada browser Anda')"></p>
            </div>
        </div>
        <button type="button" @click="getLocation()"
                class="self-start sm:self-auto px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 transition">
            Perbarui Lokasi
        </button>
    </div>

    {{-- GRID AKSI PRESENSI --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        {{-- Presensi Masuk --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-150 shadow-[0_1px_3px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Presensi Masuk</h2>
                        <p class="text-[11px] text-gray-400">Saat tiba di kantor</p>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                @if($masukSudah)
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl text-center">
                        <div class="w-7 h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center mx-auto mb-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="text-xs font-bold text-emerald-900">Tercatat Masuk</p>
                        <p class="text-[11px] text-emerald-700 mt-0.5">Pukul <b>{{ $presensiHariIni->jam_masuk }}</b> WIB</p>
                    </div>
                @else
                    @if(!$isMagangValid)
                        <div class="p-3 rounded-xl bg-gray-50 border border-gray-200 text-center">
                            <p class="text-xs font-semibold text-gray-500">Presensi Masuk Ditutup</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">Periode magang belum aktif atau telah berakhir</p>
                        </div>
                    @else
                        <form action="{{ route('mahasiswa.presensi.masuk') }}" method="POST" @submit.prevent="submitForm($event)">
                            @csrf
                            <input type="hidden" name="lat" x-model="lat">
                            <input type="hidden" name="lng" x-model="lng">
                            <button type="submit" :disabled="isLoading || !lat"
                                    class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!isLoading">Absen Masuk Sekarang</span>
                                <span x-show="isLoading" x-cloak>Memproses...</span>
                            </button>
                            <p class="text-[11px] text-gray-400 mt-2 text-center" x-show="!lat && !gpsError" x-cloak>Menunggu koordinat GPS...</p>
                            <p class="text-[11px] text-rose-500 mt-2 text-center" x-show="gpsError" x-cloak>Klik "Perbarui Lokasi" di atas.</p>
                        </form>
                    @endif
                @endif
            </div>
        </div>

        {{-- Presensi Keluar --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-150 shadow-[0_1px_3px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Presensi Keluar</h2>
                        <p class="text-[11px] text-gray-400">Saat jam kerja berakhir</p>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                @if($pulangSudah)
                    <div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-center">
                        <div class="w-7 h-7 rounded-full bg-blue-600 text-white flex items-center justify-center mx-auto mb-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="text-xs font-bold text-blue-900">Tercatat Keluar</p>
                        <p class="text-[11px] text-blue-700 mt-0.5">Pukul <b>{{ $presensiHariIni->jam_pulang }}</b> WIB</p>
                        @if($durasiHariIni)
                            <p class="text-[10.5px] text-blue-600 mt-1 border-t border-blue-100 pt-1">Durasi: {{ $durasiHariIni }}</p>
                        @endif
                    </div>
                @elseif(!$masukSudah)
                    <div class="bg-gray-50 border border-gray-150 text-gray-500 p-4 rounded-xl text-center text-xs font-medium">
                        Belum presensi masuk
                        <p class="text-[11px] text-gray-400 mt-0.5">Lakukan presensi masuk terlebih dahulu.</p>
                    </div>
                @else
                    <form action="{{ route('mahasiswa.presensi.keluar') }}" method="POST" @submit.prevent="submitForm($event)">
                        @csrf
                        <input type="hidden" name="lat" x-model="lat">
                        <input type="hidden" name="lng" x-model="lng">
                        <button type="submit" :disabled="isLoading || !lat"
                                class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!isLoading">Absen Keluar Sekarang</span>
                            <span x-show="isLoading" x-cloak>Memproses...</span>
                        </button>
                        <p class="text-[11px] text-gray-400 mt-2 text-center">Pastikan logbook hari ini sudah diisi sebelum keluar.</p>
                    </form>
                @endif
            </div>
        </div>

        {{-- Presensi Kamera (Luar Kantor) --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-150 shadow-[0_1px_3px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-9 h-9 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Luar Kantor</h2>
                        <p class="text-[11px] text-gray-400">Presensi kamera &amp; izin</p>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                    Gunakan presensi kamera jika Anda berhalangan hadir di kantor atau sedang dinas luar.
                </p>
            </div>

            <div class="mt-6">
                <button type="button" @click="openCamera()"
                        class="w-full py-2.5 px-4 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl transition shadow-sm flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Buka Kamera Presensi</span>
                </button>
            </div>
        </div>

    </div>

    {{-- RIWAYAT PRESENSI --}}
    <div id="riwayat-presensi-container"
         data-async-container
         @click="SiBookAsync.handleClick($event, 'riwayat-presensi-container')"
         class="bg-white rounded-2xl border border-gray-150 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden">
        
        <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
            <div>
                <h2 class="text-sm font-bold text-gray-900">Riwayat Presensi</h2>
                <p class="text-[11px] text-gray-400 mt-0.5">Rekap kehadiran Anda per bulan</p>
            </div>
            
            <form method="GET" action="{{ url()->current() }}" 
                  @submit.prevent="SiBookAsync.handleFormSubmit($event, 'riwayat-presensi-container')"
                  class="flex items-center gap-2">
                <input type="month" name="bulan" value="{{ request('bulan', now()->format('Y-m')) }}"
                       @change="SiBookAsync.handleFormSubmit($event, 'riwayat-presensi-container')"
                       class="text-xs border-gray-200 rounded-lg p-1.5 focus:outline-none focus:ring-1 focus:ring-orange-500 cursor-pointer text-gray-700">
                @if(request('bulan'))
                    <a href="{{ url()->current() }}" class="text-xs text-gray-400 hover:text-gray-700 underline">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-[13px]">
                <thead>
                    <tr class="border-b border-gray-100 font-semibold text-gray-700 bg-gray-50/50">
                        <th class="py-3 px-5">Tanggal</th>
                        <th class="py-3 px-3">Jam Masuk</th>
                        <th class="py-3 px-3">Jam Pulang</th>
                        <th class="py-3 px-3">Durasi</th>
                        <th class="py-3 px-5 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-800">
                    @forelse($riwayat as $row)
                        @php
                            $durasiBaris = null;
                            if ($row->jam_masuk && $row->jam_pulang) {
                                $menitBaris = (int) abs(\Carbon\Carbon::parse($row->jam_pulang)->diffInMinutes(\Carbon\Carbon::parse($row->jam_masuk)));
                                $durasiBaris = intdiv($menitBaris, 60) . 'j ' . ($menitBaris % 60) . 'm';
                            }
                        @endphp
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="py-3.5 px-5 font-semibold text-gray-900 text-xs">
                                {{ \Carbon\Carbon::parse($row->tanggal)->isoFormat('D MMM Y') }}
                            </td>
                            <td class="py-3.5 px-3 text-xs text-gray-700">
                                @if($row->jam_masuk)
                                    <span class="font-medium">{{ $row->jam_masuk }}</span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-3 text-xs text-gray-700">
                                @if($row->jam_pulang)
                                    <span class="font-medium">{{ $row->jam_pulang }}</span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-3 text-xs text-gray-500">
                                {{ $durasiBaris ?? '-' }}
                            </td>
                            <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                @if($row->status_verifikasi === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Menunggu Verifikasi
                                    </span>
                                @else
                                    <div class="inline-flex items-center gap-1">
                                        @if($row->status == 'terlambat')
                                            <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">Terlambat</span>
                                        @elseif($row->status == 'izin')
                                            <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">Izin</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Tepat Waktu</span>
                                        @endif

                                        @if($row->keterangan == 'Pulang Terlalu Cepat')
                                            <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">Pulang Cepat</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-10 h-10 mb-2 text-gray-300 stroke-[1.2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-xs font-semibold text-gray-600">Belum ada riwayat presensi bulan ini</p>
                                    <p class="text-[11px] text-gray-400 mt-0.5">Riwayat akan otomatis terisi setelah Anda melakukan presensi.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 bg-gray-50/40">
            {{ $riwayat->withQueryString()->links() }}
        </div>
    </div>

    {{-- MODAL KAMERA --}}
    <div x-show="cameraOpen" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm p-4"
         @keydown.escape.window="closeCamera()">

        <div class="relative bg-white rounded-2xl max-w-md w-full overflow-hidden shadow-2xl border border-gray-150"
             @click.outside="closeCamera()">

            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/60">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-xs text-gray-800">Presensi Luar Kantor</h3>
                </div>
                <button type="button" @click="closeCamera()" class="text-gray-400 hover:text-gray-600 p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-5">
                {{-- Video Stream --}}
                <div class="relative bg-gray-900 rounded-xl overflow-hidden aspect-video mb-4" x-show="!fotoCaptured">
                    <video x-ref="video" autoplay playsinline class="w-full h-full object-cover"></video>
                    <span class="absolute top-2 left-2 inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-black/60 text-white text-[10px] font-semibold backdrop-blur-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Kamera Aktif
                    </span>
                    <p class="absolute bottom-2 inset-x-0 text-center text-white/80 text-xs px-2" x-show="cameraError" x-text="cameraError"></p>
                </div>

                {{-- Preview Foto --}}
                <div class="relative bg-black rounded-xl overflow-hidden aspect-video mb-4" x-show="fotoCaptured" x-cloak>
                    <img :src="fotoData" class="w-full h-full object-cover">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-emerald-600 text-white text-[10px] font-bold">
                        Foto Tersimpan
                    </span>
                </div>

                <form action="{{ route('mahasiswa.presensi.kamera') }}" method="POST" @submit.prevent="submitKamera($event)">
                    @csrf
                    <input type="hidden" name="foto" x-model="fotoData">

                    <div class="mb-4" x-show="!fotoCaptured">
                        <button type="button" @click="ambilFoto()"
                                class="w-full py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs rounded-xl transition shadow-sm">
                            Ambil Foto Sekarang
                        </button>
                    </div>

                    <div x-show="fotoCaptured" x-cloak class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Alasan Berada di Luar Kantor</label>
                            <textarea name="alasan" rows="2" required
                                      class="w-full border border-gray-200 rounded-xl p-2.5 text-xs text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-orange-500"
                                      placeholder="Contoh: Sedang tugas dinas luar atau survei lapangan..."></textarea>
                        </div>

                        <div class="flex gap-2">
                            <button type="button" @click="retakeFoto()"
                                    class="w-1/3 py-2 bg-gray-100 text-gray-700 font-semibold text-xs rounded-xl hover:bg-gray-200 transition">
                                Ulangi
                            </button>
                            <button type="submit" :disabled="isLoading"
                                    class="w-2/3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition shadow-sm disabled:opacity-50">
                                <span x-show="!isLoading">Kirim Presensi</span>
                                <span x-show="isLoading" x-cloak>Mengirim...</span>
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
