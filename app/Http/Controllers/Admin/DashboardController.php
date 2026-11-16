<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PermohonanMagang;
use App\Models\Perizinan;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPendaftar = User::role('pendaftar')->count();
        $totalMahasiswa = User::role('mahasiswa')->count();
        $permohonanPending = PermohonanMagang::where('status', 'submitted')->count();
        $perizinanPending = Perizinan::where('status', 'submitted')->count();

        return view('admin.dashboard', compact(
            'totalPendaftar', 'totalMahasiswa', 'permohonanPending', 'perizinanPending'
        ));
    }
}