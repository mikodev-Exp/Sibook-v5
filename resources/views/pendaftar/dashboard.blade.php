@extends('layouts.role', ['role' => 'pendaftar'])

@section('title', 'Applicant Pass & Status Pendaftaran')

@section('content')
@php
    $user = auth()->user();
    $permohonan = $user->permohonanMagang;
@endphp

<div class="max-w-4xl mx-auto space-y-6">

    {{-- Applicant Pass Header Card --}}
    <div class="bg-zinc-950 text-white rounded-3xl p-8 border border-zinc-800 shadow-xl relative overflow-hidden">
        <div class="absolute -right-16 -bottom-16 w-64 h-64 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 pb-6 border-b border-zinc-800/80">
            <div>
                <span class="text-[10px] font-mono-tag uppercase tracking-widest text-zinc-400 block mb-1.5">// APPLICANT PASS IDENTIFIER</span>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    {{ $user->name }}
                </h1>
                <p class="text-xs text-zinc-400 mt-1 font-mono-tag">PASS ID: #SB-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }} • {{ $user->email }}</p>
            </div>

            <div>
                @if($permohonan)
                    @if($permohonan->status === 'submitted')
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-mono-tag font-bold bg-amber-950/80 text-amber-300 border border-amber-500/30">
                            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                            STATUS: REVIEW ADMIN
                        </span>
                    @elseif($permohonan->status === 'reviewed_by_admin')
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-mono-tag font-bold bg-blue-950/80 text-blue-300 border border-blue-500/30">
                            <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                            STATUS: FINALISASI SUPER USER
                        </span>
                    @elseif($permohonan->status === 'approved')
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-mono-tag font-bold bg-emerald-950/80 text-emerald-300 border border-emerald-500/30">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            STATUS: DISETUJUI (MAGANG AKTIF)
                        </span>
                    @elseif($permohonan->status === 'rejected')
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-mono-tag font-bold bg-rose-950/80 text-rose-300 border border-rose-500/30">
                            <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                            STATUS: DITOLAK
                        </span>
                    @endif
                @else
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-mono-tag font-bold bg-zinc-800 text-zinc-300 border border-zinc-700">
                        STATUS: BELUM MENGAJUKAN
                    </span>
                @endif
            </div>
        </div>

        <div class="mt-6 flex flex-wrap items-center justify-between text-xs font-mono-tag text-zinc-400 gap-4">
            <div>
                <span class="text-zinc-500 block text-[10px]">PROGRAM</span>
                <span class="text-zinc-200">BPS SURABAYA INTERNSHIP</span>
            </div>
            <div>
                <span class="text-zinc-500 block text-[10px]">VERIFIKASI</span>
                <span class="text-emerald-400 font-semibold">ON-CHAIN DATABASE</span>
            </div>
            <div>
                <span class="text-zinc-500 block text-[10px]">TERAKHIR DIPERBARUI</span>
                <span class="text-zinc-200">{{ $permohonan ? $permohonan->updated_at->format('d M Y, H:i') : now()->format('d M Y') }} WIB</span>
            </div>
        </div>
    </div>

    {{-- Main Content Section --}}
    @if(!$permohonan)
        <div class="bg-white rounded-3xl p-12 text-center border border-zinc-200/80 shadow-sm">
            <div class="w-14 h-14 rounded-2xl bg-zinc-100 border border-zinc-200 text-zinc-700 mx-auto flex items-center justify-center font-bold text-xl mb-4 font-mono-tag">
                +
            </div>
            <h3 class="text-lg font-bold text-zinc-950 mb-1">Permohonan Belum Dibuat</h3>
            <p class="text-xs text-zinc-500 max-w-sm mx-auto mb-6">
                Anda telah memiliki akun pendaftar, namun belum mengisi formulir dan mengunggah berkas persyaratan magang.
            </p>
            <a href="{{ route('pendaftar.register') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-zinc-950 text-white rounded-xl hover:bg-zinc-800 transition font-semibold text-xs font-mono-tag shadow-md">
                <span>Isi & Ajukan Permohonan Sekarang</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    @else
        {{-- Data Diri & Detail Magang Grid --}}
        <div class="grid md:grid-cols-2 gap-6">
            
            {{-- Data Diri --}}
            <div class="bg-white rounded-3xl p-7 border border-zinc-200/80 shadow-sm">
                <div class="flex items-center gap-3 pb-4 mb-4 border-b border-zinc-100">
                    <span class="w-7 h-7 rounded-lg bg-zinc-950 text-white flex items-center justify-center font-mono-tag text-xs font-bold">A</span>
                    <h3 class="text-sm font-bold text-zinc-950 uppercase tracking-wide">Data Diri Calon Peserta</h3>
                </div>
                <dl class="space-y-3 text-xs">
                    <div class="flex justify-between py-1 border-b border-zinc-50">
                        <dt class="text-zinc-400 font-mono-tag">NAMA LENGKAP</dt>
                        <dd class="font-bold text-zinc-900 text-right">{{ $user->name }}</dd>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-50">
                        <dt class="text-zinc-400 font-mono-tag">EMAIL</dt>
                        <dd class="font-medium text-zinc-900 text-right">{{ $user->email }}</dd>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-50">
                        <dt class="text-zinc-400 font-mono-tag">NOMOR HP</dt>
                        <dd class="font-mono-tag text-zinc-900 text-right">{{ $user->mahasiswaProfile->no_hp ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-50">
                        <dt class="text-zinc-400 font-mono-tag">PERGURUAN TINGGI</dt>
                        <dd class="font-medium text-zinc-900 text-right">{{ $user->mahasiswaProfile->universitas ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-50">
                        <dt class="text-zinc-400 font-mono-tag">PROGRAM STUDI</dt>
                        <dd class="font-medium text-zinc-900 text-right">{{ $user->mahasiswaProfile->program_studi ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-50">
                        <dt class="text-zinc-400 font-mono-tag">INSTAGRAM</dt>
                        <dd class="font-mono-tag text-zinc-900 text-right">{{ $user->mahasiswaProfile->username_ig ? '@'.$user->mahasiswaProfile->username_ig : '-' }}</dd>
                    </div>
                    @if(!empty($user->mahasiswaProfile->nama_dosen_pembimbing))
                        <div class="flex justify-between py-1 border-b border-zinc-50">
                            <dt class="text-zinc-400 font-mono-tag">DOSEN PEMBIMBING</dt>
                            <dd class="font-medium text-zinc-900 text-right">{{ $user->mahasiswaProfile->nama_dosen_pembimbing }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Detail Magang --}}
            <div class="bg-white rounded-3xl p-7 border border-zinc-200/80 shadow-sm">
                <div class="flex items-center gap-3 pb-4 mb-4 border-b border-zinc-100">
                    <span class="w-7 h-7 rounded-lg bg-zinc-950 text-white flex items-center justify-center font-mono-tag text-xs font-bold">B</span>
                    <h3 class="text-sm font-bold text-zinc-950 uppercase tracking-wide">Rencana Periode & Ketentuan</h3>
                </div>
                <dl class="space-y-3 text-xs">
                    <div class="flex justify-between py-1 border-b border-zinc-50">
                        <dt class="text-zinc-400 font-mono-tag">TANGGAL MULAI</dt>
                        <dd class="font-mono-tag font-bold text-zinc-900 text-right">{{ \Carbon\Carbon::parse($permohonan->tanggal_mulai_rencana)->format('d M Y') }}</dd>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-50">
                        <dt class="text-zinc-400 font-mono-tag">TANGGAL SELESAI</dt>
                        <dd class="font-mono-tag font-bold text-zinc-900 text-right">{{ \Carbon\Carbon::parse($permohonan->tanggal_selesai_rencana)->format('d M Y') }}</dd>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-50">
                        <dt class="text-zinc-400 font-mono-tag">STATUS KONVERSI</dt>
                        <dd class="font-mono-tag text-right font-semibold {{ $user->mahasiswaProfile->status_konversi ? 'text-indigo-600' : 'text-zinc-600' }}">
                            {{ $user->mahasiswaProfile->status_konversi ? 'YA (MIN 3 BULAN)' : 'TIDAK (MIN 2.5 BULAN)' }}
                        </dd>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-50">
                        <dt class="text-zinc-400 font-mono-tag">DEVICE PRIBADI</dt>
                        <dd class="font-medium text-zinc-900 text-right">{{ $user->mahasiswaProfile->device ?? 'Laptop Pribadi' }}</dd>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-50">
                        <dt class="text-zinc-400 font-mono-tag">KEPEMILIKAN BPJS</dt>
                        <dd class="font-mono-tag text-zinc-900 text-right">{{ $user->mahasiswaProfile->punya_bpjs ? 'YA' : 'TIDAK' }}</dd>
                    </div>
                    <div class="flex justify-between py-1 border-b border-zinc-50">
                        <dt class="text-zinc-400 font-mono-tag">TANGGAL SUBMIT</dt>
                        <dd class="font-mono-tag text-zinc-900 text-right">{{ $permohonan->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Status Ujian Kompetensi --}}
        <div class="bg-white rounded-3xl p-7 border border-zinc-200/80 shadow-sm">
            <div class="flex items-center justify-between pb-4 mb-5 border-b border-zinc-100">
                <div>
                    <h3 class="text-sm font-bold text-zinc-950 uppercase tracking-wide">Status Ujian Kompetensi</h3>
                    <p class="text-xs text-zinc-400 mt-0.5">Pantau hasil seleksi kompetensi awal secara berkala.</p>
                </div>
                <a href="{{ route('pendaftar.ujian.index') }}" class="text-xs font-mono-tag text-zinc-600 hover:text-zinc-950 font-bold underline">
                    Buka Halaman Ujian →
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Ujian Esai --}}
                <div class="p-4 rounded-2xl border border-zinc-200/80 bg-zinc-50/50 flex items-center justify-between font-mono-tag">
                    <div>
                        <span class="text-[10px] text-zinc-400 block uppercase">STAGE 01</span>
                        <span class="text-xs font-bold text-zinc-900">Ujian Esai</span>
                    </div>
                    @if($permohonan->status_ujian_esai === 'lulus')
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">LULUS</span>
                    @elseif($permohonan->status_ujian_esai === 'gagal')
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">GAGAL</span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-zinc-200 text-zinc-700">MENUNGGU</span>
                    @endif
                </div>

                {{-- Ujian Tulis --}}
                <div class="p-4 rounded-2xl border border-zinc-200/80 bg-zinc-50/50 flex items-center justify-between font-mono-tag">
                    <div>
                        <span class="text-[10px] text-zinc-400 block uppercase">STAGE 02</span>
                        <span class="text-xs font-bold text-zinc-900">Ujian Tulis</span>
                    </div>
                    @if($permohonan->status_ujian_tulis === 'lulus')
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">LULUS</span>
                    @elseif($permohonan->status_ujian_tulis === 'gagal')
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">GAGAL</span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-zinc-200 text-zinc-700">MENUNGGU</span>
                    @endif
                </div>

                {{-- Interview --}}
                <div class="p-4 rounded-2xl border border-zinc-200/80 bg-zinc-50/50 flex items-center justify-between font-mono-tag">
                    <div>
                        <span class="text-[10px] text-zinc-400 block uppercase">STAGE 03</span>
                        <span class="text-xs font-bold text-zinc-900">Wawancara</span>
                    </div>
                    @if($permohonan->status_interview === 'lulus')
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">LULUS</span>
                    @elseif($permohonan->status_interview === 'gagal')
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">GAGAL</span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-zinc-200 text-zinc-700">MENUNGGU</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Notifikasi Status Peninjauan --}}
        @if($permohonan->status === 'submitted' || $permohonan->status === 'reviewed_by_admin')
            <div class="p-5 rounded-2xl bg-zinc-900 text-white border border-zinc-800 flex items-start gap-4">
                <div class="w-8 h-8 rounded-xl bg-zinc-800 flex items-center justify-center shrink-0 mt-0.5">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                </div>
                <div>
                    <h4 class="text-xs font-bold font-mono-tag uppercase text-amber-300">Pemberitahuan Kurasi</h4>
                    <p class="text-xs text-zinc-300 mt-1 leading-relaxed">
                        Pengajuan Anda saat ini berada dalam antrean kurasi dan verifikasi dokumen oleh Admin BPS Kota Surabaya. Pembaruan status akan tercatat secara otomatis pada Applicant Pass ini.
                    </p>
                </div>
            </div>
        @elseif($permohonan->status === 'rejected')
            <div class="p-5 rounded-2xl bg-rose-50 text-rose-900 border border-rose-200 flex items-start gap-4">
                <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-sm shrink-0">
                    ✕
                </div>
                <div>
                    <h4 class="text-xs font-bold font-mono-tag uppercase text-rose-800">Permohonan Tidak Diterima</h4>
                    <p class="text-xs text-rose-700 mt-1 leading-relaxed">
                        Catatan Admin: {{ $permohonan->catatan_admin ?? 'Kapasitas kuota untuk jurusan terkait telah penuh atau berkas belum memenuhi kualifikasi.' }}
                    </p>
                </div>
            </div>
        @endif
    @endif

</div>
@endsection
