@extends('layouts.role', ['role' => 'admin'])

@section('title', 'Detail Permohonan Magang')

@push('styles')
<style>
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .card-anim { animation: fadeUp .3s ease both; }
    .card-anim:nth-child(2) { animation-delay: .05s; }
    .card-anim:nth-child(3) { animation-delay: .10s; }
    .card-anim:nth-child(4) { animation-delay: .15s; }
</style>
@endpush

@section('content')
@php
    $user    = $permohonan->user;
    $profile = $user?->mahasiswaProfile;
    $st      = $permohonan->status instanceof \App\Enums\StatusPermohonan
                ? $permohonan->status
                : \App\Enums\StatusPermohonan::from($permohonan->status);
    $badgeClass = match($st) {
        \App\Enums\StatusPermohonan::Approved        => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        \App\Enums\StatusPermohonan::ReviewedByAdmin => 'bg-blue-50 text-blue-700 border-blue-200',
        \App\Enums\StatusPermohonan::Rejected        => 'bg-rose-50 text-rose-700 border-rose-200',
        \App\Enums\StatusPermohonan::Submitted       => 'bg-amber-50 text-amber-700 border-amber-200',
        default                                       => 'bg-gray-100 text-gray-500 border-gray-200',
    };
@endphp

<div class="max-w-5xl mx-auto space-y-5">

    {{-- ── Breadcrumbs ───────────────────────────────────── --}}
    <nav class="flex items-center gap-2 text-xs font-mono-tag text-zinc-400">
        <a href="{{ route('admin.permohonan.index') }}" class="text-zinc-700 hover:text-zinc-950 font-semibold">PERMOHONAN MAGANG</a>
        <span>/</span>
        <span class="text-zinc-500">DOSSIER #{{ $permohonan->id }}</span>
    </nav>

    {{-- Flash Message --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition
         class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
        <svg class="w-4 h-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
        <button @click="show=false" class="ml-auto text-emerald-500 hover:text-emerald-700">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    @endif

    {{-- ── Hero Header ────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 card-anim">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                {{-- Avatar / Pasfoto --}}
                @if($profile?->link_pasfoto)
                    <a href="{{ $profile->link_pasfoto }}" target="_blank" rel="noopener noreferrer" title="Buka Pasfoto Asli" class="relative group shrink-0">
                        <img src="{{ $profile->link_pasfoto }}" alt="{{ $user?->name }}" 
                             class="w-16 h-16 rounded-2xl object-cover border-2 border-orange-200 shadow-sm group-hover:opacity-90 transition"
                             onerror="this.onerror=null;this.classList.add('hidden');this.nextElementSibling.classList.remove('hidden');">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-orange-100 to-orange-200 hidden items-center justify-center shadow-sm">
                            <span class="text-xl font-extrabold text-orange-600">{{ strtoupper(substr($user?->name ?? '?', 0, 2)) }}</span>
                        </div>
                    </a>
                @else
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center shrink-0 shadow-sm">
                        <span class="text-xl font-extrabold text-orange-600">
                            {{ strtoupper(substr($user?->name ?? '?', 0, 2)) }}
                        </span>
                    </div>
                @endif
                <div>
                    <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">{{ $user?->name ?? '-' }}</h1>
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1 text-xs text-gray-500">
                        <span>{{ $user?->email ?? '-' }}</span>
                        @if($profile?->no_hp)
                            <span>•</span>
                            <span class="text-gray-700 font-medium">{{ $profile->no_hp }}</span>
                        @endif
                        @if($profile?->jenis_kelamin)
                            <span>•</span>
                            <span class="text-gray-600">{{ $profile->jenis_kelamin }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap font-mono-tag">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $badgeClass }}">
                    {{ $st->label() }}
                </span>
                <a href="{{ route('admin.permohonan.index') }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-zinc-100 hover:bg-zinc-200
                          text-xs font-semibold text-zinc-700 transition">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    KEMBALI
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- ── Col 1-2: Info + Dokumen ─────────────────────── --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- 1. Data Diri & Kontak Pemohon --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 card-anim">
                <h2 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </span>
                    Data Pribadi &amp; Kontak Pemohon
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3.5 text-xs">
                    <div>
                        <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Nama Lengkap</div>
                        <div class="text-gray-900 font-semibold text-[13px]">{{ $user?->name ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Email Akun</div>
                        <div class="text-gray-800 font-medium">{{ $user?->email ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Jenis Kelamin</div>
                        <div class="text-gray-800 font-medium">{{ $profile?->jenis_kelamin ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">No. HP / WhatsApp</div>
                        @if($profile?->no_hp)
                            @php
                                $cleanHp = preg_replace('/[^0-9]/', '', $profile->no_hp);
                                if (str_starts_with($cleanHp, '0')) $cleanHp = '62' . substr($cleanHp, 1);
                            @endphp
                            <div class="flex items-center gap-2">
                                <span class="text-gray-900 font-semibold font-mono">{{ $profile->no_hp }}</span>
                                <a href="https://wa.me/{{ $cleanHp }}" target="_blank" rel="noopener noreferrer" 
                                   class="text-[11px] px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-semibold border border-emerald-200 transition">
                                    Chat WA &rarr;
                                </a>
                            </div>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Username Instagram</div>
                        @if($profile?->username_ig)
                            <a href="https://instagram.com/{{ ltrim($profile->username_ig, '@') }}" target="_blank" rel="noopener noreferrer" 
                               class="text-indigo-600 hover:text-indigo-800 font-semibold flex items-center gap-1">
                                <span>{{ $profile->username_ig }}</span>
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Pasfoto Resmi</div>
                        @if($profile?->link_pasfoto)
                            <a href="{{ $profile->link_pasfoto }}" target="_blank" rel="noopener noreferrer"
                               class="text-orange-600 hover:text-orange-700 font-semibold flex items-center gap-1">
                                <span>Buka Tautan Pasfoto</span>
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 2. Data Akademik & Dosen Pembimbing --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 card-anim">
                <h2 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </span>
                    Data Akademik &amp; Dosen Pembimbing
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3.5 text-xs">
                    <div>
                        <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Universitas / Institut</div>
                        <div class="text-gray-900 font-semibold text-[13px]">{{ $profile?->universitas ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Program Studi</div>
                        <div class="text-gray-900 font-semibold text-[13px]">{{ $profile?->program_studi ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Nama Dosen Pembimbing</div>
                        <div class="text-gray-900 font-semibold">{{ $profile?->nama_dosen_pembimbing ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">No. HP Dosen Pembimbing</div>
                        @if($profile?->no_hp_dosen_pembimbing)
                            @php
                                $cleanDosenHp = preg_replace('/[^0-9]/', '', $profile->no_hp_dosen_pembimbing);
                                if (str_starts_with($cleanDosenHp, '0')) $cleanDosenHp = '62' . substr($cleanDosenHp, 1);
                            @endphp
                            <div class="flex items-center gap-2">
                                <span class="text-gray-900 font-semibold font-mono">{{ $profile->no_hp_dosen_pembimbing }}</span>
                                <a href="https://wa.me/{{ $cleanDosenHp }}" target="_blank" rel="noopener noreferrer" 
                                   class="text-[11px] px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-semibold border border-emerald-200 transition">
                                    Chat WA &rarr;
                                </a>
                            </div>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Status Konversi SKS</div>
                        @if($profile?->status_konversi)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Ya (Konversi SKS - Min. 3 Bulan)
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                Tidak (Reguler - Min. 2,5 Bulan)
                            </span>
                        @endif
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Perangkat Utama (Device)</div>
                        <div class="text-gray-800 font-medium">{{ $profile?->device ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">BPJS Ketenagakerjaan</div>
                        @if($profile?->punya_bpjs)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                Sudah Punya
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                Belum Punya
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 3. Rencana Periode Magang --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 card-anim">
                <h2 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </span>
                    Rencana Periode Magang
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-150">
                        <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Tanggal Mulai</div>
                        <div class="text-gray-900 font-bold text-sm">
                            {{ $permohonan->tanggal_mulai_rencana ? \Carbon\Carbon::parse($permohonan->tanggal_mulai_rencana)->isoFormat('D MMMM Y') : '-' }}
                        </div>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-150">
                        <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Tanggal Selesai</div>
                        <div class="text-gray-900 font-bold text-sm">
                            {{ $permohonan->tanggal_selesai_rencana ? \Carbon\Carbon::parse($permohonan->tanggal_selesai_rencana)->isoFormat('D MMMM Y') : '-' }}
                        </div>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-150">
                        <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Total Durasi</div>
                        @if($permohonan->tanggal_mulai_rencana && $permohonan->tanggal_selesai_rencana)
                            @php
                                $dMulai = \Carbon\Carbon::parse($permohonan->tanggal_mulai_rencana);
                                $dSelesai = \Carbon\Carbon::parse($permohonan->tanggal_selesai_rencana);
                                $totalHari = $dMulai->diffInDays($dSelesai) + 1;
                                $totalBulan = round($totalHari / 30, 1);
                            @endphp
                            <div class="text-emerald-700 font-bold text-sm">
                                ± {{ $totalBulan }} Bulan <span class="text-xs font-normal text-gray-500">({{ $totalHari }} Hari)</span>
                            </div>
                        @else
                            <div class="text-gray-400 font-bold">-</div>
                        @endif
                    </div>
                    <div class="sm:col-span-3 text-[11px] text-gray-400 pt-1">
                        Diajukan pada: <span class="font-medium text-gray-700">{{ $permohonan->created_at ? \Carbon\Carbon::parse($permohonan->created_at)->isoFormat('dddd, D MMMM Y - HH:mm') . ' WIB' : '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Dokumen Berkas --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 card-anim">
                <h2 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </span>
                    Berkas Dokumen
                    <span class="ml-auto text-[11px] font-normal text-gray-400">{{ $permohonan->dokumens?->count() ?? 0 }} file</span>
                </h2>

                <div x-data="{ previewUrl: '', previewName: '', previewExt: '', showPreview: false }">

                @forelse($permohonan->dokumens ?? [] as $dok)
                    @php
                        $ext = strtolower(pathinfo($dok->nama_asli ?? $dok->path, PATHINFO_EXTENSION));
                        $iconColor = match($ext) {
                            'pdf'  => 'text-rose-500 bg-rose-50 border-rose-200',
                            'doc','docx' => 'text-blue-500 bg-blue-50 border-blue-200',
                            'jpg','jpeg','png','webp' => 'text-purple-500 bg-purple-50 border-purple-200',
                            'xls','xlsx' => 'text-emerald-500 bg-emerald-50 border-emerald-200',
                            default => 'text-gray-500 bg-gray-100 border-gray-200',
                        };
                        $isPreviewable = in_array($ext, ['pdf','jpg','jpeg','png','webp']);
                        $fileUrl  = route('admin.permohonan.dokumen', [$permohonan, $dok]);
                        $downloadUrl = route('admin.permohonan.dokumen', [$permohonan, $dok, 'download' => 1]);
                        $fileName = $dok->nama_asli ?? basename($dok->path);
                    @endphp
                    <div class="flex items-center justify-between gap-3 p-3 rounded-xl border border-gray-100 hover:border-gray-200 hover:bg-gray-50/50 transition group mb-2">
                        {{-- Klik kiri (ikon + nama) untuk preview --}}
                        <div class="flex items-center gap-3 min-w-0 {{ $isPreviewable ? 'cursor-pointer' : 'cursor-default' }} select-none"
                             @if($isPreviewable)
                             @click="previewUrl = '{{ $fileUrl }}'; previewName = '{{ addslashes($fileName) }}'; previewExt = '{{ $ext }}'; showPreview = true"
                             @endif
                             title="{{ $isPreviewable ? 'Klik untuk lihat preview' : 'File tidak dapat dipreview' }}">
                            <div class="w-9 h-9 rounded-lg {{ $iconColor }} border flex items-center justify-center shrink-0 font-bold text-[10px] uppercase">
                                {{ $ext ?: 'FILE' }}
                            </div>
                            <div class="min-w-0">
                                <div class="text-xs font-semibold text-gray-800 truncate group-hover:text-gray-900">
                                    {{ $fileName }}
                                </div>
                                @if($dok->jenis)
                                    <div class="text-[10.5px] text-gray-400">{{ ucwords(str_replace('_', ' ', $dok->jenis)) }}</div>
                                @endif
                                @if($isPreviewable)
                                    <div class="text-[9.5px] text-blue-400 mt-0.5">Klik untuk preview</div>
                                @endif
                            </div>
                        </div>
                        {{-- Tombol unduh tetap sama, langsung download via controller --}}
                        <a href="{{ $downloadUrl }}"
                           class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-orange-50 hover:bg-orange-100
                                  text-orange-600 text-xs font-semibold transition border border-orange-200">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Unduh File
                        </a>
                    </div>
                @empty
                    <div class="py-8 text-center text-gray-400 text-sm">
                        Belum ada berkas yang diunggah.
                    </div>
                @endforelse

                {{-- ── Modal Preview ── --}}
                <div x-show="showPreview" x-cloak
                     class="fixed inset-0 z-[999] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
                     @keydown.escape.window="showPreview = false"
                     style="display:none;">
                    <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[92vh] flex flex-col shadow-2xl"
                         @click.outside="showPreview = false">
                        {{-- Header --}}
                        <div class="flex items-center justify-between gap-3 px-5 py-3 border-b border-gray-100">
                            <div class="flex items-center gap-2 min-w-0">
                                <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="text-sm font-semibold text-gray-800 truncate" x-text="previewName"></p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <a :href="previewUrl + '?download=1'"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-orange-50 hover:bg-orange-100 text-orange-600 text-xs font-semibold transition border border-orange-200">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Unduh
                                </a>
                                <button @click="showPreview = false"
                                        class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                        {{-- Body --}}
                        <div class="flex-1 overflow-hidden p-3 min-h-0">
                            <template x-if="previewExt === 'pdf'">
                                <iframe :src="previewUrl" class="w-full rounded-xl border-0" style="height:76vh;"></iframe>
                            </template>
                            <template x-if="['jpg','jpeg','png','webp'].includes(previewExt)">
                                <div class="flex items-center justify-center h-full" style="height:76vh;">
                                    <img :src="previewUrl" :alt="previewName" class="max-w-full max-h-full object-contain rounded-xl shadow">
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                </div>{{-- end x-data --}}
            </div>
        </div>

        {{-- ── Col 3: Status & Aksi ────────────────────────── --}}
        <div class="space-y-5">

            {{-- Status card --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 card-anim">
                <h2 class="text-sm font-bold text-gray-700 mb-4">Status Verifikasi</h2>

                {{-- Timeline status 5 tahap hierarki --}}
                @php
                    $stVal = $st->value;
                    $isSuperUserRejected = ($stVal === 'rejected' && $permohonan->approved_by);
                    $isAdminRejected = ($stVal === 'rejected' && !$isSuperUserRejected);

                    // Step aktif hierarki:
                    // 1: Draft
                    // 2: Diajukan
                    // 3: Ditinjau Admin
                    // 4: Ditinjau Super Admin
                    // 5: Keputusan Akhir (Disetujui / Ditolak)
                    $activeStep = match($stVal) {
                        'draft'             => 1,
                        'submitted'         => 3,
                        'reviewed_by_admin' => 4,
                        'approved'          => 5,
                        'rejected'          => ($isSuperUserRejected ? 5 : 3),
                        default             => 1,
                    };

                    $steps = [
                        1 => ['label' => 'Draft', 'desc' => 'Penyusunan berkas'],
                        2 => ['label' => 'Diajukan', 'desc' => 'Terkirim ke sistem'],
                        3 => ['label' => 'Ditinjau Admin', 'desc' => 'Verifikasi kelayakan berkas'],
                        4 => ['label' => 'Ditinjau Super Admin', 'desc' => 'Evaluasi kelayakan akhir'],
                        5 => [
                            'label' => $stVal === 'approved' ? 'Disetujui' : ($stVal === 'rejected' ? 'Ditolak' : 'Disetujui / Ditolak'),
                            'desc'  => 'Penerbitan hak akses mahasiswa'
                        ],
                    ];
                @endphp

                <div class="relative pl-5 space-y-4">
                    <div class="absolute left-[9px] top-2 bottom-2 w-0.5 bg-gray-100"></div>

                    @foreach($steps as $stepNum => $stepData)
                        @php
                            $isDone = false;
                            $isCurrent = false;
                            $isFailed = false;

                            if ($stVal === 'rejected') {
                                if ($stepNum < $activeStep) {
                                    $isDone = true;
                                } elseif ($stepNum === $activeStep) {
                                    $isFailed = true;
                                }
                            } else {
                                if ($stepNum < $activeStep) {
                                    $isDone = true;
                                } elseif ($stepNum === $activeStep) {
                                    $isCurrent = true;
                                }
                            }
                        @endphp

                        <div class="flex items-start gap-2.5 relative">
                            {{-- Step Dot / Icon --}}
                            <div class="w-4 h-4 rounded-full border-2 shrink-0 z-10 mt-0.5 flex items-center justify-center
                                {{ $isFailed ? 'bg-rose-500 border-rose-500' : ($isDone ? 'bg-emerald-500 border-emerald-500' : ($isCurrent ? 'bg-orange-500 border-orange-500 shadow-sm shadow-orange-200' : 'bg-gray-100 border-gray-200')) }}">
                                @if($isDone)
                                    <svg class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                @elseif($isFailed)
                                    <svg class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                @elseif($isCurrent)
                                    <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                                @endif
                            </div>

                            {{-- Step Label & Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-semibold
                                        {{ $isFailed ? 'text-rose-600 font-bold' : ($isCurrent ? 'text-orange-600 font-bold' : ($isDone ? 'text-gray-800' : 'text-gray-400')) }}">
                                        {{ $stepData['label'] }}
                                    </span>
                                    @if($isCurrent)
                                        <span class="text-[10px] text-orange-600 font-semibold bg-orange-50 border border-orange-200/60 px-1.5 py-0.5 rounded-full">
                                            Sekarang
                                        </span>
                                    @elseif($isFailed)
                                        <span class="text-[10px] text-rose-600 font-semibold bg-rose-50 border border-rose-200/60 px-1.5 py-0.5 rounded-full">
                                            Ditolak
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($permohonan->reviewed_at || $permohonan->approved_at)
                    <div class="mt-5 pt-3 border-t border-gray-100 text-[11px] text-gray-500 space-y-1">
                        @if($permohonan->reviewed_at)
                            <div>
                                <span class="text-gray-400">Ditinjau Admin:</span>
                                <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($permohonan->reviewed_at)->isoFormat('D MMM Y, HH:mm') }}</span>
                                @if($permohonan->reviewer)
                                    <span class="text-gray-400">oleh</span> <span class="font-medium text-gray-700">{{ $permohonan->reviewer->name }}</span>
                                @endif
                            </div>
                        @endif
                        @if($permohonan->approved_at)
                            <div>
                                <span class="text-gray-400">Persetujuan Super Admin:</span>
                                <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($permohonan->approved_at)->isoFormat('D MMM Y, HH:mm') }}</span>
                                @if($permohonan->approver)
                                    <span class="text-gray-400">oleh</span> <span class="font-medium text-gray-700">{{ $permohonan->approver->name }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Catatan Admin --}}
            @if($permohonan->catatan_admin)
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 card-anim">
                <h2 class="text-sm font-bold text-gray-700 mb-2">Catatan Admin</h2>
                <p class="text-xs text-gray-600 leading-relaxed italic">"{{ $permohonan->catatan_admin }}"</p>
            </div>
            @endif

            {{-- ── Action Panel (Hierarki Kurasi Admin) ──────────────────────────────── --}}
            @if(in_array($st->value, ['submitted', 'reviewed_by_admin']))
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 card-anim">
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    <h2 class="text-sm font-bold text-gray-800">Tinjauan Kelayakan Berkas</h2>
                </div>
                <p class="text-[11px] text-gray-500 mb-4 leading-relaxed">
                    Sesuai hierarki otoritas, Admin berwenang memverifikasi kelayakan berkas pendaftar. Keputusan akhir untuk menerima pendaftar menjadi <strong>Mahasiswa Magang</strong> berada pada <strong>Super Admin</strong>.
                </p>

                {{-- Tinjau (submitted → reviewed_by_admin) --}}
                @if($st->value === 'submitted')
                <form method="POST" action="{{ route('admin.permohonan.update-status', $permohonan->id) }}" class="space-y-2">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="reviewed_by_admin">
                    <textarea name="catatan_admin"
                              placeholder="Catatan tinjauan kelayakan (opsional)…"
                              rows="2"
                              class="w-full px-3 py-2 text-xs border border-gray-200 rounded-xl resize-none
                                     focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"></textarea>
                    <button type="submit"
                            class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold
                                   transition shadow-sm flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Nyatakan Layak &amp; Teruskan ke Super Admin</span>
                    </button>
                </form>
                @endif

                {{-- Sudah Ditinjau Admin, Menunggu Super Admin --}}
                @if($st->value === 'reviewed_by_admin')
                <div class="p-3.5 rounded-xl bg-blue-50 border border-blue-200/80 mb-3 space-y-1.5 text-xs text-blue-900">
                    <div class="font-bold flex items-center gap-1.5 text-blue-800">
                        <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Menunggu Keputusan Super Admin</span>
                    </div>
                    <p class="text-[11px] text-blue-700 leading-relaxed">
                        Berkas telah ditinjau dan dinyatakan <strong>LAYAK</strong> oleh Admin. Berkas ini kini berada di antrean finalisasi Super Admin untuk persetujuan resmi.
                    </p>
                </div>
                @endif

                {{-- Tolak (Tersedia bagi Admin jika berkas tidak memenuhi syarat) --}}
                <div class="mt-3 pt-3 border-t border-gray-100" x-data="{ showReject: false }">
                    <button type="button" @click="showReject = !showReject"
                            class="w-full py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold
                                   transition border border-rose-200">
                        Nyatakan Tidak Layak / Tolak
                    </button>
                    <form method="POST" action="{{ route('admin.permohonan.update-status', $permohonan->id) }}"
                          x-show="showReject" x-transition class="mt-2 space-y-2">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <textarea name="catatan_admin"
                                  placeholder="Alasan berkas dinyatakan tidak layak (wajib)…"
                                  rows="2" required
                                  class="w-full px-3 py-2 text-xs border border-rose-200 rounded-xl resize-none
                                         focus:outline-none focus:ring-2 focus:ring-rose-400 transition"></textarea>
                        <button type="submit"
                                class="w-full py-2.5 rounded-xl bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold
                                       transition shadow-sm">
                            Konfirmasi Tolak (Tidak Layak)
                        </button>
                    </form>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
