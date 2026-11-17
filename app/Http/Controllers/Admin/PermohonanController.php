<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusPermohonan;
use App\Http\Controllers\Controller;
use App\Models\PermohonanMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // ⚠ Tidak ada perubahan role atau pembuatan record magang di sini.
        // Ini hanya verifikasi berkas saja. Aktivasi akun mahasiswa dilakukan oleh super_user.

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['ok' => true, 'message' => 'Status berhasil diperbarui.']);
        }

        return back()->with('success', 'Status permohonan berhasil diperbarui.');
    }
}
