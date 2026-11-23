<?php

namespace App\Http\Controllers\Pendaftar;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MahasiswaProfile;
use App\Models\PermohonanMagang;
use App\Models\PermohonanDokumen;
use App\Services\WorkdayService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('pendaftar.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'no_hp' => 'required|string|max:20',
            'link_pasfoto' => 'required|url',
            'username_ig' => 'required|string|max:50',
            'universitas' => 'required|string|max:255',
            'program_studi' => 'required|string|max:255',
            'nama_dosen_pembimbing' => 'required|string|max:255',
            'no_hp_dosen_pembimbing' => 'required|string|max:25',
            'status_konversi' => 'required|in:0,1',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'device' => 'required|string|max:255',
            'punya_bpjs' => 'required|in:0,1',
            'dokumen' => 'required|file|mimes:pdf,zip,rar|max:5120', // Maks 5MB
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Ambil status konversi (untuk disimpan ke profil mahasiswa)
        $statusKonversi = $request->boolean('status_konversi');

        // Validasi Durasi Minimal: minimal 65 hari kerja efektif (± 3 bulan)
        // berlaku untuk semua status konversi (konversi maupun non-konversi)
        $tglMulai   = Carbon::parse($request->tanggal_mulai);
        $tglSelesai = Carbon::parse($request->tanggal_selesai);

        $hariKerja  = WorkdayService::hitungHariKerja($tglMulai, $tglSelesai);
        $minSelesai = WorkdayService::tanggalSelesaiMinimal($tglMulai);

        if ($hariKerja < WorkdayService::MIN_WORKDAYS) {
            return back()->withErrors([
                'tanggal_selesai' => "Periode magang minimal " . WorkdayService::MIN_WORKDAYS . " hari kerja efektif (tidak termasuk Sabtu, Minggu, dan hari libur nasional). "
                    . "Dengan tanggal mulai " . $tglMulai->translatedFormat('d F Y') . ", tanggal selesai paling awal adalah "
                    . $minSelesai->translatedFormat('d F Y') . ". "
                    . "Saat ini baru " . $hariKerja . " hari kerja."
            ])->withInput();
        }

        try {
            DB::beginTransaction();

            // 1. Buat User
            $user = User::create([
                'name' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);
            $user->assignRole('pendaftar');

            // 2. Buat Profil Mahasiswa
            MahasiswaProfile::create([
                'user_id' => $user->id,
                'universitas' => $request->universitas,
                'program_studi' => $request->program_studi,
                'nama_dosen_pembimbing' => $request->nama_dosen_pembimbing,
                'no_hp_dosen_pembimbing' => $request->no_hp_dosen_pembimbing,
                'no_hp' => $request->no_hp,
                'jenis_kelamin' => $request->jenis_kelamin,
                'link_pasfoto' => $request->link_pasfoto,
                'username_ig' => $request->username_ig,
                'status_konversi' => $statusKonversi,
                'device' => $request->device,
                'punya_bpjs' => $request->boolean('punya_bpjs'),
            ]);

            // 3. Buat Permohonan Magang
            $permohonan = PermohonanMagang::create([
                'user_id' => $user->id,
                'tanggal_mulai_rencana' => $request->tanggal_mulai,
                'tanggal_selesai_rencana' => $request->tanggal_selesai,
                'status' => 'submitted',
            ]);

            // 4. Upload Dokumen
            if ($request->hasFile('dokumen')) {
                $file = $request->file('dokumen');
                $path = $file->store('dokumen_pendaftaran', 'public');
                
                PermohonanDokumen::create([
                    'permohonan_id' => $permohonan->id,
                    'jenis' => 'berkas_gabungan',
                    'nama_asli' => $file->getClientOriginalName(),
                    'path' => $path,
                ]);
            }

            DB::commit();

            // Login otomatis setelah daftar
            Auth::login($user);

            return redirect()->route('pendaftar.dashboard')->with('success', 'Pendaftaran berhasil! Silakan cek status permohonan Anda.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}