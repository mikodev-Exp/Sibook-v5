<?php

namespace App\Http\Controllers\SuperUser;

use App\Enums\StatusMagang;
use App\Enums\StatusPermohonan;
use App\Http\Controllers\Controller;
use App\Models\PermohonanMagang;
use App\Models\PermohonanDokumen;
use App\Models\Magang;
use App\Services\PersyaratanMagangService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PermohonanController extends Controller
{
    public function index(Request $request)
    {
        // Hitung statistik antrean untuk Super Admin
        // Catatan: Pendaftar yang masih berstatus 'submitted' (Menunggu Admin) berada di ranah Admin, belum masuk ke Super Admin.
        $countMenunggu = PermohonanMagang::where('status', StatusPermohonan::ReviewedByAdmin)->count();
        $countApproved = PermohonanMagang::where('status', StatusPermohonan::Approved)->count();
        $countRejected = PermohonanMagang::where('status', StatusPermohonan::Rejected)->count();
        $countTotal    = $countMenunggu + $countApproved + $countRejected;

        $tab = $request->input('tab', 'menunggu');

        $query = PermohonanMagang::with(['user.mahasiswaProfile', 'dokumens', 'reviewer', 'approver'])
            ->latest();

        // Filter berdasarkan hierarki: Super Admin hanya memproses yang sudah ditinjau Admin atau riwayat keputusannya
        if ($tab === 'approved') {
            $query->where('status', StatusPermohonan::Approved);
        } elseif ($tab === 'rejected') {
            $query->where('status', StatusPermohonan::Rejected);
        } elseif ($tab === 'all') {
            $query->whereIn('status', [
                StatusPermohonan::ReviewedByAdmin,
                StatusPermohonan::Approved,
                StatusPermohonan::Rejected,
            ]);
        } else {
            // Default: 'menunggu' (Hanya yang berstatus ReviewedByAdmin / Siap Finalisasi)
            $tab = 'menunggu';
            $query->where('status', StatusPermohonan::ReviewedByAdmin);
        }

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

        $permohonan = $query->paginate(10)->withQueryString();

        return view('superuser.permohonan.index', compact(
            'permohonan',
            'tab',
            'countMenunggu',
            'countApproved',
            'countRejected',
            'countTotal'
        ));
    }

    public function show(PermohonanMagang $permohonan)
    {
        // Load relasi profil, dokumen, admin peninjau, dan superuser approver
        $permohonan->load(['user.mahasiswaProfile', 'dokumens', 'reviewer', 'approver']);
        return view('superuser.permohonan.show', compact('permohonan'));
    }

    public function approve(Request $request, PermohonanMagang $permohonan)
    {
        // Enforce hierarki: Hanya permohonan yang telah dinyatakan layak oleh Admin yang bisa disetujui Super Admin
        if ($permohonan->status !== StatusPermohonan::ReviewedByAdmin) {
            return back()->with('error', 'Permohonan belum dapat disetujui karena belum ditinjau dan dinyatakan layak oleh Admin.');
        }

        $request->validate(['catatan_superuser' => 'nullable|string']);

        try {
            DB::beginTransaction();

            // 1. Update status permohonan menjadi approved
            $permohonan->update([
                'status'            => StatusPermohonan::Approved,
                'catatan_superuser' => $request->catatan_superuser,
                'approved_by'       => auth()->id(),
                'approved_at'       => now(),
            ]);

            // 2. Buat / update data Magang (Otomatis aktif)
            Magang::updateOrCreate(
                ['user_id' => $permohonan->user_id],
                [
                    'tanggal_mulai'   => $permohonan->tanggal_mulai_rencana ?? today(),
                    'tanggal_selesai' => $permohonan->tanggal_selesai_rencana ?? today()->addMonths(3),
                    'status'          => StatusMagang::Active,
                ]
            );

            // 3. Ubah Role Pendaftar menjadi Mahasiswa
            $permohonan->user->syncRoles(['mahasiswa']);

            DB::commit();

            // 4. Sinkronisasi kuota dan bersihkan cache kuota
            app(PersyaratanMagangService::class)->syncActiveFromDatabase();
            app(PersyaratanMagangService::class)->clearCache();

            return redirect()->route('superuser.permohonan.index')->with('success', 'Pendaftar berhasil disetujui dan resmi menjadi Mahasiswa Magang!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, PermohonanMagang $permohonan)
    {
        $request->validate(['catatan_superuser' => 'required|string']);

        try {
            DB::beginTransaction();

            $permohonan->update([
                'status'            => StatusPermohonan::Rejected,
                'catatan_superuser' => $request->catatan_superuser,
                'approved_by'       => auth()->id(),
                'approved_at'       => now(),
            ]);

            // Jika sebelumnya terdaftar magang, terminate
            if ($permohonan->user && $permohonan->user->magang) {
                $permohonan->user->magang->update(['status' => StatusMagang::Terminated]);
            }

            // Kembalikan ke pendaftar jika bukan admin/superuser
            if ($permohonan->user && !$permohonan->user->hasRole('admin') && !$permohonan->user->hasRole('super_user')) {
                $permohonan->user->syncRoles(['pendaftar']);
            }

            DB::commit();

            app(PersyaratanMagangService::class)->syncActiveFromDatabase();
            app(PersyaratanMagangService::class)->clearCache();

            return redirect()->route('superuser.permohonan.index')->with('success', 'Pendaftaran berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak: ' . $e->getMessage());
        }
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