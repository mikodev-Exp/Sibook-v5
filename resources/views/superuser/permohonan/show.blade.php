@extends('layouts.role', ['role' => 'superuser'])

@section('title', 'Detail & Finalisasi Pendaftar Magang')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Tombol Kembali -->
    <div>
        <a href="{{ route('superuser.permohonan.index') }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-zinc-900 transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Permohonan
        </a>
    </div>

    <!-- Header Profil -->
    <div class="bg-white rounded-3xl shadow-sm border border-zinc-200/80 p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-zinc-950 text-white rounded-2xl flex items-center justify-center text-xl font-bold font-mono shadow-sm">
                {{ strtoupper(substr($permohonan->user->name ?? '?', 0, 2)) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-2xl font-extrabold text-zinc-900 tracking-tight">{{ $permohonan->user->name }}</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-mono font-bold uppercase tracking-wider
                        @if($permohonan->status === \App\Enums\StatusPermohonan::Approved) bg-emerald-50 text-emerald-700 border border-emerald-200
                        @elseif($permohonan->status === \App\Enums\StatusPermohonan::ReviewedByAdmin) bg-blue-50 text-blue-700 border border-blue-200
                        @elseif($permohonan->status === \App\Enums\StatusPermohonan::Rejected) bg-rose-50 text-rose-700 border border-rose-200
                        @else bg-amber-50 text-amber-700 border border-amber-200 @endif">
                        {{ $permohonan->status->label() }}
                    </span>
                </div>
                <p class="text-xs text-zinc-500 mt-1 font-mono">{{ $permohonan->user->email }} • {{ $permohonan->user->mahasiswaProfile->no_hp ?? '-' }}</p>
            </div>
        </div>
        <div class="text-right font-mono text-xs text-zinc-400">
            <span>Diajukan: {{ $permohonan->created_at ? $permohonan->created_at->translatedFormat('d M Y, H:i') : '-' }} WIB</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Data Diri & Akademik -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-zinc-200/80">
            <h3 class="font-bold text-zinc-900 text-sm mb-4 border-b border-zinc-100 pb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                <span>Data Diri &amp; Akademik</span>
            </h3>
            <ul class="space-y-3 text-xs">
                <li class="flex justify-between py-1 border-b border-zinc-50"><span class="text-zinc-500">Perguruan Tinggi</span> <span class="font-bold text-zinc-900 text-right">{{ $permohonan->user->mahasiswaProfile->universitas ?? '-' }}</span></li>
                <li class="flex justify-between py-1 border-b border-zinc-50"><span class="text-zinc-500">Program Studi</span> <span class="font-bold text-zinc-900 text-right">{{ $permohonan->user->mahasiswaProfile->program_studi ?? '-' }}</span></li>
                <li class="flex justify-between py-1 border-b border-zinc-50"><span class="text-zinc-500">NIM / NPM</span> <span class="font-bold text-zinc-900 font-mono">{{ $permohonan->user->mahasiswaProfile->nim ?? '-' }}</span></li>
                <li class="flex justify-between py-1 border-b border-zinc-50"><span class="text-zinc-500">Jenis Kelamin</span> <span class="font-semibold text-zinc-800">{{ $permohonan->user->mahasiswaProfile->jenis_kelamin ?? '-' }}</span></li>
                <li class="flex justify-between py-1 border-b border-zinc-50"><span class="text-zinc-500">Status Konversi</span> <span class="font-semibold text-zinc-800">{{ ($permohonan->user->mahasiswaProfile->status_konversi ?? false) ? 'Ya (Konversi SKS)' : 'Bukan Konversi' }}</span></li>
                <li class="flex justify-between py-1 border-b border-zinc-50"><span class="text-zinc-500">Kepemilikan BPJS</span> <span class="font-semibold text-zinc-800">{{ ($permohonan->user->mahasiswaProfile->punya_bpjs ?? false) ? 'Tersedia' : 'Belum Ada' }}</span></li>
                <li class="flex justify-between py-1 border-b border-zinc-50"><span class="text-zinc-500">Instagram</span> <span class="font-mono text-zinc-800">{{ $permohonan->user->mahasiswaProfile->username_ig ?? '-' }}</span></li>
                <li class="flex justify-between py-1"><span class="text-zinc-500">Perangkat Kerja</span> <span class="font-semibold text-zinc-800">{{ $permohonan->user->mahasiswaProfile->device ?? '-' }}</span></li>
            </ul>
        </div>

        <!-- Detail Rencana & Dokumen -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-zinc-200/80">
            <h3 class="font-bold text-zinc-900 text-sm mb-4 border-b border-zinc-100 pb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Rencana Periode Magang</span>
            </h3>
            <div class="grid grid-cols-2 gap-3 mb-6 font-mono text-xs">
                <div class="p-3 bg-zinc-50 rounded-2xl border border-zinc-200/60">
                    <span class="text-[10px] text-zinc-400 block mb-1">TANGGAL MULAI</span>
                    <span class="font-bold text-zinc-900">{{ $permohonan->tanggal_mulai_rencana ? \Carbon\Carbon::parse($permohonan->tanggal_mulai_rencana)->translatedFormat('d M Y') : '-' }}</span>
                </div>
                <div class="p-3 bg-zinc-50 rounded-2xl border border-zinc-200/60">
                    <span class="text-[10px] text-zinc-400 block mb-1">TANGGAL SELESAI</span>
                    <span class="font-bold text-zinc-900">{{ $permohonan->tanggal_selesai_rencana ? \Carbon\Carbon::parse($permohonan->tanggal_selesai_rencana)->translatedFormat('d M Y') : '-' }}</span>
                </div>
            </div>

            <h3 class="font-bold text-zinc-900 text-sm mb-3 border-b border-zinc-100 pb-2">Dokumen Pendukung</h3>
            @if($permohonan->dokumens->isEmpty())
                <p class="text-xs text-rose-500 italic py-2">Tidak ada dokumen yang diunggah.</p>
            @else
                <ul class="space-y-2">
                    @foreach($permohonan->dokumens as $dok)
                        <li>
                            <a href="{{ Storage::url($dok->path) }}" target="_blank" class="flex items-center gap-3 p-3 bg-zinc-50 rounded-2xl hover:bg-zinc-100 transition-colors border border-zinc-200/60 group">
                                <div class="w-8 h-8 bg-zinc-950 text-white rounded-xl flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-zinc-900 truncate">{{ $dok->nama_asli }}</p>
                                    <p class="text-[10px] text-zinc-400 font-mono uppercase">{{ $dok->jenis }}</p>
                                </div>
                                <span class="text-[10px] font-mono text-zinc-400">LIHAT &rarr;</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Tinjauan Kelayakan oleh Admin (Hierarki Otoritas) -->
    <div class="bg-white rounded-3xl shadow-sm border border-zinc-200/80 p-6">
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-zinc-100">
            <div>
                <span class="text-[10px] font-mono uppercase tracking-widest text-zinc-400 block mb-0.5">// ADMIN VERIFICATION RECORD</span>
                <h3 class="font-extrabold text-zinc-900 text-sm">Hasil Tinjauan Kelayakan oleh Admin</h3>
            </div>
            @if($permohonan->status === \App\Enums\StatusPermohonan::ReviewedByAdmin)
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    Dinyatakan LAYAK oleh Admin
                </span>
            @elseif($permohonan->status === \App\Enums\StatusPermohonan::Approved)
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Lolos Verifikasi &amp; Disetujui
                </span>
            @elseif($permohonan->status === \App\Enums\StatusPermohonan::Rejected)
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                    Dinyatakan Tidak Layak
                </span>
            @else
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                    Menunggu Tinjauan Admin
                </span>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs font-mono">
            <div class="p-3.5 bg-zinc-50 rounded-2xl border border-zinc-200/60">
                <span class="text-[10px] text-zinc-400 block mb-1">ADMIN PENINJAU</span>
                <span class="font-bold text-zinc-900">{{ $permohonan->reviewer->name ?? 'Belum Ditinjau Admin' }}</span>
            </div>
            <div class="p-3.5 bg-zinc-50 rounded-2xl border border-zinc-200/60">
                <span class="text-[10px] text-zinc-400 block mb-1">WAKTU TINJAUAN</span>
                <span class="font-bold text-zinc-900">{{ $permohonan->reviewed_at ? \Carbon\Carbon::parse($permohonan->reviewed_at)->translatedFormat('d F Y, H:i') . ' WIB' : '-' }}</span>
            </div>
            <div class="p-3.5 bg-zinc-50 rounded-2xl border border-zinc-200/60 md:col-span-3">
                <span class="text-[10px] text-zinc-400 block mb-1">CATATAN KELAYAKAN ADMIN:</span>
                <p class="font-sans text-xs text-zinc-700 leading-relaxed">{{ $permohonan->catatan_admin ?: 'Tidak ada catatan tambahan dari Admin.' }}</p>
            </div>
        </div>
    </div>

    <!-- Panel Keputusan Akhir Super Admin -->
    <div class="bg-white rounded-3xl shadow-sm border border-zinc-200/80 overflow-hidden">
        <div class="p-6 border-b border-zinc-100 bg-zinc-50/70 flex items-center justify-between">
            <div>
                <span class="text-[10px] font-mono uppercase tracking-widest text-zinc-400 block mb-0.5">// ROOT DECISION</span>
                <h3 class="font-extrabold text-zinc-900 text-sm">Persetujuan Akhir (Super Admin)</h3>
            </div>
            <span class="text-xs font-mono text-zinc-500">OTORITAS TERTINGGI</span>
        </div>

        @if($permohonan->status === \App\Enums\StatusPermohonan::Approved)
            <div class="p-8 text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto text-xl">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h4 class="text-base font-bold text-zinc-900">Permohonan Ini Telah Disetujui</h4>
                <p class="text-xs text-zinc-500 max-w-md mx-auto">
                    Disetujui oleh <strong>{{ $permohonan->approver->name ?? 'Super User' }}</strong> pada {{ $permohonan->approved_at ? \Carbon\Carbon::parse($permohonan->approved_at)->translatedFormat('d F Y, H:i') : '-' }} WIB. Pemohon telah berstatus resmi sebagai Mahasiswa Magang aktif.
                </p>
                @if($permohonan->catatan_superuser)
                    <div class="mt-4 p-3 bg-zinc-50 rounded-2xl border border-zinc-200 max-w-md mx-auto text-xs text-zinc-700">
                        <span class="font-bold block text-[10px] text-zinc-400 font-mono mb-1">CATATAN SUPER ADMIN:</span>
                        {{ $permohonan->catatan_superuser }}
                    </div>
                @endif
            </div>
        @elseif($permohonan->status === \App\Enums\StatusPermohonan::Submitted)
            <div class="p-8 text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center mx-auto text-xl">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h4 class="text-base font-bold text-zinc-900">Belum Ditinjau oleh Admin</h4>
                <p class="text-xs text-zinc-500 max-w-md mx-auto leading-relaxed">
                    Sesuai hierarki kurasi, berkas pendaftar ini masih dalam tahap verifikasi kelayakan oleh staf <strong>Admin</strong>. Super Admin baru dapat menyetujui permohonan setelah Admin menyatakan berkas ini <strong>LAYAK</strong>.
                </p>
                <div class="pt-2">
                    <a href="{{ route('superuser.permohonan.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-xs font-mono font-semibold transition">
                        &larr; Kembali ke Antrean Siap Disetujui
                    </a>
                </div>
            </div>
        @else
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Form Setujui (Approve) -->
                <div class="border border-emerald-200 rounded-2xl p-6 bg-emerald-50/20 flex flex-col justify-between">
                    <div>
                        <h4 class="font-bold text-emerald-900 text-sm mb-1.5 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Setujui Menjadi Mahasiswa Magang</span>
                        </h4>
                        <p class="text-xs text-emerald-800 leading-relaxed mb-4">
                            Akun pendaftar akan resmi dialihkan perannya menjadi <strong>Mahasiswa</strong> dan jadwal periode magang aktif diterbitkan ke sistem.
                        </p>
                    </div>
                    <form action="{{ route('superuser.permohonan.approve', $permohonan->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin MENYETUJUI pendaftar ini menjadi Mahasiswa Magang?')">
                        @csrf
                        <textarea name="catatan_superuser" rows="2" placeholder="Catatan persetujuan akhir (opsional)..." class="w-full border-emerald-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-xs mb-3 bg-white"></textarea>
                        <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all shadow-sm text-xs">
                            Setujui &amp; Terbitkan Akses Mahasiswa
                        </button>
                    </form>
                </div>

                <!-- Form Tolak (Reject) -->
                <div class="border border-rose-200 rounded-2xl p-6 bg-rose-50/20 flex flex-col justify-between">
                    <div>
                        <h4 class="font-bold text-rose-900 text-sm mb-1.5 flex items-center gap-2">
                            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Tolak Permohonan Pendaftar</span>
                        </h4>
                        <p class="text-xs text-rose-800 leading-relaxed mb-4">
                            Pendaftar akan ditolak dan hak akses akun tidak ditingkatkan menjadi mahasiswa magang.
                        </p>
                    </div>
                    <form action="{{ route('superuser.permohonan.reject', $permohonan->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin MENOLAK pendaftaran magang ini?')">
                        @csrf
                        <textarea name="catatan_superuser" rows="2" placeholder="Alasan penolakan resmi (wajib)..." required class="w-full border-rose-200 rounded-xl focus:ring-rose-500 focus:border-rose-500 text-xs mb-3 bg-white"></textarea>
                        <button type="submit" class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl transition-all shadow-sm text-xs">
                            Konfirmasi Tolak Pendaftaran
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
