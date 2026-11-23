@extends('layouts.role', ['role' => 'superuser'])

@section('title', 'Pengaturan Sistem - Super User')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    {{-- Alerts --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs font-mono-tag">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs space-y-1">
            <span class="font-bold block mb-1">Periksa kembali data yang dimasukkan:</span>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Top Header Placard --}}
    <div class="bg-white rounded-3xl border border-zinc-200/80 p-8 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-mono-tag text-zinc-400 uppercase tracking-widest mb-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>SYSTEM CONFIGURATION // ROOT PRIVILEGES</span>
            </div>
            <h1 class="text-3xl font-extrabold text-zinc-950 tracking-tight">Pengaturan Sistem &amp; Geofencing</h1>
            <p class="text-xs text-zinc-500 mt-1 max-w-xl leading-relaxed">
                Konfigurasi parameter titik koordinat kantor, radius geofencing presensi, dan jadwal operasional harian BPS Kota Surabaya.
            </p>
        </div>

        <div class="flex items-center gap-2 font-mono-tag text-xs text-zinc-500">
            <span class="px-3.5 py-1.5 rounded-xl bg-zinc-100 border border-zinc-200 text-zinc-700">
                CONFIG: PRODUCTION
            </span>
        </div>
    </div>

    {{-- Form Pengaturan --}}
    <form action="{{ route('superuser.pengaturan.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- 1. Lokasi & Geofence Kantor --}}
        <div class="bg-white rounded-3xl border border-zinc-200/80 p-8 shadow-sm space-y-6">
            <div class="border-b border-zinc-100 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <span class="text-[10px] font-mono-tag uppercase tracking-widest text-zinc-400 block mb-0.5">// GEOFENCING PROTOCOL</span>
                    <h3 class="text-base font-bold text-zinc-950">Lokasi Kantor &amp; Radius Presensi</h3>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" 
                            onclick="getLocation()"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-xs font-mono-tag transition">
                        <svg class="w-3.5 h-3.5 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Deteksi GPS Saya</span>
                    </button>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $settings['kantor_lat'] }},{{ $settings['kantor_lng'] }}" 
                       target="_blank" 
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-zinc-50 hover:bg-zinc-100 border border-zinc-200 text-zinc-600 text-xs font-mono-tag transition">
                        <span>Buka Maps &rarr;</span>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- Nama Kantor --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-zinc-700 mb-2 font-mono-tag uppercase">Nama Instansi / Kantor</label>
                    <input type="text" 
                           name="kantor_nama" 
                           value="{{ old('kantor_nama', $settings['kantor_nama']) }}" 
                           required 
                           class="w-full px-4 py-3 rounded-2xl border border-zinc-200 text-xs text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 focus:border-zinc-950 transition">
                </div>

                {{-- Latitude --}}
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-2 font-mono-tag uppercase">Latitude (Lintang)</label>
                    <input type="number" 
                           step="any" 
                           name="kantor_lat" 
                           id="kantor_lat" 
                           value="{{ old('kantor_lat', $settings['kantor_lat']) }}" 
                           required 
                           class="w-full px-4 py-3 rounded-2xl border border-zinc-200 font-mono text-xs text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 focus:border-zinc-950 transition">
                    <p class="text-[10px] text-zinc-400 mt-1 font-mono-tag">Format desimal (contoh: -7.3283024)</p>
                </div>

                {{-- Longitude --}}
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-2 font-mono-tag uppercase">Longitude (Bujur)</label>
                    <input type="number" 
                           step="any" 
                           name="kantor_lng" 
                           id="kantor_lng" 
                           value="{{ old('kantor_lng', $settings['kantor_lng']) }}" 
                           required 
                           class="w-full px-4 py-3 rounded-2xl border border-zinc-200 font-mono text-xs text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 focus:border-zinc-950 transition">
                    <p class="text-[10px] text-zinc-400 mt-1 font-mono-tag">Format desimal (contoh: 112.7258666)</p>
                </div>

                {{-- Radius Presensi --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-zinc-700 mb-2 font-mono-tag uppercase">Radius Toleransi Geofencing (Meter)</label>
                    <div class="relative">
                        <input type="number" 
                               name="kantor_radius_m" 
                               value="{{ old('kantor_radius_m', $settings['kantor_radius_m']) }}" 
                               min="10" 
                               max="50000" 
                               required 
                               class="w-full px-4 py-3 rounded-2xl border border-zinc-200 font-mono text-xs text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 focus:border-zinc-950 transition pr-16">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-mono-tag text-zinc-400">METER</span>
                    </div>
                    <p class="text-[10px] text-zinc-400 mt-1.5 font-mono-tag">
                        Jarak maksimal mahasiswa dari titik koordinat kantor agar presensi dinyatakan valid (WFO Kantor).
                    </p>
                </div>
            </div>
        </div>

        {{-- 2. Parameter Jam Kerja Operasional --}}
        <div class="bg-white rounded-3xl border border-zinc-200/80 p-8 shadow-sm space-y-6">
            <div class="border-b border-zinc-100 pb-4">
                <span class="text-[10px] font-mono-tag uppercase tracking-widest text-zinc-400 block mb-0.5">// OPERATIONAL SCHEDULE</span>
                <h3 class="text-base font-bold text-zinc-950">Jam Kerja &amp; Batas Presensi</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                {{-- Jam Masuk Mulai --}}
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-2 font-mono-tag uppercase">Jam Mulai Masuk</label>
                    <input type="time" 
                           name="jam_masuk_mulai" 
                           value="{{ old('jam_masuk_mulai', $settings['jam_masuk_mulai']) }}" 
                           required 
                           class="w-full px-4 py-3 rounded-2xl border border-zinc-200 font-mono text-xs text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 focus:border-zinc-950 transition">
                    <p class="text-[10px] text-zinc-400 mt-1 font-mono-tag">Pintu presensi masuk dibuka</p>
                </div>

                {{-- Jam Masuk Batas --}}
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-2 font-mono-tag uppercase">Batas Akhir Presensi Masuk</label>
                    <input type="time" 
                           name="jam_masuk_batas" 
                           value="{{ old('jam_masuk_batas', $settings['jam_masuk_batas']) }}" 
                           required 
                           class="w-full px-4 py-3 rounded-2xl border border-zinc-200 font-mono text-xs text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 focus:border-zinc-950 transition">
                    <p class="text-[10px] text-zinc-400 mt-1 font-mono-tag">Setelah jam ini dinyatakan Terlambat</p>
                </div>

                {{-- Jam Pulang Min --}}
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 mb-2 font-mono-tag uppercase">Jam Pulang Minimal</label>
                    <input type="time" 
                           name="jam_pulang_min" 
                           value="{{ old('jam_pulang_min', $settings['jam_pulang_min']) }}" 
                           required 
                           class="w-full px-4 py-3 rounded-2xl border border-zinc-200 font-mono text-xs text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 focus:border-zinc-950 transition">
                    <p class="text-[10px] text-zinc-400 mt-1 font-mono-tag">Presensi pulang diperbolehkan</p>
                </div>

                {{-- Timezone --}}
                <div class="sm:col-span-3">
                    <label class="block text-xs font-semibold text-zinc-700 mb-2 font-mono-tag uppercase">Zona Waktu Sistem</label>
                    <select name="timezone" 
                            required 
                            class="w-full px-4 py-3 rounded-2xl border border-zinc-200 text-xs text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 focus:border-zinc-950 transition">
                        <option value="Asia/Jakarta" {{ old('timezone', $settings['timezone']) === 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB - Waktu Indonesia Barat)</option>
                        <option value="Asia/Makassar" {{ old('timezone', $settings['timezone']) === 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar (WITA - Waktu Indonesia Tengah)</option>
                        <option value="Asia/Jayapura" {{ old('timezone', $settings['timezone']) === 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura (WIT - Waktu Indonesia Timur)</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-semibold transition-all shadow-md hover:shadow-lg">
                <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Simpan Seluruh Pengaturan</span>
            </button>
        </div>
    </form>
</div>

<script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(pos) {
                document.getElementById('kantor_lat').value = pos.coords.latitude.toFixed(7);
                document.getElementById('kantor_lng').value = pos.coords.longitude.toFixed(7);
                alert("Koordinat GPS berhasil diperbarui sesuai lokasi perangkat Anda saat ini: " + pos.coords.latitude.toFixed(7) + ", " + pos.coords.longitude.toFixed(7));
            }, function(err) {
                alert("Gagal membaca GPS: " + err.message);
            });
        } else {
            alert("Perangkat atau browser tidak mendukung fitur Geolocation.");
        }
    }
</script>
@endsection
