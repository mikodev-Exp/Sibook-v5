@extends('layouts.role', ['role' => 'superuser'])

@section('title', 'Dashboard Super User')

@section('content')
<div class="space-y-6">
    <!-- ================= BAGIAN ATAS ================= -->
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-orange-50 to-white rounded-2xl shadow-sm border border-orange-100 p-6 flex flex-col md:flex-row justify-between items-center">
        <div>
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-100 text-orange-600 text-xs font-bold mb-3">
                <span class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></span>
                Sistem SiBook v5 • BPS Kota Surabaya
            </span>
            <h2 class="text-3xl font-bold text-gray-800">Selamat Pagi, Super User!</h2>
            <p class="text-gray-500 mt-1">Berikut adalah ringkasan operasional harian, statistik kehadiran, dan verifikasi aktivitas mahasiswa magang terkini.</p>
        </div>
        <div class="flex items-center gap-4 mt-4 md:mt-0">
            <div class="bg-white px-6 py-3 rounded-xl shadow-sm border border-gray-100 text-center">
                <div class="text-2xl font-bold text-gray-800" id="clock">00.00.00</div>
                <div class="text-xs text-gray-500 font-medium">WIB</div>
                <div class="text-[10px] text-gray-400 mt-1">{{ now()->translatedFormat('l, d F Y') }}</div>
            </div>
            <a href="{{ route('superuser.permohonan.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-4 rounded-xl shadow-md transition-colors flex items-center gap-2 font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Finalisasi Magang
            </a>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Admin -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Admin</h3>
                <div class="w-8 h-8 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-800 mb-2">{{ $totalAdmin }}</div>
            <div class="text-xs text-gray-500">Pengguna Admin</div>
        </div>

        <!-- Mahasiswa Aktif -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Mahasiswa Aktif</h3>
                <div class="w-8 h-8 bg-green-50 text-green-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-800 mb-2">{{ $totalMahasiswa }}</div>
            <div class="text-xs text-gray-500">Terdaftar Aktif</div>
        </div>

        <!-- Logbook Pending -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Logbook Pending</h3>
                <div class="w-8 h-8 bg-yellow-50 text-yellow-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-800 mb-2">{{ $logbookPending }}</div>
            <div class="text-xs text-gray-500">{{ $logbookPending == 0 ? 'Semua Beres' : 'Butuh Verifikasi' }}</div>
        </div>

        <!-- Pengajuan Masuk -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Menunggu Finalisasi</h3>
                <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-800 mb-2">{{ $pengajuanMasuk }}</div>
            <div class="text-xs text-blue-600 font-bold hover:underline cursor-pointer">Berkas & Izin</div>
        </div>
    </div>

    <!-- ================= BAGIAN BAWAH (GRAFIK & LIST) ================= -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Grafik Tren Kehadiran -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="font-bold text-gray-800">Tren Kehadiran Mahasiswa Magang</h3>
                    <p class="text-xs text-gray-500">Statistik perbandingan hadir tepat waktu, terlambat, dan izin.</p>
                </div>
                <div class="flex gap-4 text-xs font-medium">
                    <span class="flex items-center gap-1"><span class="w-2 h-2 bg-green-500 rounded-full"></span> Hadir</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 bg-orange-500 rounded-full"></span> Terlambat</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 bg-blue-500 rounded-full"></span> Izin</span>
                </div>
            </div>
            <!-- Tempat Grafik (Jika Admin pakai ApexCharts/Chart.js, copy script-nya ke sini) -->
            <div class="h-64 w-full">
                <canvas id="chartTren"></canvas>
            </div>
        </div>
        
        <!-- Grafik Distribusi Divisi -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="mb-6">
                <h3 class="font-bold text-gray-800">Distribusi Divisi / Tim</h3>
                <p class="text-xs text-gray-500">Proporsi aktivitas logbook berdasarkan unit penugasan.</p>
            </div>
            <div class="h-64 w-full flex justify-center">
                <canvas id="chartDivisi"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Presensi Masuk Terkini -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="font-bold text-gray-800">Presensi Masuk Terkini</h3>
                    <p class="text-xs text-gray-500">Aktivitas kehadiran mahasiswa magang terbaru.</p>
                </div>
                <a href="{{ route('superuser.dashboard') }}" class="text-xs text-orange-500 font-bold hover:underline">Semua Presensi &rarr;</a>
            </div>
            <div class="space-y-4">
                @forelse($recentPresensi as $presensi)
                <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-xl transition-colors border border-transparent hover:border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                            {{ substr($presensi->user->name ?? '?', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $presensi->user->name ?? 'Unknown' }}</p>
                            <p class="text-[11px] text-gray-500">{{ $presensi->user->mahasiswaProfile->universitas ?? '-' }} • {{ $presensi->tipe_presensi == 'kantor' ? 'Kantor' : 'Luar' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($presensi->jam_masuk)->format('H:i') }}</p>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $presensi->status === \App\Enums\StatusPresensi::Hadir ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                            {{ $presensi->status->label() }}
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">Belum ada data presensi hari ini.</p>
                @endforelse
            </div>
        </div>

        <!-- Perguruan Tinggi Asal -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="font-bold text-gray-800">Perguruan Tinggi Asal</h3>
                    <p class="text-xs text-gray-500">Sebaran universitas mahasiswa magang.</p>
                </div>
                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-lg">{{ $totalMhsProfile }} Mahasiswa</span>
            </div>
            
            <div class="space-y-5">
                @forelse($universitasData as $univ)
                    @php
                        $persen = $totalMhsProfile > 0 ? round(($univ->total / $totalMhsProfile) * 100) : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between text-xs font-bold text-gray-700 mb-2">
                            <span>{{ $univ->universitas }}</span>
                            <span>{{ $univ->total }} orang ({{ $persen }}%)</span>
                        </div>
                        <!-- Progress bar warna warni -->
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="h-2 rounded-full {{ $loop->first ? 'bg-orange-500' : ($loop->iteration == 2 ? 'bg-blue-500' : 'bg-indigo-500') }}" style="width: {{ $persen }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">Belum ada data universitas.</p>
                @endforelse
            </div>
            
            <a href="{{ route('superuser.dashboard') }}" class="mt-6 block w-full text-center px-4 py-2 border border-gray-200 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 transition-colors">
                Lihat Rekap Seluruh Mahasiswa &rarr;
            </a>
        </div>
    </div>
</div>

<script>
    // Script jam real-time
    setInterval(function() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('clock').textContent = `${hours}.${minutes}.${seconds}`;
    }, 1000);
