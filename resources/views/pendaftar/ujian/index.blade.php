@extends('layouts.role', ['role' => 'pendaftar'])

@section('title', 'Ujian Kompetensi')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    {{-- Top Navigation & Badge --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('pendaftar.dashboard') }}" 
           class="inline-flex items-center gap-2 text-xs font-mono-tag text-zinc-500 hover:text-zinc-950 transition-colors py-1.5 px-3 rounded-lg hover:bg-zinc-100">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>KEMBALI KE TERMINAL</span>
        </a>

        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-zinc-100 border border-zinc-200/80 text-[11px] font-mono-tag text-zinc-600">
            <span>TAHAP SELEKSI // 03</span>
        </div>
    </div>

    {{-- Header Placard --}}
    <div class="bg-white rounded-3xl p-8 border border-zinc-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-mono-tag text-zinc-400 uppercase tracking-widest mb-1.5">
                <span>EVALUATION SUITE</span>
            </div>
            <h1 class="text-3xl font-extrabold text-zinc-950 tracking-tight">Evaluasi Kompetensi</h1>
            <p class="text-xs text-zinc-500 mt-1 max-w-xl leading-relaxed">
                Selesaikan 3 rangkaian uji kualifikasi untuk memverifikasi kapasitas akademik dan kelayakan bergabung ke tim BPS Kota Surabaya.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <div class="p-3 bg-zinc-50 border border-zinc-200/70 rounded-2xl text-center min-w-[100px]">
                <span class="block text-[10px] font-mono-tag text-zinc-400 uppercase">STATUS PASS</span>
                <span class="text-sm font-extrabold text-zinc-900 font-mono-tag">
                    @if($permohonan->status_ujian_esai === 'lulus' && $permohonan->status_ujian_tulis === 'lulus' && $permohonan->status_interview === 'lulus')
                        <span class="text-emerald-600">LULUS</span>
                    @elseif($permohonan->status_ujian_esai === 'gagal' || $permohonan->status_ujian_tulis === 'gagal' || $permohonan->status_interview === 'gagal')
                        <span class="text-rose-600">TIDAK LULUS</span>
                    @else
                        <span class="text-amber-600">PROSES</span>
                    @endif
                </span>
            </div>
        </div>
    </div>

    {{-- Evaluation Cards Grid (Gallery Module Style) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- 1. Ujian Esai -->
        <div class="bg-white rounded-3xl border border-zinc-200/80 shadow-sm overflow-hidden flex flex-col justify-between hover:border-zinc-400 hover:shadow-md transition-all duration-300">
            <div class="p-7 flex-1">
                <div class="flex items-center justify-between mb-5">
                    <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-wider">MODULE #01</span>
                    @if($permohonan->status_ujian_esai === 'lulus')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-mono-tag font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            LULUS
                        </span>
                    @elseif($permohonan->status_ujian_esai === 'gagal')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-mono-tag font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            GAGAL
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-mono-tag font-semibold bg-zinc-100 text-zinc-600 border border-zinc-200">
                            BELUM
                        </span>
                    @endif
                </div>

                <div class="w-12 h-12 bg-zinc-950 text-white rounded-2xl flex items-center justify-center mb-5 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>

                <h3 class="text-lg font-bold text-zinc-950 mb-2 tracking-tight">Ujian Esai</h3>
                <p class="text-xs text-zinc-500 leading-relaxed">
                    Evaluasi nalar kritis, kemampuan analisis masalah kontekstual, dan pemahaman visi pelayanan statistik publik.
                </p>
            </div>

            <div class="p-5 bg-zinc-50 border-t border-zinc-100">
                <a href="{{ $gformEsai }}" target="_blank" 
                   class="inline-flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-zinc-950 text-white text-xs font-semibold rounded-xl hover:bg-zinc-800 transition-all font-mono-tag shadow-xs">
                    <span>BUKA LEMBAR UJIAN</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
        </div>

        <!-- 2. Ujian Tulis -->
        <div class="bg-white rounded-3xl border border-zinc-200/80 shadow-sm overflow-hidden flex flex-col justify-between hover:border-zinc-400 hover:shadow-md transition-all duration-300">
            <div class="p-7 flex-1">
                <div class="flex items-center justify-between mb-5">
                    <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-wider">MODULE #02</span>
                    @if($permohonan->status_ujian_tulis === 'lulus')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-mono-tag font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            LULUS
                        </span>
                    @elseif($permohonan->status_ujian_tulis === 'gagal')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-mono-tag font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            GAGAL
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-mono-tag font-semibold bg-zinc-100 text-zinc-600 border border-zinc-200">
                            BELUM
                        </span>
                    @endif
                </div>

                <div class="w-12 h-12 bg-zinc-950 text-white rounded-2xl flex items-center justify-center mb-5 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>

                <h3 class="text-lg font-bold text-zinc-950 mb-2 tracking-tight">Ujian Tulis</h3>
                <p class="text-xs text-zinc-500 leading-relaxed">
                    Pengukuran komprehensif logika matematika, dasar metodologi statistik, serta pemahaman komputasi dasar.
                </p>
            </div>

            <div class="p-5 bg-zinc-50 border-t border-zinc-100">
                <a href="{{ $gformTulis }}" target="_blank" 
                   class="inline-flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-zinc-950 text-white text-xs font-semibold rounded-xl hover:bg-zinc-800 transition-all font-mono-tag shadow-xs">
                    <span>BUKA LEMBAR UJIAN</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
        </div>

        <!-- 3. Interview -->
        <div class="bg-white rounded-3xl border border-zinc-200/80 shadow-sm overflow-hidden flex flex-col justify-between hover:border-zinc-400 hover:shadow-md transition-all duration-300">
            <div class="p-7 flex-1">
                <div class="flex items-center justify-between mb-5">
                    <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-wider">MODULE #03</span>
                    @if($permohonan->status_interview === 'lulus')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-mono-tag font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            LULUS
                        </span>
                    @elseif($permohonan->status_interview === 'gagal')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-mono-tag font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            GAGAL
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-mono-tag font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                            JADWAL
                        </span>
                    @endif
                </div>

                <div class="w-12 h-12 bg-zinc-950 text-white rounded-2xl flex items-center justify-center mb-5 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>

                <h3 class="text-lg font-bold text-zinc-950 mb-2 tracking-tight">Wawancara Kurasi</h3>
                <p class="text-xs text-zinc-500 leading-relaxed">
                    Sesi wawancara langsung dengan kurator mentor BPS untuk penentuan penempatan divisi dan konfirmasi kesiapan kerja.
                </p>
            </div>

            <div class="p-5 bg-zinc-50 border-t border-zinc-100 text-xs">
                <span class="block text-[10px] font-mono-tag text-zinc-400 uppercase mb-2 font-bold">KONTAK KURATOR (WHATSAPP):</span>
                <div class="space-y-1.5">
                    <a href="https://wa.me/62812345678" target="_blank" 
                       class="flex items-center justify-between p-2 rounded-xl bg-white border border-zinc-200 hover:border-zinc-400 transition-colors font-mono-tag text-[11px] text-zinc-700">
                        <span>Admin BPS #01</span>
                        <span class="text-zinc-400">Hubungi &rarr;</span>
                    </a>
                    <a href="https://wa.me/62812345679" target="_blank" 
                       class="flex items-center justify-between p-2 rounded-xl bg-white border border-zinc-200 hover:border-zinc-400 transition-colors font-mono-tag text-[11px] text-zinc-700">
                        <span>Admin BPS #02</span>
                        <span class="text-zinc-400">Hubungi &rarr;</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
