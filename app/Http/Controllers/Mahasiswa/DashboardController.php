<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $magang = $user->magang;
        
        // Data Hari Ini
        $presensiHariIni = $user->presensis()->where('tanggal', today())->first();
        $logbookHariIni = $user->logbooks()->where('tanggal', today())->first();

        // Statistik
        $totalPresensi = $user->presensis()->count();
        $totalLogbook = $user->logbooks()->count();
        $totalIzin = $user->perizinans()->where('status', 'approved')->count();
        $totalMenitTerlambat = $user->totalMenitTerlambat();
        
        // Rata-rata jam masuk dihitung secara aman di PHP (kompatibel MySQL & SQLite)
        $jamMasuks = $user->presensis()->whereNotNull('jam_masuk')->pluck('jam_masuk');
        if ($jamMasuks->isNotEmpty()) {
            $totalDetik = 0;
            foreach ($jamMasuks as $jam) {
                $parts = explode(':', $jam);
                $totalDetik += ((int)$parts[0] * 3600) + ((int)($parts[1] ?? 0) * 60) + (int)($parts[2] ?? 0);
            }
            $rataRataDetik = round($totalDetik / $jamMasuks->count());
            $rataRataFormat = gmdate('H:i', $rataRataDetik);
        } else {
            $rataRataFormat = '-';
        }

        // 5 Logbook Terbaru untuk widget ringkasan
        $logbookTerbaru = $user->logbooks()->orderBy('tanggal', 'desc')->take(5)->get();

        return view('mahasiswa.dashboard', compact(
            'user', 'magang', 'presensiHariIni', 'logbookHariIni', 
            'totalPresensi', 'totalLogbook', 'totalIzin', 'rataRataFormat', 'logbookTerbaru',
            'totalMenitTerlambat'
        ));
    }
}
