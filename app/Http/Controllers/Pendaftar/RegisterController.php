<?php

namespace App\Http\Controllers\Pendaftar;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MahasiswaProfile;
use App\Models\PermohonanMagang;
use App\Models\PermohonanDokumen;
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
            'status_konversi' => 'required|boolean',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'device' => 'required|string',
            'punya_bpjs' => 'required|boolean',
            'dokumen' => 'required|file|mimes:pdf,zip,rar|max:5120', // Maks 5MB
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        try {
            DB::beginTransaction();

            // 1. Buat User
            $user = User::create([
                'name' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(), // <-- TAMBAHKAN BARIS INI
            ]);
            $user->assignRole('pendaftar');

            // 2. Buat Profil Mahasiswa
            MahasiswaProfile::create([
                'user_id' => $user->id,
                'universitas' => $request->universitas,
                'program_studi' => $request->program_studi,
                'no_hp' => $request->no_hp,
                'jenis_kelamin' => $request->jenis_kelamin,
                'link_pasfoto' => $request->link_pasfoto,
                'username_ig' => $request->username_ig,
                'status_konversi' => $request->status_konversi,
                'device' => $request->device,
                'punya_bpjs' => $request->punya_bpjs,
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