<?php

namespace App\Http\Controllers\SuperUser;

use App\Http\Controllers\Controller;
use App\Models\PermohonanMagang;
use App\Models\Magang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermohonanController extends Controller
{
    public function index()
    {
        // Ambil semua data permohonan, urutkan dari yang terbaru
        $permohonan = PermohonanMagang::with(['user.mahasiswaProfile', 'dokumens'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('superuser.permohonan.index', compact('permohonan'));
    }

    public function show(PermohonanMagang $permohonan)
    {
        // Load relasi profil dan dokumen
        $permohonan->load(['user.mahasiswaProfile', 'dokumens']);
        return view('superuser.permohonan.show', compact('permohonan'));
    }

    public function approve(Request $request, PermohonanMagang $permohonan)
    {
        $request->validate(['catatan_superuser' => 'nullable|string']);

        try {
            DB::beginTransaction();

            // 1. Update status permohonan menjadi approved
            $permohonan->update([
                'status' => 'approved',
                'catatan_superuser' => $request->catatan_superuser,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // 2. Buat data Magang (Otomatis aktif)
            Magang::updateOrCreate(
                ['user_id' => $permohonan->user_id],
                [
                    'tanggal_mulai' => $permohonan->tanggal_mulai_rencana,
                    'tanggal_selesai' => $permohonan->tanggal_selesai_rencana,
                    'status' => 'active',
                ]
            );

            // 3. Ubah Role Pendaftar menjadi Mahasiswa
            $permohonan->user->syncRoles(['mahasiswa']);

            DB::commit();

            return redirect()->route('superuser.permohonan.index')->with('success', 'Pendaftar berhasil disetujui dan diubah menjadi Mahasiswa Magang!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, PermohonanMagang $permohonan)
    {
        $request->validate(['catatan_superuser' => 'required|string']);

        $permohonan->update([
            'status' => 'rejected',
            'catatan_superuser' => $request->catatan_superuser,
        ]);

        return redirect()->route('superuser.permohonan.index')->with('success', 'Pendaftaran berhasil ditolak.');
    }
}