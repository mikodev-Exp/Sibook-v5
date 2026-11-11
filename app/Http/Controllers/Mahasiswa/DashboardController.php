<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
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
        
        // Rata-rata jam masuk (dalam format H:i)
        $rataRataMasuk = $user->presensis()
            ->whereNotNull('jam_masuk')
            ->avg(DB::raw('TIME_TO_SEC(jam_masuk)'));
            
        $rataRataFormat = $rataRataMasuk 
            ? gmdate('H:i', $rataRataMasuk) 
            : '-';

        return view('mahasiswa.dashboard', compact(
            'user', 'magang', 'presensiHariIni', 'logbookHariIni', 
            'totalPresensi', 'totalLogbook', 'totalIzin', 'rataRataFormat'
        ));
    }
}