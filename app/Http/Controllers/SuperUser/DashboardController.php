<?php

namespace App\Http\Controllers\SuperUser;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PermohonanMagang;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung user dengan role 'admin'
        $totalAdmin = User::role('admin')->count();
        
        // Menghitung user dengan role 'mahasiswa'
        $totalMahasiswa = User::role('mahasiswa')->count();
        
        // Menghitung permohonan yang sudah di-review admin tapi belum di-approve superuser
        $menungguApproval = PermohonanMagang::where('status', 'reviewed_by_admin')->count();

        return view('superuser.dashboard', compact('totalAdmin', 'totalMahasiswa', 'menungguApproval'));
    }
}