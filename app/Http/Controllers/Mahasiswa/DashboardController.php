<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $magang = $user->magang;
        $presensiHariIni = $user->presensis()->where('tanggal', today())->first();

        return view('mahasiswa.dashboard', compact('user', 'magang', 'presensiHariIni'));
    }
}