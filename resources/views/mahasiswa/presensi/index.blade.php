@extends('layouts.role', ['role' => 'mahasiswa'])

@section('title', 'Presensi Harian')

@section('content')

@php
    /*
    |----------------------------------------------------------------------
    | DATA TURUNAN — status & durasi dihitung di view
    |----------------------------------------------------------------------
    */
    $masukSudah  = $presensiHariIni && $presensiHariIni->jam_masuk;
    $pulangSudah = $presensiHariIni && $presensiHariIni->jam_pulang;

    // Durasi kerja hari ini (jika masuk + pulang sudah lengkap)
    $durasiHariIni = null;
    if ($masukSudah && $pulangSudah) {
        $menit         = (int) abs(\Carbon\Carbon::parse($presensiHariIni->jam_pulang)->diffInMinutes(\Carbon\Carbon::parse($presensiHariIni->jam_masuk)));
        $durasiHariIni = intdiv($menit, 60) . ' jam ' . ($menit % 60) . ' menit';
    }
@endphp

<!-- Alpine.js (hapus baris ini jika layout utama sudah memuat Alpine) -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    [x-cloak] { display: none !important; }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .anim-fade-up { animation: fadeUp .55s ease-out both; }
    .anim-delay-1 { animation-delay: .10s; }
    .anim-delay-2 { animation-delay: .20s; }
    .anim-delay-3 { animation-delay: .32s; }
</style>

