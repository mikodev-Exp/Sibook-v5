@extends('layouts.role', ['role' => 'superuser'])

@section('title', 'Master Console - Super User')

@section('content')
<div class="space-y-8">
    {{-- Top Header Placard --}}
    <div class="bg-white rounded-3xl border border-zinc-200/80 p-8 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-mono-tag text-zinc-400 uppercase tracking-widest mb-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>MASTER CONSOLE // ROOT PRIVILEGES</span>
            </div>
            <h1 class="text-3xl font-extrabold text-zinc-950 tracking-tight">Pusat Kendali Sistem</h1>
            <p class="text-xs text-zinc-500 mt-1 max-w-xl leading-relaxed">
                Ringkasan operasional harian, audit permohonan magang, analitik presensi, dan logbook mahasiswa BPS Kota Surabaya.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 font-mono-tag text-xs">
            <div class="bg-zinc-50 px-4 py-2.5 rounded-2xl border border-zinc-200/80 text-right">
                <div class="text-lg font-bold text-zinc-950" id="clock">00:00:00</div>
                <div class="text-[10px] text-zinc-400">{{ now()->translatedFormat('l, d F Y') }}</div>
            </div>
            <a href="{{ route('superuser.permohonan.index') }}" 
               class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-zinc-950 text-white hover:bg-zinc-800 transition-all font-semibold shadow-sm hover:shadow">
                <span>Finalisasi Magang</span>
                @if($pengajuanMasuk > 0)
                    <span class="px-2 py-0.5 rounded-full bg-amber-400 text-zinc-950 text-[10px] font-bold">{{ $pengajuanMasuk }}</span>
                @endif
            </a>
        </div>
    </div>

    {{-- 4 KPI Placards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- 1. Menunggu Finalisasi (Obsidian Hero Card) -->
        <div class="bg-zinc-950 text-white p-7 rounded-3xl border border-zinc-800 shadow-xl relative overflow-hidden flex flex-col justify-between group">
            <div class="absolute -right-12 -top-12 w-40 h-40 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div>
                <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-wider block mb-2">FINAL REVIEW QUEUE</span>
                <p class="text-xs text-zinc-300 font-semibold mb-1">Menunggu Finalisasi</p>
                <h4 class="text-4xl font-extrabold font-mono-tag text-white tracking-tight mt-2">{{ $pengajuanMasuk }}</h4>
            </div>
            <div class="mt-6 pt-3.5 border-t border-zinc-800/80 flex items-center justify-between text-xs font-mono-tag">
                <span class="text-zinc-400">Berkas &amp; Izin</span>
                @if($pengajuanMasuk > 0)
                    <span class="text-amber-400 flex items-center gap-1 text-[11px] font-bold">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-ping"></span>
                        ACTION
                    </span>
                @else
                    <span class="text-zinc-500 text-[11px]">CLEAR</span>
                @endif
            </div>
        </div>

        <!-- 2. Mahasiswa Aktif -->
        <div class="bg-white p-7 rounded-3xl border border-zinc-200/80 shadow-sm flex flex-col justify-between hover:border-zinc-300 transition-colors">
            <div>
                <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-wider block mb-2">ACTIVE ARTISTS</span>
                <p class="text-xs text-zinc-500 font-semibold mb-1">Mahasiswa Aktif</p>
                <h4 class="text-4xl font-extrabold font-mono-tag text-zinc-950 tracking-tight mt-2">{{ $totalMahasiswa }}</h4>
            </div>
            <div class="mt-6 pt-3.5 border-t border-zinc-100 flex items-center justify-between text-xs font-mono-tag text-zinc-400">
                <span>ROLE: MAHASISWA</span>
                <span class="text-zinc-600 font-semibold text-[11px]">TERVERIFIKASI</span>
            </div>
        </div>

        <!-- 3. Total Admin -->
        <div class="bg-white p-7 rounded-3xl border border-zinc-200/80 shadow-sm flex flex-col justify-between hover:border-zinc-300 transition-colors">
            <div>
                <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-wider block mb-2">CURATORIAL STAFF</span>
                <p class="text-xs text-zinc-500 font-semibold mb-1">Total Admin &amp; Kurator</p>
                <h4 class="text-4xl font-extrabold font-mono-tag text-zinc-950 tracking-tight mt-2">{{ $totalAdmin }}</h4>
            </div>
            <div class="mt-6 pt-3.5 border-t border-zinc-100 flex items-center justify-between text-xs font-mono-tag text-zinc-400">
                <span>ROLE: ADMIN</span>
                <span class="text-zinc-600 font-semibold text-[11px]">PENGELOLA</span>
            </div>
        </div>

        <!-- 4. Logbook Pending -->
        <div class="bg-white p-7 rounded-3xl border border-zinc-200/80 shadow-sm flex flex-col justify-between hover:border-zinc-300 transition-colors">
            <div>
                <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-wider block mb-2">DRAFT ACTIVITY</span>
                <p class="text-xs text-zinc-500 font-semibold mb-1">Logbook Pending</p>
                <h4 class="text-4xl font-extrabold font-mono-tag text-zinc-950 tracking-tight mt-2">{{ $logbookPending }}</h4>
            </div>
            <div class="mt-6 pt-3.5 border-t border-zinc-100 flex items-center justify-between text-xs font-mono-tag text-zinc-400">
                <span>STATUS DRAFT</span>
                <span class="{{ $logbookPending > 0 ? 'text-amber-600 font-bold' : 'text-zinc-500' }} text-[11px]">
                    {{ $logbookPending > 0 ? 'BUTUH REVIEW' : 'SEMUA SELESAI' }}
                </span>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Grafik Tren Kehadiran -->
        <div class="lg:col-span-2 bg-white p-7 rounded-3xl border border-zinc-200/80 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-zinc-100">
                <div>
                    <h3 class="font-bold text-zinc-900 text-sm">Tren Kehadiran Mahasiswa (7 Hari Terakhir)</h3>
                    <p class="text-xs text-zinc-500">Statistik perbandingan hadir tepat waktu, terlambat, dan izin.</p>
                </div>
                <div class="flex gap-4 text-xs font-mono-tag">
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 bg-emerald-500 rounded-full"></span> Hadir</span>
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 bg-amber-500 rounded-full"></span> Terlambat</span>
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 bg-blue-500 rounded-full"></span> Izin</span>
                </div>
            </div>
            <div class="h-64 w-full">
                <canvas id="chartTren"></canvas>
            </div>
        </div>

        <!-- Grafik Distribusi Tim -->
        <div class="bg-white p-7 rounded-3xl border border-zinc-200/80 shadow-sm flex flex-col justify-between">
            <div class="mb-4 pb-4 border-b border-zinc-100">
                <h3 class="font-bold text-zinc-900 text-sm">Distribusi Divisi / Tim</h3>
                <p class="text-xs text-zinc-500">Aktivitas penugasan berdasarkan unit tim.</p>
            </div>
            <div class="h-60 w-full flex items-center justify-center">
                <canvas id="chartDivisi"></canvas>
            </div>
        </div>
    </div>

    {{-- Bottom Grid: Presensi Masuk & Asal Perguruan Tinggi --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Presensi Masuk Terkini -->
        <div class="bg-white p-7 rounded-3xl border border-zinc-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-zinc-100">
                <div>
                    <h3 class="font-bold text-zinc-900 text-sm">Presensi Masuk Terkini</h3>
                    <p class="text-xs text-zinc-500">Aktivitas kehadiran mahasiswa magang terbaru.</p>
                </div>
                <span class="text-xs font-mono-tag text-zinc-400">TODAY</span>
            </div>

            <div class="space-y-3">
                @forelse($recentPresensi as $presensi)
                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-zinc-50/70 border border-zinc-200/60 hover:bg-zinc-100/60 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-zinc-950 text-white flex items-center justify-center font-bold text-xs font-mono-tag">
                                {{ strtoupper(substr($presensi->user->name ?? '?', 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-xs font-bold text-zinc-900">{{ $presensi->user->name ?? 'Unknown' }}</p>
                                <p class="text-[11px] text-zinc-500 font-mono-tag">
                                    {{ $presensi->user->mahasiswaProfile->universitas ?? '-' }} • {{ $presensi->tipe_presensi === 'kantor' ? 'WFO Kantor' : 'Dinas Luar' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right font-mono-tag">
                            <p class="text-xs font-bold text-zinc-900">{{ substr($presensi->jam_masuk ?? '--:--', 0, 5) }} WIB</p>
                            <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] font-bold {{ $presensi->status === \App\Enums\StatusPresensi::Hadir ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                {{ $presensi->status->label() }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-zinc-400 italic text-center py-6 font-mono-tag">Belum ada data presensi hari ini.</p>
                @endforelse
            </div>
        </div>

        <!-- Perguruan Tinggi Asal -->
        <div class="bg-white p-7 rounded-3xl border border-zinc-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-zinc-100">
                <div>
                    <h3 class="font-bold text-zinc-900 text-sm">Perguruan Tinggi Asal</h3>
                    <p class="text-xs text-zinc-500">Sebaran institusi mahasiswa magang.</p>
                </div>
                <span class="px-2.5 py-1 bg-zinc-100 text-zinc-700 text-xs font-mono-tag font-bold rounded-lg border border-zinc-200">
                    {{ $totalMhsProfile }} Mahasiswa
                </span>
            </div>

            <div class="space-y-4">
                @forelse($universitasData as $univ)
                    @php
                        $persen = $totalMhsProfile > 0 ? round(($univ->total / $totalMhsProfile) * 100) : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between text-xs font-mono-tag text-zinc-700 mb-1.5">
                            <span class="font-bold truncate pr-2">{{ $univ->universitas }}</span>
                            <span class="text-zinc-500 shrink-0">{{ $univ->total }} mhs ({{ $persen }}%)</span>
                        </div>
                        <div class="w-full bg-zinc-100 rounded-full h-2 overflow-hidden">
                            <div class="h-2 rounded-full {{ $loop->first ? 'bg-zinc-950' : ($loop->iteration == 2 ? 'bg-indigo-600' : 'bg-zinc-400') }}" 
                                 style="width: {{ $persen }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-zinc-400 italic text-center py-6 font-mono-tag">Belum ada data universitas.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    // Realtime digital clock (HH:mm:ss)
    setInterval(function() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const el = document.getElementById('clock');
        if (el) el.textContent = `${hours}:${minutes}:${seconds} WIB`;
    }, 1000);
</script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. GRAFIK TREN KEHADIRAN (Line Chart) ---
        const elTren = document.getElementById('chartTren');
        if (elTren) {
            const ctxTren = elTren.getContext('2d');
            new Chart(ctxTren, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartDates) !!},
                    datasets: [
                        {
                            label: 'Hadir',
                            data: {!! json_encode($chartHadir) !!},
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.08)',
                            tension: 0.3,
                            fill: true,
                            borderWidth: 2,
                            pointRadius: 3,
                        },
                        {
                            label: 'Terlambat',
                            data: {!! json_encode($chartTerlambat) !!},
                            borderColor: '#f59e0b',
                            backgroundColor: 'rgba(245, 158, 11, 0.08)',
                            tension: 0.3,
                            fill: true,
                            borderWidth: 2,
                            pointRadius: 3,
                        },
                        {
                            label: 'Izin/Sakit',
                            data: {!! json_encode($chartIzin) !!},
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.08)',
                            tension: 0.3,
                            fill: true,
                            borderWidth: 2,
                            pointRadius: 3,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: '#09090b',
                            titleFont: { size: 12 },
                            bodyFont: { size: 11 },
                            padding: 10,
                            cornerRadius: 8,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: '#a1a1aa', font: { family: 'Space Mono', size: 10 } },
                            grid: { borderDash: [4, 4], color: '#f4f4f5' }
                        },
                        x: {
                            ticks: { color: '#a1a1aa', font: { family: 'Space Mono', size: 10 } },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // --- 2. GRAFIK DISTRIBUSI TIM (Donut Chart) ---
        const elDivisi = document.getElementById('chartDivisi');
        if (elDivisi) {
            const ctxDivisi = elDivisi.getContext('2d');
            const bgColors = ['#09090b', '#6366f1', '#10b981', '#f59e0b', '#ec4899', '#06b6d4'];

            new Chart(ctxDivisi, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($timLabels) !!},
                    datasets: [{
                        data: {!! json_encode($timData) !!},
                        backgroundColor: bgColors,
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 6,
                                font: { family: 'Space Mono', size: 10 }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
