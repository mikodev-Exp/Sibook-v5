<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogbookController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Statistik tetap menghitung semua baris (induk + anak)
        $totalLogbook = $user->logbooks()->count();
        $selesai = $user->logbooks()->where('status', '!=', 'draft')->count();
        $belumSelesai = $user->logbooks()->where('status', 'draft')->count();

        // Ambil HANYA logbook induk (parent_id null), dan load data anaknya (children)
        $riwayat = $user->logbooks()
            ->whereNull('parent_id') 
            ->with('children')
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('mahasiswa.logbook.index', compact('totalLogbook', 'selesai', 'belumSelesai', 'riwayat'));
    }

    public function create(Request $request)
    {
        $parent = null;
        if ($request->has('parent_id')) {
            // Mengambil data logbook yang mau dilanjutkan
            $parent = Logbook::where('user_id', Auth::id())->findOrFail($request->parent_id);
            $mode = 'lanjutkan';
        } else {
            // Cek apakah hari ini user sudah buat logbook baru
            $sudahBuatHariIni = Auth::user()->logbooks()->where('tanggal', today())->exists();
            if ($sudahBuatHariIni) {
                return redirect()->route('mahasiswa.logbook.index')
                    ->with('error', 'Anda sudah membuat logbook untuk hari ini. Silakan klik "Edit" pada tabel riwayat.');
            }
            $mode = 'create';
        }

        return view('mahasiswa.logbook.form', compact('parent', 'mode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tim' => 'required|string',
            'jenis_gsbpm' => 'required|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'nullable|required_if:is_selesai,1',
            'kegiatan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'link_bukti' => 'required|url',
            'is_selesai' => 'required|boolean'
        ]);

        $sudahBuatHariIni = Auth::user()->logbooks()->where('tanggal', $request->tanggal)->exists();
        if ($sudahBuatHariIni) {
            return back()->with('error', 'Logbook untuk tanggal tersebut sudah ada. Silakan edit yang lama.')->withInput();
        }

        $isSelesai = (bool) $request->is_selesai;
        $status = $isSelesai ? 'submitted' : 'draft';

        $logbook = Auth::user()->logbooks()->create([
            'tanggal' => $request->tanggal,
            'tim' => $request->tim,
            'jenis_gsbpm' => $request->jenis_gsbpm,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $isSelesai ? $request->jam_selesai : null,
            'kegiatan' => $request->kegiatan,
            'deskripsi' => $request->deskripsi,
            'link_bukti' => $request->link_bukti,
            'status' => $status,
            'parent_id' => $request->parent_id, // Menyimpan relasi ke logbook sebelumnya
        ]);

        // Jika diselesaikan, ubah juga status semua induknya menjadi selesai
        if ($isSelesai && $logbook->parent_id) {
            $this->selesaikanSemuaInduk($logbook);
        }

        $pesan = $isSelesai ? 'Logbook berhasil diselesaikan!' : 'Logbook disimpan sebagai draf.';
        return redirect()->route('mahasiswa.logbook.index')->with('success', $pesan);
    }

    public function edit(Request $request, Logbook $logbook)
    {
        if ($logbook->user_id !== Auth::id()) abort(403);
        $mode = 'edit';
        return view('mahasiswa.logbook.form', compact('logbook', 'mode'));
    }

    public function update(Request $request, Logbook $logbook)
    {
        if ($logbook->user_id !== Auth::id()) abort(403);

        $request->validate([
            'tim' => 'required|string',
            'jenis_gsbpm' => 'required|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'nullable|required_if:is_selesai,1',
            'kegiatan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'link_bukti' => 'required|url',
            'is_selesai' => 'required|boolean'
        ]);

        $bentrok = Auth::user()->logbooks()
            ->where('tanggal', $request->tanggal)
            ->where('id', '!=', $logbook->id)
            ->exists();

        if ($bentrok) {
            return back()->with('error', 'Anda sudah memiliki logbook untuk tanggal tersebut. Silakan pilih tanggal lain.')->withInput();
        }

        $isSelesai = (bool) $request->is_selesai;
        $status = $isSelesai ? 'submitted' : 'draft';

        $logbook->update([
            'tanggal' => $request->tanggal,
            'tim' => $request->tim,
            'jenis_gsbpm' => $request->jenis_gsbpm,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $isSelesai ? $request->jam_selesai : null,
            'kegiatan' => $request->kegiatan,
            'deskripsi' => $request->deskripsi,
            'link_bukti' => $request->link_bukti,
            'status' => $status,
        ]);

        if ($isSelesai) {
            $this->selesaikanSemuaInduk($logbook);
        }

        return redirect()->route('mahasiswa.logbook.index')->with('success', 'Logbook berhasil diperbarui!');
    }

    // Fungsi bantuan untuk mencentang (selesai) semua logbook induknya
    private function selesaikanSemuaInduk(Logbook $logbook)
    {
        $parentId = $logbook->parent_id;
        while ($parentId) {
            $parent = Logbook::find($parentId);
            if ($parent) {
                $parent->update(['status' => 'submitted']);
                $parentId = $parent->parent_id;
            } else {
                break;
            }
        }
    }

    public function destroy(Logbook $logbook)
    {
        if ($logbook->user_id !== Auth::id()) abort(403);
        $logbook->delete();
        return redirect()->route('mahasiswa.logbook.index')->with('success', 'Logbook berhasil dihapus!');
    }

    public function lanjutkan()
    {
        $drafts = Auth::user()->logbooks()->where('status', 'draft')->orderBy('tanggal', 'desc')->get();
        return view('mahasiswa.logbook.lanjutkan', compact('drafts'));
    }
}