<div x-data="presensiApp()" x-init="init()" class="max-w-5xl mx-auto space-y-6">

    <!-- ================= HERO: JAM & BATAS WAKTU ================= -->
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700 rounded-3xl shadow-xl shadow-indigo-200/60 p-7 lg:p-9 anim-fade-up">
        <!-- Dekorasi latar -->
        <div class="absolute -top-20 -right-16 w-72 h-72 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-24 -left-12 w-64 h-64 bg-fuchsia-400/20 rounded-full blur-2xl"></div>

        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/15 text-white text-xs font-semibold backdrop-blur-sm">
                    @if($pulangSudah)
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        Presensi hari ini sudah lengkap
                    @elseif($masukSudah)
                        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                        Sudah absen masuk — jangan lupa absen keluar
                    @else
                        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                        Anda belum melakukan presensi hari ini
                    @endif
                </span>
                <h2 class="mt-4 text-2xl lg:text-3xl font-extrabold text-white leading-snug">Presensi Kehadiran 🕒</h2>
                <p class="text-indigo-100 mt-2 text-sm lg:text-base">Catat kehadiran Anda setiap tiba &amp; sebelum pulang dari kantor.</p>
                <div class="mt-5 flex flex-wrap gap-2">
                    <span class="inline-flex items-center gap-1.5 bg-white/10 text-white text-xs font-medium px-3 py-1.5 rounded-lg">🟢 Batas Masuk: <b class="font-semibold">07:30</b></span>
                    <span class="inline-flex items-center gap-1.5 bg-white/10 text-white text-xs font-medium px-3 py-1.5 rounded-lg">🔵 Batas Pulang: <b class="font-semibold">16:00</b> <span class="text-indigo-200">(Jumat 16:30)</span></span>
                </div>
            </div>

            <div class="lg:text-right">
                <p class="text-indigo-200 text-[11px] font-semibold uppercase tracking-[0.2em]">Waktu Indonesia Barat</p>
                <!-- Mengubah font-extrabold menjadi font-bold -->
                <div class="mt-4 text-3xl lg:text-6xl font-extrabold text-white tabular-nums font-sans tracking-wider" x-text="jamSekarang">18:09:30</div>
                <div class="text-sm text-indigo-100 mt-2" x-text="tanggalSekarang">-</div>
            </div>
        </div>
    </div>

    <!-- ================= NOTIFIKASI ================= -->
    @if(session('success'))
        <div class="anim-fade-up flex items-start gap-3 p-4 bg-green-50 border border-green-200 text-green-800 rounded-2xl">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="anim-fade-up flex items-start gap-3 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- ================= STATUS GPS ================= -->
    <div class="anim-fade-up anim-delay-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-col sm:flex-row sm:items-center gap-3">
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                 :class="gpsError ? 'bg-red-50 text-red-600' : (lat ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600')">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <div class="min-w-0">
                <p class="text-sm font-bold text-gray-800" x-text="gpsError ? 'Lokasi Gagal Dideteksi' : (lat ? 'Lokasi Terdeteksi' : 'Mendeteksi Lokasi...')"></p>
                <p class="text-xs text-gray-500 truncate" x-text="gpsError ? gpsError : (lat ? `Koordinat: ${lat.toFixed(5)}, ${lng.toFixed(5)}` : 'Aktifkan izin lokasi pada browser Anda')"></p>
            </div>
        </div>
        <button type="button" @click="getLocation()"
                class="self-start sm:self-auto px-4 py-2 text-xs font-bold rounded-xl bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition-colors">
            Perbarui Lokasi
        </button>
    </div>

    <!-- ================= GRID AKSI ================= -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- ===== Presensi Masuk ===== -->
        <div class="anim-fade-up anim-delay-1 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col relative overflow-hidden group hover:shadow-lg transition-shadow duration-300">
            <div class="absolute -right-5 -top-5 w-28 h-28 bg-green-50 rounded-full opacity-60 group-hover:scale-125 transition-transform duration-500"></div>
            <div class="relative flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Presensi Masuk</h3>
                    <p class="text-xs text-gray-500">Saat Anda tiba di kantor.</p>
                </div>
            </div>

            <div class="relative mt-auto">
                @if($masukSudah)
                    <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-xl text-center">
                        <p class="text-2xl mb-1">✅</p>
                        <p class="text-sm font-bold">Tercatat Masuk</p>
                        <p class="text-xs mt-0.5">Pukul <b>{{ $presensiHariIni->jam_masuk }}</b> WIB</p>
                    </div>
                @else
                    <form action="{{ route('mahasiswa.presensi.masuk') }}" method="POST" @submit.prevent="submitForm($event)">
                        @csrf
                        <input type="hidden" name="lat" x-model="lat">
                        <input type="hidden" name="lng" x-model="lng">
                        <button type="submit" :disabled="isLoading || !lat"
                                class="w-full py-3.5 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold rounded-xl transition-all duration-300 shadow-md shadow-green-200 hover:shadow-lg hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0">
                            <span x-show="!isLoading">🟢 Absen Masuk Sekarang</span>
                            <span x-show="isLoading" x-cloak>Memproses...</span>
                        </button>
                        <p class="text-[11px] text-gray-400 mt-2 text-center" x-show="!lat && !gpsError" x-cloak>Menunggu koordinat GPS...</p>
                        <p class="text-[11px] text-red-500 mt-2 text-center" x-show="gpsError" x-cloak>Tekan "Perbarui Lokasi" di atas untuk mencoba lagi.</p>
                    </form>
                @endif
            </div>
        </div>

        <!-- ===== Presensi Keluar ===== -->
        <div class="anim-fade-up anim-delay-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col relative overflow-hidden group hover:shadow-lg transition-shadow duration-300">
            <div class="absolute -right-5 -top-5 w-28 h-28 bg-blue-50 rounded-full opacity-60 group-hover:scale-125 transition-transform duration-500"></div>
            <div class="relative flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Presensi Keluar</h3>
                    <p class="text-xs text-gray-500">Saat jam kerja berakhir.</p>
                </div>
            </div>

            <div class="relative mt-auto">
                @if($pulangSudah)
                    <div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl text-center">
                        <p class="text-2xl mb-1">🏁</p>
                        <p class="text-sm font-bold">Tercatat Keluar</p>
                        <p class="text-xs mt-0.5">Pukul <b>{{ $presensiHariIni->jam_pulang }}</b> WIB</p>
                        @if($durasiHariIni)
                            <p class="text-[11px] text-blue-600 mt-1.5 border-t border-blue-100 pt-1.5">Durasi kerja: <b>{{ $durasiHariIni }}</b></p>
                        @endif
                    </div>
                @elseif(!$masukSudah)
                    <div class="bg-gray-50 border border-gray-200 text-gray-500 p-4 rounded-xl text-center text-sm font-medium">
                        <p class="text-2xl mb-1">🔒</p>
                        Belum absen masuk
                        <p class="text-xs font-normal mt-1">Lakukan presensi masuk terlebih dahulu.</p>
                    </div>
                @else
                    <form action="{{ route('mahasiswa.presensi.keluar') }}" method="POST" @submit.prevent="submitForm($event)">
                        @csrf
                        <input type="hidden" name="lat" x-model="lat">
                        <input type="hidden" name="lng" x-model="lng">
                        <button type="submit" :disabled="isLoading || !lat"
                                class="w-full py-3.5 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold rounded-xl transition-all duration-300 shadow-md shadow-blue-200 hover:shadow-lg hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0">
                            <span x-show="!isLoading">🔵 Absen Keluar Sekarang</span>
                            <span x-show="isLoading" x-cloak>Memproses...</span>
                        </button>
                        <p class="text-[11px] text-gray-400 mt-2 text-center">Pastikan logbook hari ini sudah terisi sebelum absen keluar.</p>
                    </form>
                @endif
            </div>
        </div>

        <!-- ===== Presensi Kamera (Luar Kantor) ===== -->
        <div class="anim-fade-up anim-delay-3 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col relative overflow-hidden group hover:shadow-lg transition-shadow duration-300">
            <div class="absolute -right-5 -top-5 w-28 h-28 bg-orange-50 rounded-full opacity-60 group-hover:scale-125 transition-transform duration-500"></div>
            <div class="relative flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Luar Kantor?</h3>
                    <p class="text-xs text-gray-500">Presensi berbasis kamera.</p>
                </div>
            </div>

            <div class="relative mt-auto">
                <p class="text-xs text-gray-500 mb-4 leading-relaxed">Gunakan presensi berbasis kamera jika Anda berhalangan hadir di kantor. Akan menunggu <b>verifikasi admin</b>.</p>
                <button type="button" @click="openCamera()"
                        class="w-full py-3.5 bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-bold rounded-xl transition-all duration-300 shadow-md shadow-orange-200 hover:shadow-lg hover:-translate-y-0.5 flex justify-center items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Buka Kamera
                </button>
            </div>
        </div>
    </div>

    <!-- ================= RIWAYAT PRESENSI ================= -->
    <div class="anim-fade-up anim-delay-3 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <div>
                <h3 class="font-bold text-gray-800">📋 Riwayat Presensi</h3>
                <p class="text-xs text-gray-500 mt-0.5">Rekap kehadiran Anda per bulan.</p>
            </div>
            <!-- Filter Bulan (terkirim sebagai ?bulan=YYYY-MM) -->
            <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-2">
                <input type="month" name="bulan" value="{{ request('bulan', now()->format('Y-m')) }}"
                       onchange="this.form.submit()"
                       class="text-sm border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500">
                @if(request('bulan'))
                    <a href="{{ url()->current() }}" class="text-xs font-bold text-gray-400 hover:text-red-500">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Waktu Datang</th>
                        <th class="px-6 py-4">Waktu Pulang</th>
                        <th class="px-6 py-4">Durasi</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $row)
                        @php
                            $durasiBaris = null;
                            if ($row->jam_masuk && $row->jam_pulang) {
                                $menitBaris = (int) abs(\Carbon\Carbon::parse($row->jam_pulang)->diffInMinutes(\Carbon\Carbon::parse($row->jam_masuk)));
                                $durasiBaris = intdiv($menitBaris, 60) . 'j ' . ($menitBaris % 60) . 'm';
                            }
                        @endphp
                        <tr class="bg-white border-b hover:bg-indigo-50/40 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                @if($row->jam_masuk)
                                    <span class="inline-flex items-center gap-1.5 text-gray-800 font-medium">🟢 {{ $row->jam_masuk }}</span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($row->jam_pulang)
                                    <span class="inline-flex items-center gap-1.5 text-gray-800 font-medium">🔵 {{ $row->jam_pulang }}</span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $durasiBaris ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if($row->status_verifikasi === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                                        Menunggu Verifikasi
                                    </span>
                                @else
                                    <div class="flex flex-wrap gap-1">
                                        @if($row->status == 'terlambat')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Terlambat</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Tepat Waktu</span>
                                        @endif

                                        @if($row->keterangan == 'Pulang Terlalu Cepat')
                                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pulang Cepat</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-gray-100 flex items-center justify-center">
                                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <p class="text-gray-500 text-sm font-medium">Belum ada riwayat presensi pada bulan ini.</p>
                                <p class="text-gray-400 text-xs mt-1">Riwayat akan muncul setelah Anda melakukan presensi.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            {{ $riwayat->withQueryString()->links() }}
        </div>
    </div>

    <!-- ================= MODAL KAMERA ================= -->
    <div x-show="cameraOpen" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4"
         @keydown.escape.window="closeCamera()">

        <div class="absolute inset-0" @click="closeCamera()"></div>

        <div class="relative bg-white rounded-2xl max-w-md w-full overflow-hidden shadow-2xl"
             x-show="cameraOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

            <div class="p-4 border-b flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">📷 Presensi Luar Kantor</h3>
                <button @click="closeCamera()" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-500 hover:bg-red-50 hover:text-red-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-4">
                @if(session('error'))
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl">{{ session('error') }}</div>
                @endif

                <!-- Video Stream -->
                <div class="relative bg-black rounded-xl overflow-hidden aspect-video mb-4" x-show="!fotoCaptured">
                    <video x-ref="video" autoplay playsinline class="w-full h-full object-cover"></video>
                    <span class="absolute top-2 left-2 inline-flex items-center gap-1.5 px-2 py-1 rounded-lg bg-black/50 backdrop-blur text-white text-[10px] font-bold tracking-wider">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> LIVE
                    </span>
                    <p class="absolute bottom-2 inset-x-0 text-center text-white/70 text-xs" x-show="cameraError" x-text="cameraError"></p>
                </div>

                <!-- Preview Foto -->
                <div class="relative bg-black rounded-xl overflow-hidden aspect-video mb-4" x-show="fotoCaptured" x-cloak>
                    <img :src="fotoData" class="w-full h-full object-cover">
                    <span class="absolute top-2 left-2 px-2 py-1 rounded-lg bg-emerald-500 text-white text-[10px] font-bold tracking-wider">FOTO SIAP ✓</span>
                </div>

                <form action="{{ route('mahasiswa.presensi.kamera') }}" method="POST" @submit.prevent="submitKamera($event)">
                    @csrf
                    <input type="hidden" name="foto" x-model="fotoData">

                    <div class="mb-4" x-show="!fotoCaptured">
                        <button type="button" @click="ambilFoto()"
                                class="w-full py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl transition-all duration-300 shadow-md shadow-indigo-200">
                            📸 Ambil Foto
                        </button>
                    </div>

                    <div x-show="fotoCaptured" x-cloak>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Alasan Tidak Bisa Absen di Kantor</label>
                        <textarea name="alasan" rows="3" required
                                  class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 mb-4 text-sm"
                                  placeholder="Contoh: Sedang dinas luar, sakit, atau kendala perjalanan..."></textarea>

                        <div class="flex gap-2">
                            <button type="button" @click="retakeFoto()"
                                    class="w-1/3 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">
                                Ulangi
                            </button>
                            <button type="submit" :disabled="isLoading"
                                    class="w-2/3 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold rounded-xl transition-all duration-300 shadow-md shadow-green-200 disabled:opacity-50">
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
                // Jam real-time (langsung tampil, tidak menunggu 1 detik)
                const updateClock = () => {
                    const now = new Date();
                    this.jamSekarang = now.toLocaleTimeString('id-ID', { hour12: false });
                    this.tanggalSekarang = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                };
                updateClock();
                setInterval(updateClock, 1000);

                this.getLocation();
            },

            // Ambil koordinat GPS (bisa dipanggil ulang dari tombol "Perbarui Lokasi")
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
                        this.gpsError = 'Gagal mendapatkan lokasi. Pastikan izin lokasi diberikan.';
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

                // Hentikan stream lama (jika ada) sebelum membuka yang baru
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

                // Matikan kamera setelah foto diambil untuk hemat baterai
                this.stream.getTracks().forEach(track => track.stop());
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