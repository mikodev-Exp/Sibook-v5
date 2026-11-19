@extends('layouts.role', ['role' => 'superuser'])

@section('title', 'Detail Pendaftar')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Tombol Kembali -->
    <div>
        <a href="{{ route('superuser.permohonan.index') }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-orange-500 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
    </div>

    <!-- Header Profil -->
    <div class="bg-gradient-to-r from-orange-50 to-white rounded-2xl shadow-sm border border-orange-100 p-6 flex flex-col md:flex-row justify-between items-start md:items-center">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-orange-500 text-white rounded-full flex items-center justify-center text-2xl font-bold shadow-md">
                {{ substr($permohonan->user->name, 0, 1) }}
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $permohonan->user->name }}</h2>
                <p class="text-gray-500">{{ $permohonan->user->email }} • {{ $permohonan->user->mahasiswaProfile->no_hp ?? '-' }}</p>
            </div>
        </div>
        <div class="mt-4 md:mt-0 text-right">
            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold uppercase tracking-wider">Menunggu Keputusan</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Data Diri & Akademik -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Data Akademik</h3>
            <ul class="space-y-3 text-sm">
                <li class="flex justify-between"><span class="text-gray-500">Universitas</span> <span class="font-bold text-gray-800">{{ $permohonan->user->mahasiswaProfile->universitas ?? '-' }}</span></li>
                <li class="flex justify-between"><span class="text-gray-500">Program Studi</span> <span class="font-bold text-gray-800">{{ $permohonan->user->mahasiswaProfile->program_studi ?? '-' }}</span></li>
                <li class="flex justify-between"><span class="text-gray-500">Instagram</span> <span class="font-bold text-gray-800">{{ $permohonan->user->mahasiswaProfile->username_ig ?? '-' }}</span></li>
                <li class="flex justify-between"><span class="text-gray-500">Jenis Kelamin</span> <span class="font-bold text-gray-800">{{ $permohonan->user->mahasiswaProfile->jenis_kelamin ?? '-' }}</span></li>
                <li class="flex justify-between"><span class="text-gray-500">Status Konversi</span> <span class="font-bold text-gray-800">{{ $permohonan->user->mahasiswaProfile->status_konversi ? 'Ya' : 'Tidak' }}</span></li>
                <li class="flex justify-between"><span class="text-gray-500">BPJS</span> <span class="font-bold text-gray-800">{{ $permohonan->user->mahasiswaProfile->punya_bpjs ? 'Sudah Punya' : 'Belum Punya' }}</span></li>
                <li class="flex justify-between"><span class="text-gray-500">Device</span> <span class="font-bold text-gray-800">{{ $permohonan->user->mahasiswaProfile->device ?? '-' }}</span></li>
            </ul>
        </div>

        <!-- Detail Pengajuan & Dokumen -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Rencana Magang</h3>
            <ul class="space-y-3 text-sm mb-6">
                <li class="flex justify-between"><span class="text-gray-500">Tanggal Mulai</span> <span class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($permohonan->tanggal_mulai_rencana)->format('d M Y') }}</span></li>
                <li class="flex justify-between"><span class="text-gray-500">Tanggal Selesai</span> <span class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($permohonan->tanggal_selesai_rencana)->format('d M Y') }}</span></li>
            </ul>

            <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Dokumen Pendukung</h3>
            @if($permohonan->dokumens->isEmpty())
                <p class="text-sm text-red-500">Tidak ada dokumen yang diunggah.</p>
            @else
                <ul class="space-y-2">
                    @foreach($permohonan->dokumens as $dok)
                        <li>
                            <a href="{{ Storage::url($dok->path) }}" target="_blank" class="flex items-center gap-2 p-3 bg-gray-50 rounded-xl hover:bg-orange-50 transition-colors border border-gray-100 group">
                                <div class="w-8 h-8 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center group-hover:bg-orange-500 group-hover:text-white transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ $dok->nama_asli }}</p>
                                    <p class="text-xs text-gray-500 uppercase">{{ $dok->jenis }}</p>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Form Keputusan -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-800">Keputusan Akhir</h3>
            <p class="text-sm text-gray-500">Beri catatan dan tentukan status akhir pendaftar ini.</p>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Form Terima -->
            <div class="border border-green-200 rounded-xl p-6 bg-green-50/30">
                <h4 class="font-bold text-green-800 mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Terima Mahasiswa
                </h4>
                <p class="text-xs text-green-700 mb-4">Mahasiswa akan otomatis diubah rolenya dan dibuatkan jadwal magang aktif.</p>
                <form action="{{ route('superuser.permohonan.approve', $permohonan->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin MENERIMA pendaftar ini?')">
                    @csrf
                    <textarea name="catatan_superuser" rows="2" placeholder="Catatan (Opsional)..." class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm mb-3"></textarea>
                    <button type="submit" class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-colors shadow-sm">
                        Terima & Aktifkan Magang
                    </button>
                </form>
            </div>

            <!-- Form Tolak -->
            <div class="border border-red-200 rounded-xl p-6 bg-red-50/30">
                <h4 class="font-bold text-red-800 mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Tolak Pendaftar
                </h4>
                <p class="text-xs text-red-700 mb-4">Pendaftar akan ditolak dan tidak bisa melanjutkan proses magang.</p>
                <form action="{{ route('superuser.permohonan.reject', $permohonan->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin MENOLAK pendaftar ini?')">
                    @csrf
                    <textarea name="catatan_superuser" rows="2" placeholder="Alasan Penolakan (Wajib diisi)..." required class="w-full border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 text-sm mb-3"></textarea>
                    <button type="submit" class="w-full py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-colors shadow-sm">
                        Tolak Pendaftaran
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection