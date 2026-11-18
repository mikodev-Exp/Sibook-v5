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
        // Hanya tampilkan yang sudah di-review admin, atau semua data
        $permohonan = PermohonanMagang::with('user.mahasiswaProfile')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('superuser.permohonan.index', compact('permohonan'));
    }

    public function approve(Request $request, PermohonanMagang $permohonan)
    {
        $request->validate(['catatan_superuser' => 'nullable|string']);

        try {
            DB::beginTransaction();

            // 1. Update status permohonan
            $permohonan->update([
                'status' => 'approved',
                'catatan_superuser' => $request->catatan_superuser,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // 2. Buat data Magang (Aktif)
            Magang::updateOrCreate(
                ['user_id' => $permohonan->user_id],
                [
                    'tanggal_mulai' => $permohonan->tanggal_mulai_rencana,
                    'tanggal_selesai' => $permohonan->tanggal_selesai_rencana,
                    'status' => 'active',
                ]
            );

            // 3. Ubah Role User menjadi Mahasiswa
            $permohonan->user->syncRoles(['mahasiswa']);

            DB::commit();

            return back()->with('success', 'Pendaftar berhasil disetujui dan diubah menjadi Mahasiswa Magang!');
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

        return back()->with('success', 'Pendaftaran berhasil ditolak.');
    }
}