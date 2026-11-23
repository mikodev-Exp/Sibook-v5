<?php

namespace App\Http\Controllers\SuperUser;

use App\Http\Controllers\Controller;
use App\Enums\StatusPermohonan;
use App\Models\User;
use App\Models\PermohonanMagang;
use App\Models\Presensi;
use App\Models\Logbook;
use App\Models\Perizinan;
use App\Models\MahasiswaProfile;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data Statistik Atas
        $totalAdmin = User::role('admin')->count();
        $totalMahasiswa = User::role('mahasiswa')->count();
        $totalPendaftar = User::role('pendaftar')->count();
        
        $presensiHariIni = Presensi::where('tanggal', today())->count();
        $logbookPending = Logbook::where('status', 'draft')->count();
        $pengajuanMasuk = PermohonanMagang::where('status', StatusPermohonan::ReviewedByAdmin)->count();
        $perizinanPending = Perizinan::where('status', 'submitted')->count();

        // 2. Data Presensi Terkini
        $recentPresensi = Presensi::with('user.mahasiswaProfile')
                            ->orderBy('created_at', 'desc')
                            ->take(3)
                            ->get();

        // 3. Data Perguruan Tinggi
        $universitasData = MahasiswaProfile::select('universitas', DB::raw('count(*) as total'))
                            ->whereNotNull('universitas')
                            ->groupBy('universitas')
                            ->orderByDesc('total')
                            ->take(3)
                            ->get();
        $totalMhsProfile = $universitasData->sum('total');

        // 4. Data Grafik Tren Kehadiran (7 hari terakhir)
        $chartDates = [];
        $chartHadir = [];
        $chartTerlambat = [];
        $chartIzin = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartDates[] = $date->format('d M');
            
            $chartHadir[] = Presensi::whereDate('tanggal', $date)->where('status', 'hadir')->count();
            $chartTerlambat[] = Presensi::whereDate('tanggal', $date)->where('status', 'terlambat')->count();
            $chartIzin[] = Presensi::whereDate('tanggal', $date)->whereIn('status', ['izin', 'sakit'])->count();
        }

        // 5. Data Grafik Distribusi Tim (Dari Logbook)
        $distribusiTim = Logbook::select('tim', DB::raw('count(*) as total'))
                            ->whereNotNull('tim')
                            ->groupBy('tim')
                            ->get();
        $timLabels = $distribusiTim->pluck('tim');
        $timData = $distribusiTim->pluck('total');

        return view('superuser.dashboard', compact(
            'totalAdmin', 'totalMahasiswa', 'totalPendaftar', 'presensiHariIni', 
            'logbookPending', 'pengajuanMasuk', 'perizinanPending',
            'recentPresensi', 'universitasData', 'totalMhsProfile',
            'chartDates', 'chartHadir', 'chartTerlambat', 'chartIzin',
            'timLabels', 'timData'
        ));
    }
}