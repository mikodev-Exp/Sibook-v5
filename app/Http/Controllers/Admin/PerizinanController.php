<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusPerizinan;
use App\Enums\StatusPresensi;
use App\Http\Controllers\Controller;
use App\Models\Perizinan;
use App\Models\Presensi;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class PerizinanController extends Controller
{
    public function index(Request $request)
    {
        $query = Perizinan::with(['user.mahasiswaProfile', 'reviewer']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%");
                })->orWhere('alasan', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $perizinans = $query->latest()->paginate(15)->withQueryString();

        $totalCount = Perizinan::count();
        $submittedCount = Perizinan::where('status', StatusPerizinan::Submitted)->count();
        $approvedCount = Perizinan::where('status', StatusPerizinan::Approved)->count();
        $rejectedCount = Perizinan::where('status', StatusPerizinan::Rejected)->count();

        return view('admin.perizinan.index', compact(
            'perizinans', 'totalCount', 'submittedCount', 'approvedCount', 'rejectedCount'
        ));
    }

    public function updateStatus(Request $request, Perizinan $perizinan)
    {
        $validated = $request->validate([
            'status'  => 'required|in:approved,rejected',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $statusEnum = $validated['status'] === 'approved' 
            ? StatusPerizinan::Approved 
            : StatusPerizinan::Rejected;

        $perizinan->update([
            'status'      => $statusEnum,
            'catatan'     => $validated['catatan'] ?? null,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        // Jika disetujui, otomatis rekap ke tabel presensi sebagai 'izin' untuk setiap hari kerja dalam periode
        if ($statusEnum === StatusPerizinan::Approved) {
            $period = CarbonPeriod::create($perizinan->tanggal_mulai, $perizinan->tanggal_selesai);
            foreach ($period as $date) {
                // Abaikan hari Sabtu & Minggu (5 hari kerja)
                if ($date->isWeekend()) {
                    continue;
                }

                Presensi::updateOrCreate(
                    [
                        'user_id' => $perizinan->user_id,
                        'tanggal' => $date->format('Y-m-d'),
                    ],
                    [
                        'status'            => StatusPresensi::Izin,
                        'tipe_presensi'     => 'izin',
                        'keterangan'        => 'Izin: ' . $perizinan->alasan,
                        'status_verifikasi' => 'approved',
                    ]
                );
            }
        }

        $label = $statusEnum === StatusPerizinan::Approved ? 'disetujui' : 'ditolak';
        return back()->with('success', "Pengajuan izin berhasil {$label}.");
    }
}
