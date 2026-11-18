@extends('layouts.role', ['role' => 'admin'])

@section('title', 'Dashboard Interaktif Admin')

@section('content')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(18px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .anim-fade-up { animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both; }
    .anim-delay-1 { animation-delay: 0.08s; }
    .anim-delay-2 { animation-delay: 0.16s; }
    .anim-delay-3 { animation-delay: 0.24s; }
    .anim-delay-4 { animation-delay: 0.32s; }
    .anim-delay-5 { animation-delay: 0.40s; }

    @keyframes pulseGlow {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.6; transform: scale(1.08); }
    }
    .pulse-dot { animation: pulseGlow 2s infinite ease-in-out; }
</style>

<div class="max-w-[1400px] mx-auto space-y-6">

    {{-- ================= HERO GREETING & REAL-TIME CLOCK ================= --}}
    <div x-data="liveClock()" class="anim-fade-up bg-white rounded-3xl border border-gray-150 p-6 lg:p-7 shadow-[0_2px_8px_rgba(0,0,0,0.02)] relative overflow-hidden">
        {{-- Subtle decorative gradient glow behind --}}
        <div class="absolute -top-16 -right-16 w-64 h-64 bg-gradient-to-br from-orange-100/60 via-amber-50/40 to-transparent rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 w-64 h-64 bg-gradient-to-tr from-indigo-50/50 to-transparent rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            {{-- Left: Dynamic Greeting --}}
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-50 border border-orange-200/80 text-[11px] font-semibold text-orange-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 pulse-dot"></span>
                    <span>Sistem SiBook v5 • BPS Kota Surabaya</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-extrabold text-[#111827] tracking-tight">
                    <span x-text="greeting">Selamat Bekerja</span>, {{ auth()->user()->name }}!
                </h1>
                <p class="text-xs lg:text-sm text-gray-500 max-w-xl">
                    Berikut adalah ringkasan operasional harian, statistik kehadiran, dan verifikasi aktivitas mahasiswa magang terkini.
                </p>
            </div>

            {{-- Right: Live Digital Clock & Quick Actions --}}
            <div class="flex flex-col sm:flex-row md:flex-col lg:flex-row items-start sm:items-center gap-3">
                <div class="bg-gray-50/90 border border-gray-150 px-4 py-2.5 rounded-2xl flex items-center gap-3 shadow-inner">
                    <div class="w-8 h-8 rounded-xl bg-white text-orange-600 flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-mono text-base font-bold text-gray-900 tracking-wider" x-text="timeString">--:--:-- WIB</div>
                        <div class="text-[11px] text-gray-400" x-text="dateString">Memuat tanggal...</div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.logbook.index') }}" 
                       class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-[#EA580C] hover:bg-orange-700 text-white rounded-xl text-xs font-semibold shadow-sm transition transform hover:-translate-y-0.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Verifikasi Logbook</span>
                        @if($logbookPending > 0)
                            <span class="ml-1 px-1.5 py-0.2 bg-white/25 rounded-md text-[10px] font-bold">{{ $logbookPending }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= 4 ANIMATED KPI CARDS ================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        {{-- Card 1: Mahasiswa Magang Aktif --}}
        <div x-data="animatedCounter({{ $totalMahasiswa }})" class="anim-fade-up anim-delay-1 bg-white rounded-2xl border border-gray-150 p-5 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-emerald-300 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Mahasiswa Aktif</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-gray-900 tracking-tight" x-text="current">0</span>
                <span class="text-xs text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.5 rounded-md">Terdaftar Aktif</span>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                <span class="text-gray-400">Total Pendaftar: {{ $totalPendaftar }}</span>
                <a href="{{ route('admin.mahasiswa.index') }}" class="font-semibold text-emerald-600 hover:text-emerald-800 flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
                    <span>Lihat Mahasiswa</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        {{-- Card 2: Kehadiran Hari Ini --}}
        <div x-data="animatedCounter({{ $presensiHariIniCount }})" class="anim-fade-up anim-delay-2 bg-white rounded-2xl border border-gray-150 p-5 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-orange-300 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Presensi Hari Ini</span>
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-gray-900 tracking-tight" x-text="current">0</span>
                <span class="text-xs text-orange-700 font-semibold bg-orange-50 px-2 py-0.5 rounded-md">
                    {{ $tingkatKehadiranHariIni }}% Hadir
                </span>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                <span class="text-gray-400">{{ $presensiHadirHariIni }} Tepat • {{ $presensiTerlambatHariIni }} Terlambat</span>
                <a href="{{ route('admin.presensi.index') }}" class="font-semibold text-orange-600 hover:text-orange-800 flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
                    <span>Monitoring</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        {{-- Card 3: Verifikasi Logbook Menunggu --}}
        <div x-data="animatedCounter({{ $logbookPending }})" class="anim-fade-up anim-delay-3 bg-white rounded-2xl border border-gray-150 p-5 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-amber-300 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Logbook Pending</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-gray-900 tracking-tight" x-text="current">0</span>
                @if($logbookPending > 0)
                    <span class="text-xs text-amber-700 font-semibold bg-amber-50 px-2 py-0.5 rounded-md">Perlu Ditinjau</span>
                @else
                    <span class="text-xs text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.5 rounded-md">Semua Beres</span>
                @endif
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                <span class="text-gray-400">Verifikasi harian</span>
                <a href="{{ route('admin.logbook.index', ['status' => 'submitted']) }}" class="font-semibold text-amber-600 hover:text-amber-800 flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
                    <span>Review Sekarang</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        {{-- Card 4: Pengajuan Permohonan & Izin --}}
        <div x-data="animatedCounter({{ $permohonanPending + $perizinanPending }})" class="anim-fade-up anim-delay-4 bg-white rounded-2xl border border-gray-150 p-5 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:border-indigo-300 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengajuan Masuk</span>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-gray-900 tracking-tight" x-text="current">0</span>
                <span class="text-xs text-indigo-700 font-semibold bg-indigo-50 px-2 py-0.5 rounded-md">Berkas & Izin</span>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                <span class="text-gray-400">{{ $permohonanPending }} Magang • {{ $perizinanPending }} Izin</span>
                <a href="{{ route('admin.permohonan.index') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
                    <span>Buka Berkas</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

    </div>

    {{-- ================= SECTION 2 CHARTS INTERAKTIF ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Chart 1: Tren Kehadiran Mahasiswa (Line / Area Chart) --}}
        <div class="anim-fade-up anim-delay-2 lg:col-span-2 bg-white rounded-3xl border border-gray-150 p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-4 border-b border-gray-100">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Tren Kehadiran Mahasiswa Magang</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Statistik perbandingan hadir tepat waktu, terlambat, dan izin.</p>
                </div>
                <div class="flex items-center gap-3 text-xs">
                    <span class="flex items-center gap-1.5 text-gray-600">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span> Hadir
                    </span>
                    <span class="flex items-center gap-1.5 text-gray-600">
                        <span class="w-3 h-3 rounded-full bg-amber-500"></span> Terlambat
                    </span>
                    <span class="flex items-center gap-1.5 text-gray-600">
                        <span class="w-3 h-3 rounded-full bg-blue-500"></span> Izin
                    </span>
                </div>
            </div>

            <div class="relative h-72 w-full">
                <canvas id="kehadiranChart"></canvas>
            </div>
        </div>

        {{-- Chart 2: Distribusi Kegiatan per Divisi / Tim (Doughnut Chart) --}}
        <div class="anim-fade-up anim-delay-3 bg-white rounded-3xl border border-gray-150 p-6 shadow-sm flex flex-col justify-between">
            <div class="mb-4 pb-3 border-b border-gray-100">
                <h3 class="text-base font-bold text-gray-900">Distribusi Divisi / Tim</h3>
                <p class="text-xs text-gray-500 mt-0.5">Proporsi aktivitas logbook berdasarkan unit penugasan.</p>
            </div>

            <div class="relative h-56 w-full flex items-center justify-center my-auto">
                <canvas id="divisiChart"></canvas>
            </div>

            <div class="mt-4 pt-3 border-t border-gray-100 flex flex-wrap items-center justify-center gap-x-4 gap-y-1.5 text-xs text-gray-600">
                @foreach($timDistribution as $namaTim => $total)
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ match($namaTim) {
                            'IPDS' => '#0FAF62',
                            'Humas' => '#3B82F6',
                            'Sosial' => '#EF4444',
                            'PSS' => '#7C3AED',
                            'Neraca' => '#F59E0B',
                            default => '#6B7280'
                        } }}"></span>
                        <span class="font-medium text-gray-700">{{ $namaTim }}</span>
                        <span class="text-gray-400">({{ $total }})</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- ================= SECTION LIVE FEEDS & UNIVERSITAS ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Feed Presensi Terkini --}}
        <div class="anim-fade-up anim-delay-3 lg:col-span-2 bg-white rounded-3xl border border-gray-150 p-6 shadow-sm">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Presensi Masuk Terkini</h3>
                        <p class="text-xs text-gray-500">Aktivitas kehadiran mahasiswa magang terbaru.</p>
                    </div>
                </div>
                <a href="{{ route('admin.presensi.index') }}" class="text-xs font-semibold text-orange-600 hover:text-orange-800">
                    Semua Presensi &rarr;
                </a>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($presensiTerbaru as $p)
                    @php
                        $userP = $p->user;
                        $statusP = $p->status?->value ?? (string) $p->status;
                        $badgeStyle = match($statusP) {
                            'hadir' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'terlambat' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'izin' => 'bg-blue-50 text-blue-700 border-blue-200',
                            default => 'bg-rose-50 text-rose-700 border-rose-200',
                        };
                    @endphp
                    <div class="py-3 flex items-center justify-between hover:bg-gray-50/70 rounded-xl px-2.5 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-700 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                {{ strtoupper(substr($userP->name ?? 'M', 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-900">{{ $userP->name ?? '-' }}</h4>
                                <p class="text-[11px] text-gray-400">
                                    {{ $userP->mahasiswaProfile->universitas ?? '-' }} • 
                                    <span class="capitalize">{{ str_replace('_', ' ', $p->tipe_presensi ?? 'kantor') }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="text-right">
                                <span class="text-xs font-mono font-semibold text-gray-800">
                                    {{ $p->jam_masuk ? substr($p->jam_masuk, 0, 5) : '-' }}
                                </span>
                                <span class="block text-[10px] text-gray-400">
                                    {{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d M') }}
                                </span>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold border {{ $badgeStyle }}">
                                {{ ucfirst($statusP) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-gray-400">
                        <p class="text-sm font-medium">Belum ada presensi tercatat hari ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Top Universitas Mitra --}}
        <div class="anim-fade-up anim-delay-4 bg-white rounded-3xl border border-gray-150 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Perguruan Tinggi Asal</h3>
                        <p class="text-xs text-gray-500">Sebaran universitas mahasiswa magang.</p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-gray-100 text-gray-700 rounded-lg">
                        {{ $totalMahasiswa }} Mahasiswa
                    </span>
                </div>

                <div class="space-y-4">
                    @forelse($topUniversitas as $idx => $univ)
                        @php
                            $pct = $totalMahasiswa > 0 ? round(($univ->total / $totalMahasiswa) * 100) : 0;
                            $barColors = ['bg-orange-500', 'bg-blue-500', 'bg-emerald-500', 'bg-purple-500'];
                            $color = $barColors[$idx % count($barColors)];
                        @endphp
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-semibold text-gray-800 truncate max-w-[200px]" title="{{ $univ->universitas }}">
                                    {{ $univ->universitas }}
                                </span>
                                <span class="text-gray-500 font-mono">{{ $univ->total }} orang ({{ $pct }}%)</span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full rounded-full {{ $color }} transition-all duration-1000 ease-out" 
                                     style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-gray-400 text-xs">
                            Data universitas mahasiswa belum lengkap.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Pintasan Navigasi Tambahan --}}
            <div class="mt-6 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.mahasiswa.index') }}" 
                   class="w-full flex items-center justify-center gap-2 py-2.5 bg-gray-50 hover:bg-orange-50 text-gray-700 hover:text-orange-600 rounded-xl text-xs font-semibold border border-gray-200 hover:border-orange-200 transition">
                    <span>Lihat Rekap Seluruh Mahasiswa</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

    </div>

</div>

{{-- SCRIPT INTERAKTIF DASHBOARD: CLOCK, COUNTER, CHART.JS --}}
<script>
    // Live Ticking Clock Component
    function liveClock() {
        return {
            timeString: '',
            dateString: '',
            greeting: 'Selamat Bekerja',

            init() {
                const update = () => {
                    const now = new Date();
                    
                    // Time
                    this.timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
                    
                    // Date
                    this.dateString = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });

                    // Greeting
                    const hour = now.getHours();
                    if (hour >= 4 && hour < 11) {
                        this.greeting = 'Selamat Pagi';
                    } else if (hour >= 11 && hour < 15) {
                        this.greeting = 'Selamat Siang';
                    } else if (hour >= 15 && hour < 18) {
                        this.greeting = 'Selamat Sore';
                    } else {
                        this.greeting = 'Selamat Malam';
                    }
                };
                update();
                setInterval(update, 1000);
            }
        };
    }

    // Animated Count-Up for KPI Metrics
    function animatedCounter(target) {
        return {
            target: target || 0,
            current: 0,
            init() {
                if (this.target <= 0) {
                    this.current = 0;
                    return;
                }
                const duration = 1200;
                const steps = 30;
                const stepVal = Math.ceil(this.target / steps);
                let currentStep = 0;

                const timer = setInterval(() => {
                    currentStep++;
                    this.current = Math.min(this.target, currentStep * stepVal);
                    if (this.current >= this.target) {
                        this.current = this.target;
                        clearInterval(timer);
                    }
                }, duration / steps);
            }
        };
    }

    // Inisialisasi Chart.js setelah DOM Ready
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Line Chart: Tren Kehadiran
        const ctxKehadiran = document.getElementById('kehadiranChart')?.getContext('2d');
        if (ctxKehadiran) {
            // Gradient fill untuk Hadir
            const gradHadir = ctxKehadiran.createLinearGradient(0, 0, 0, 280);
            gradHadir.addColorStop(0, 'rgba(16, 185, 129, 0.28)');
            gradHadir.addColorStop(1, 'rgba(16, 185, 129, 0.00)');

            // Gradient fill untuk Terlambat
            const gradTerlambat = ctxKehadiran.createLinearGradient(0, 0, 0, 280);
            gradTerlambat.addColorStop(0, 'rgba(245, 158, 11, 0.20)');
            gradTerlambat.addColorStop(1, 'rgba(245, 158, 11, 0.00)');

            new Chart(ctxKehadiran, {
                type: 'line',
                data: {
                    labels: @json($trendLabels),
                    datasets: [
                        {
                            label: 'Hadir Tepat Waktu',
                            data: @json($trendHadir),
                            borderColor: '#10B981',
                            backgroundColor: gradHadir,
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.38,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#10B981',
                        },
                        {
                            label: 'Terlambat',
                            data: @json($trendTerlambat),
                            borderColor: '#F59E0B',
                            backgroundColor: gradTerlambat,
                            borderWidth: 2,
                            fill: true,
                            tension: 0.38,
                            pointRadius: 3.5,
                            pointHoverRadius: 5.5,
                            pointBackgroundColor: '#F59E0B',
                        },
                        {
                            label: 'Izin / Sakit',
                            data: @json($trendIzin),
                            borderColor: '#3B82F6',
                            borderDash: [5, 5],
                            borderWidth: 1.8,
                            fill: false,
                            tension: 0.38,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            pointBackgroundColor: '#3B82F6',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1400,
                        easing: 'easeOutQuart'
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1E293B',
                            titleFont: { size: 12, family: 'Figtree' },
                            bodyFont: { size: 11, family: 'Figtree' },
                            padding: 10,
                            cornerRadius: 10,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: { size: 11, family: 'Figtree' },
                                color: '#94A3B8'
                            },
                            grid: {
                                color: '#F1F5F9'
                            }
                        },
                        x: {
                            ticks: {
                                font: { size: 11, family: 'Figtree' },
                                color: '#94A3B8'
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // 2. Doughnut Chart: Distribusi Divisi
        const ctxDivisi = document.getElementById('divisiChart')?.getContext('2d');
        if (ctxDivisi) {
            const timData = @json($timDistribution);
            const labels = Object.keys(timData);
            const dataValues = Object.values(timData);

            const colors = labels.map(tim => {
                switch(tim) {
                    case 'IPDS': return '#0FAF62';
                    case 'Humas': return '#3B82F6';
                    case 'Sosial': return '#EF4444';
                    case 'PSS': return '#7C3AED';
                    case 'Neraca': return '#F59E0B';
                    default: return '#94A3B8';
                }
            });

            new Chart(ctxDivisi, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: dataValues,
                        backgroundColor: colors,
                        borderWidth: 3,
                        borderColor: '#FFFFFF',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 1400,
                        easing: 'easeOutQuart'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1E293B',
                            titleFont: { size: 12, family: 'Figtree' },
                            bodyFont: { size: 11, family: 'Figtree' },
                            padding: 10,
                            cornerRadius: 10,
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
