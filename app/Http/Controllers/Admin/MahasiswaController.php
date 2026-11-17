<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ExportRekapService;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::role('mahasiswa')->with(['mahasiswaProfile', 'magang']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('mahasiswaProfile', function ($pq) use ($search) {
                      $pq->where('universitas', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%")
                        ->orWhere('program_studi', 'like', "%{$search}%");
                  });
            });
        }

        $mahasiswas = $query->latest()->paginate(15)->withQueryString();

        return view('admin.mahasiswa.index', compact('mahasiswas'));
    }

    /**
     * Halaman laporan lengkap per mahasiswa (dibuka di tab baru sesuai diagram)
     */
    public function laporan(User $user, Request $request)
    {
        $user->load(['mahasiswaProfile', 'magang']);

        $bulan = $request->input('bulan');

        // Presensi
        $presensiQuery = $user->presensis()->orderBy('tanggal', 'desc');
        if ($bulan) {
            $presensiQuery->where('tanggal', 'like', "{$bulan}%");
        }
        $presensis = $presensiQuery->get();

        // Logbook
        $logbookQuery = $user->logbooks()->orderBy('tanggal', 'desc');
        if ($bulan) {
            $logbookQuery->where('tanggal', 'like', "{$bulan}%");
        }
        $logbooks = $logbookQuery->get();

        // Perizinan
        $perizinanQuery = $user->perizinans()->orderBy('tanggal_mulai', 'desc');
        if ($bulan) {
            $perizinanQuery->where('tanggal_mulai', 'like', "{$bulan}%");
        }
        $perizinans = $perizinanQuery->get();

        // Statistik Kehadiran (Hadir, Izin, Alpha, dsb)
        $totalHadir = $user->presensis()->where('status', 'hadir')->when($bulan, fn($q) => $q->where('tanggal', 'like', "{$bulan}%"))->count();
        $totalTerlambat = $user->presensis()->where('status', 'terlambat')->when($bulan, fn($q) => $q->where('tanggal', 'like', "{$bulan}%"))->count();
        $totalIzin = $user->presensis()->where('status', 'izin')->when($bulan, fn($q) => $q->where('tanggal', 'like', "{$bulan}%"))->count() 
                   + $user->perizinans()->where('status', 'approved')->when($bulan, fn($q) => $q->where('tanggal_mulai', 'like', "{$bulan}%"))->count();
        $totalSakit = $user->presensis()->where('status', 'sakit')->when($bulan, fn($q) => $q->where('tanggal', 'like', "{$bulan}%"))->count();
        $totalAlpha = $user->presensis()->where('status', 'alpha')->when($bulan, fn($q) => $q->where('tanggal', 'like', "{$bulan}%"))->count();
        $totalPresensi = $user->presensis()->when($bulan, fn($q) => $q->where('tanggal', 'like', "{$bulan}%"))->count();
        $rateKehadiran = $totalPresensi > 0 ? round((($totalHadir + $totalTerlambat) / $totalPresensi) * 100, 1) : 0;

        // Statistik Logbook
        $totalLogbook = $user->logbooks()->when($bulan, fn($q) => $q->where('tanggal', 'like', "{$bulan}%"))->count();
        $logbookApproved = $user->logbooks()->where('status', 'approved')->when($bulan, fn($q) => $q->where('tanggal', 'like', "{$bulan}%"))->count();
        $logbookSubmitted = $user->logbooks()->where('status', 'submitted')->when($bulan, fn($q) => $q->where('tanggal', 'like', "{$bulan}%"))->count();
        $logbookDraft = $user->logbooks()->where('status', 'draft')->when($bulan, fn($q) => $q->where('tanggal', 'like', "{$bulan}%"))->count();

        $stats = [
            'hadir' => $totalHadir,
            'terlambat' => $totalTerlambat,
            'izin' => $totalIzin,
            'sakit' => $totalSakit,
            'alpha' => $totalAlpha,
            'total_presensi' => $totalPresensi,
            'persentase_kehadiran' => $rateKehadiran,
            'total_logbook' => $totalLogbook,
            'logbook_approved' => $logbookApproved,
            'logbook_submitted' => $logbookSubmitted,
            'logbook_draft' => $logbookDraft,
        ];

        return view('admin.mahasiswa.laporan', compact(
            'user', 'presensis', 'logbooks', 'perizinans', 'stats', 'bulan'
        ));
    }

    /**
     * Export file Excel rekap laporan individu mahasiswa
     */
    public function exportLaporan(User $user, Request $request, ExportRekapService $exportService)
    {
        return $exportService->exportLaporanMahasiswa($user, $request->input('bulan'));
    }

    /**
     * Export rekap seluruh mahasiswa magang ke file Excel
     */
    public function exportAll(Request $request, ExportRekapService $exportService)
    {
        $query = User::role('mahasiswa')->with(['mahasiswaProfile', 'magang']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('mahasiswaProfile', function ($pq) use ($search) {
                      $pq->where('universitas', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%")
                        ->orWhere('program_studi', 'like', "%{$search}%");
                  });
            });
        }

        $mahasiswas = $query->latest()->get();

        return $exportService->exportSemuaMahasiswa($mahasiswas);
    }
}
