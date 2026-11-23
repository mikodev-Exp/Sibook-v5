<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\MahasiswaProfile;
use App\Models\Perizinan;
use App\Models\PermohonanMagang;
use App\Models\Presensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. KPI Counts
        $totalPendaftar = User::role('pendaftar')->count();
        $totalMahasiswa = User::role('mahasiswa')->count();
        $permohonanPending = PermohonanMagang::where('status', 'submitted')->count();
        $perizinanPending = Perizinan::where('status', 'submitted')->count();
        $logbookPending = Logbook::where('status', 'submitted')->count();

        // 2. Presensi Hari Ini
        $presensiHariIniCount = Presensi::whereDate('tanggal', Carbon::today())->count();
        $presensiHadirHariIni = Presensi::whereDate('tanggal', Carbon::today())->where('status', 'hadir')->count();
        $presensiTerlambatHariIni = Presensi::whereDate('tanggal', Carbon::today())->where('status', 'terlambat')->count();
        $presensiIzinHariIni = Presensi::whereDate('tanggal', Carbon::today())->where('status', 'izin')->count();

        $tingkatKehadiranHariIni = $totalMahasiswa > 0 
            ? round(($presensiHariIniCount / $totalMahasiswa) * 100) 
            : 0;

        // Presensi Terbaru (Hari ini, atau data terbaru secara umum jika hari ini kosong)
        $presensiTerbaru = Presensi::with(['user.mahasiswaProfile'])
            ->whereDate('tanggal', Carbon::today())
            ->orderBy('jam_masuk', 'desc')
            ->take(6)
            ->get();

        if ($presensiTerbaru->isEmpty()) {
            $presensiTerbaru = Presensi::with(['user.mahasiswaProfile'])
                ->orderBy('tanggal', 'desc')
                ->orderBy('jam_masuk', 'desc')
                ->take(6)
                ->get();
        }

        // 3. Tren Kehadiran 7 Hari Terakhir untuk Chart.js
        $past7Days = collect(range(6, 0))->map(fn($days) => Carbon::today()->subDays($days)->format('Y-m-d'));
        $trendLabels = $past7Days->map(fn($d) => Carbon::parse($d)->translatedFormat('D, d M'))->toArray();
        $trendHadir = [];
        $trendTerlambat = [];
        $trendIzin = [];

        foreach ($past7Days as $d) {
            $trendHadir[] = Presensi::whereDate('tanggal', $d)->where('status', 'hadir')->count();
            $trendTerlambat[] = Presensi::whereDate('tanggal', $d)->where('status', 'terlambat')->count();
            $trendIzin[] = Presensi::whereDate('tanggal', $d)->where('status', 'izin')->count();
        }

        // Jika hari-hari terakhir kosong (contoh hari libur), gunakan tanggal terbaru yang memiliki data
        if (array_sum($trendHadir) == 0 && array_sum($trendTerlambat) == 0) {
            $distinctDates = Presensi::select('tanggal')->distinct()->orderBy('tanggal', 'desc')->take(7)->pluck('tanggal')->reverse()->values();
            if ($distinctDates->isNotEmpty()) {
                $trendLabels = $distinctDates->map(fn($d) => Carbon::parse($d)->translatedFormat('d M'))->toArray();
                $trendHadir = [];
                $trendTerlambat = [];
                $trendIzin = [];
                foreach ($distinctDates as $d) {
                    $trendHadir[] = Presensi::whereDate('tanggal', $d)->where('status', 'hadir')->count();
                    $trendTerlambat[] = Presensi::whereDate('tanggal', $d)->where('status', 'terlambat')->count();
                    $trendIzin[] = Presensi::whereDate('tanggal', $d)->where('status', 'izin')->count();
                }
            }
        }

        // 4. Distribusi Divisi / Tim Logbook untuk Doughnut Chart
        $timDistribution = Logbook::selectRaw('COALESCE(NULLIF(tim, ""), "Kegiatan Lainnya") as nama_tim, count(*) as total')
            ->groupBy('nama_tim')
            ->orderByDesc('total')
            ->pluck('total', 'nama_tim')
            ->toArray();

        // 5. Logbook Terbaru yang Membutuhkan Verifikasi
        $logbookTerbaru = Logbook::with(['user.mahasiswaProfile'])
            ->where('status', 'submitted')
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        if ($logbookTerbaru->isEmpty()) {
            $logbookTerbaru = Logbook::with(['user.mahasiswaProfile'])
                ->orderBy('tanggal', 'desc')
                ->take(5)
                ->get();
        }

        // 6. Top Universitas Mahasiswa Magang (hanya yang sudah berstatus role mahasiswa)
        $topUniversitas = MahasiswaProfile::whereHas('user', function ($q) {
                $q->role('mahasiswa');
            })
            ->whereNotNull('universitas')
            ->where('universitas', '!=', '')
            ->selectRaw('universitas, count(*) as total')
            ->groupBy('universitas')
            ->orderByDesc('total')
            ->take(4)
            ->get();

        return view('admin.dashboard', compact(
            'totalPendaftar',
            'totalMahasiswa',
            'permohonanPending',
            'perizinanPending',
            'logbookPending',
            'presensiHariIniCount',
            'presensiHadirHariIni',
            'presensiTerlambatHariIni',
            'presensiIzinHariIni',
            'tingkatKehadiranHariIni',
            'presensiTerbaru',
            'trendLabels',
            'trendHadir',
            'trendTerlambat',
            'trendIzin',
            'timDistribution',
            'logbookTerbaru',
            'topUniversitas'
        ));
    }
}