</script>
<!-- Library Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. GRAFIK TREN KEHADIRAN (Garis) ---
        const ctxTren = document.getElementById('chartTren').getContext('2d');
        const gradientOrange = ctxTren.createLinearGradient(0, 0, 0, 400);
        gradientOrange.addColorStop(0, 'rgba(249, 115, 22, 0.5)'); // Oranye transparan
        gradientOrange.addColorStop(1, 'rgba(249, 115, 22, 0)');

        new Chart(ctxTren, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartDates) !!},
                datasets: [
                    {
                        label: 'Hadir',
                        data: {!! json_encode($chartHadir) !!},
                        borderColor: '#10b981', // Hijau
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                        pointRadius: 3,
                    },
                    {
                        label: 'Terlambat',
                        data: {!! json_encode($chartTerlambat) !!},
                        borderColor: '#f97316', // Oranye
                        backgroundColor: gradientOrange,
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                        pointRadius: 3,
                    },
                    {
                        label: 'Izin/Sakit',
                        data: {!! json_encode($chartIzin) !!},
                        borderColor: '#3b82f6', // Biru
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
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
                    legend: { display: false }, // Sembunyikan legenda bawaan karena sudah ada di HTML
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#1e293b',
                        titleFont: { size: 13 },
                        bodyFont: { size: 12 },
                        padding: 10,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#9ca3af' },
                        grid: { borderDash: [5, 5], color: '#f3f4f6' }
                    },
                    x: {
                        ticks: { color: '#9ca3af' },
                        grid: { display: false }
                    }
                }
            }
        });

        // --- 2. GRAFIK DISTRIBUSI TIM (Donat) ---
        const ctxDivisi = document.getElementById('chartDivisi').getContext('2d');
        
        // Warna-warna untuk donat
        const bgColors = ['#8b5cf6', '#f97316', '#10b981', '#3b82f6', '#ef4444', '#eab308'];

        new Chart(ctxDivisi, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($timLabels) !!},
                datasets: [{
                    data: {!! json_encode($timData) !!},
                    backgroundColor: bgColors,
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%', // Ketebalan donat
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection