<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Enums\StatusPerizinan;
use App\Http\Controllers\Controller;
use App\Models\Perizinan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PerizinanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $perizinans = $user->perizinans()
            ->latest()
            ->paginate(10);

        $totalIzin = $user->perizinans()->count();
        $disetujui = $user->perizinans()->where('status', StatusPerizinan::Approved)->count();
        $menunggu  = $user->perizinans()->where('status', StatusPerizinan::Submitted)->count();
        $ditolak   = $user->perizinans()->where('status', StatusPerizinan::Rejected)->count();

        return view('mahasiswa.perizinan.index', compact(
            'perizinans', 'totalIzin', 'disetujui', 'menunggu', 'ditolak'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan'          => 'required|string|max:1000',
            'bukti_file'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'url_bukti'       => 'nullable|url|max:500',
        ]);

        $urlBukti = $request->input('url_bukti');

        if ($request->hasFile('bukti_file')) {
            $path = $request->file('bukti_file')->store('perizinan', 'public');
            $urlBukti = Storage::url($path);
        }

        if (!$urlBukti) {
            return back()->withErrors(['bukti_file' => 'Wajib melampirkan berkas bukti atau tautan dokumen bukti izin.'])->withInput();
        }

        Auth::user()->perizinans()->create([
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan'          => $request->alasan,
            'url_bukti'       => $urlBukti,
            'status'          => StatusPerizinan::Submitted,
        ]);

        return redirect()->route('mahasiswa.perizinan.index')
            ->with('success', 'Pengajuan izin berhasil dikirim dan menunggu persetujuan admin.');
    }
}
