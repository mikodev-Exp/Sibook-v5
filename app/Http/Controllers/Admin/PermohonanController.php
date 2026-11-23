<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusMagang;
use App\Enums\StatusPermohonan;
use App\Http\Controllers\Controller;
use App\Models\Magang;
use App\Models\PermohonanDokumen;
use App\Models\PermohonanMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PermohonanController extends Controller
{
    public function index(Request $request)
    {
        $query = PermohonanMagang::with(['user.mahasiswaProfile', 'dokumens', 'reviewer']);

        if ($search = $request->input('search')) {
            $query->whereHas('user', function ($uq) use ($search) {
                $uq->where('name', 'like', "%{$search}%")
                   ->orWhere('email', 'like', "%{$search}%")
                   ->orWhereHas('mahasiswaProfile', function ($pq) use ($search) {
                       $pq->where('universitas', 'like', "%{$search}%")
                          ->orWhere('program_studi', 'like', "%{$search}%")
                          ->orWhere('nim', 'like', "%{$search}%");
                   });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $permohonans = $query->latest()->paginate(15)->withQueryString();

        // Statistik ringkas untuk toolbar
        $stats = [
            'total'    => PermohonanMagang::count(),
            'pending'  => PermohonanMagang::where('status', StatusPermohonan::Submitted)->count(),
            'reviewed' => PermohonanMagang::where('status', StatusPermohonan::ReviewedByAdmin)->count(),
            'approved' => PermohonanMagang::where('status', StatusPermohonan::Approved)->count(),
            'rejected' => PermohonanMagang::where('status', StatusPermohonan::Rejected)->count(),
        ];

        return view('admin.permohonan.index', compact('permohonans', 'stats'));
    }

    public function show(PermohonanMagang $permohonan)
    {
        $permohonan->load(['user.mahasiswaProfile', 'dokumens', 'reviewer']);
        return view('admin.permohonan.show', compact('permohonan'));
    }

    public function updateStatus(Request $request, PermohonanMagang $permohonan)
    {
        $request->validate([
            'status'        => ['required', 'in:reviewed_by_admin,approved,rejected'],
            'catatan_admin' => ['nullable', 'string', 'max:1000'],
        ]);

        $newStatus = StatusPermohonan::from($request->status);

        $permohonan->update([
            'status'        => $newStatus,
            'catatan_admin' => $request->catatan_admin,
            'reviewed_by'   => Auth::id(),
            'reviewed_at'   => now(),
        ]);

        // Update role dan status magang mahasiswa jika disetujui / ditolak
        $user = $permohonan->user;
        if ($user) {
            if ($newStatus === StatusPermohonan::Approved) {
                // Beri role mahasiswa
                $user->syncRoles(['mahasiswa']);

                // Tentukan periode magang dari tanggal rencana permohonan
                $mulai = $permohonan->tanggal_mulai_rencana ? \Carbon\Carbon::parse($permohonan->tanggal_mulai_rencana) : today();
                $selesai = $permohonan->tanggal_selesai_rencana ? \Carbon\Carbon::parse($permohonan->tanggal_selesai_rencana) : $mulai->copy()->addMonths(3);

                // Jika sudah melewati tanggal selesai maka statusnya completed, selain itu active
                $statusMagang = today()->gt($selesai) ? StatusMagang::Completed : StatusMagang::Active;

                Magang::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'tanggal_mulai'   => $mulai,
                        'tanggal_selesai' => $selesai,
                        'status'          => $statusMagang,
                    ]
                );
            } elseif ($newStatus === StatusPermohonan::Rejected) {
                // Jika ditolak dan user bukan admin/superuser, kembalikan ke pendaftar
                if (!$user->hasRole('admin') && !$user->hasRole('super_user')) {
                    $user->syncRoles(['pendaftar']);
                    if ($user->magang) {
                        $user->magang->update(['status' => StatusMagang::Terminated]);
                    }
                }
            }

            // Sinkronkan data kuota magang aktif dan bersihkan cache landing page
            app(\App\Services\PersyaratanMagangService::class)->syncActiveFromDatabase();
            app(\App\Services\PersyaratanMagangService::class)->clearCache();
        }

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'ok' => true,
                'message' => $newStatus === StatusPermohonan::Approved
                    ? 'Permohonan disetujui. Akun telah aktif sebagai Mahasiswa Magang.'
                    : 'Status permohonan berhasil diperbarui.'
            ]);
        }

        return back()->with('success', $newStatus === StatusPermohonan::Approved
            ? 'Permohonan disetujui. Akun telah aktif sebagai Mahasiswa Magang.'
            : 'Status permohonan berhasil diperbarui.');
    }

    public function dokumen(Request $request, PermohonanMagang $permohonan, PermohonanDokumen $dokumen)
    {
        abort_unless($dokumen->permohonan_id === $permohonan->id, 404);

        $disk = Storage::disk('public');
        abort_unless($disk->exists($dokumen->path), 404, 'Berkas dokumen tidak ditemukan di server.');

        $fileName = $dokumen->nama_asli ?? basename($dokumen->path);

        if ($request->boolean('download')) {
            return $disk->download($dokumen->path, $fileName);
        }

        return $disk->response($dokumen->path, $fileName, [
            'Content-Disposition' => 'inline; filename="' . addslashes($fileName) . '"',
        ]);
    }
}
