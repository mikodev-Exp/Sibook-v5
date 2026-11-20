<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\User;
use App\Services\ExportRekapService;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Presensi::with(['user.mahasiswaProfile']);

        // Filter spesifik berdasarkan Mahasiswa terpilih
        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        } elseif ($search = $request->input('search')) {
            $query->whereHas('user', function ($uq) use ($search) {
                $uq->where('name', 'like', "%{$search}%");
            });
        }

        if ($tanggal = $request->input('tanggal')) {
            $query->whereDate('tanggal', $tanggal);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $presensis = $query->orderBy('tanggal', 'desc')->orderBy('jam_masuk', 'desc')->paginate(15)->withQueryString();

        // Ambil daftar seluruh mahasiswa magang terdaftar (role mahasiswa) untuk opsi dropdown rekap
        $daftarMahasiswa = User::role('mahasiswa')
            ->with('mahasiswaProfile')
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.presensi.index', compact('presensis', 'daftarMahasiswa'));
    }

    public function export(Request $request, ExportRekapService $exportService)
    {
        $query = Presensi::with(['user.mahasiswaProfile']);

        // Filter berdasarkan Mahasiswa terpilih untuk diekspor
        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        } elseif ($search = $request->input('search')) {
            $query->whereHas('user', function ($uq) use ($search) {
                $uq->where('name', 'like', "%{$search}%");
            });
        }

        if ($tanggal = $request->input('tanggal')) {
            $query->whereDate('tanggal', $tanggal);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $presensis = $query->orderBy('tanggal', 'desc')->orderBy('jam_masuk', 'desc')->get();

        return $exportService->exportPresensi($presensis);
    }
}
