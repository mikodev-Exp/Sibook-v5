@extends('layouts.role', ['role' => 'superuser'])

@section('title', 'Master Console - Super User')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    {{-- Top Header Placard --}}
    <div class="bg-white rounded-3xl border border-zinc-200/80 p-8 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-mono-tag text-zinc-400 uppercase tracking-widest mb-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>MASTER CONSOLE // ROOT PRIVILEGES</span>
            </div>
            <h1 class="text-3xl font-extrabold text-zinc-950 tracking-tight">Pusat Kendali Sistem</h1>
            <p class="text-xs text-zinc-500 mt-1 max-w-xl leading-relaxed">
                Akses tingkat tinggi untuk manajemen otoritas, audit berkas permohonan final, dan kendali konfigurasi SiBook BPS Kota Surabaya.
            </p>
        </div>

        <div class="flex items-center gap-3 font-mono-tag text-xs">
            <span class="px-3.5 py-1.5 rounded-xl bg-zinc-100 border border-zinc-200 text-zinc-700">
                AUTH: SUPERUSER
            </span>
        </div>
    </div>

    {{-- 3 KPI Placards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- 1. Menunggu Approval Akhir (Obsidian Hero Card) -->
        <div class="bg-zinc-950 text-white p-8 rounded-3xl border border-zinc-800 shadow-xl relative overflow-hidden flex flex-col justify-between group">
            <div class="absolute -right-16 -top-16 w-48 h-48 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div>
                <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-wider block mb-2">FINAL REVIEW QUEUE</span>
                <p class="text-xs text-zinc-300 font-semibold mb-1">Menunggu Persetujuan Akhir</p>
                <h4 class="text-5xl font-extrabold font-mono-tag text-white tracking-tight mt-3">{{ $menungguApproval }}</h4>
            </div>
            <div class="mt-8 pt-4 border-t border-zinc-800/80 flex items-center justify-between text-xs font-mono-tag">
                <span class="text-zinc-400">Pendaftar lolos kurasi</span>
                @if($menungguApproval > 0)
                    <span class="text-emerald-400 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                        ACTION REQUIRED
                    </span>
                @else
                    <span class="text-zinc-500">CLEAR</span>
                @endif
            </div>
        </div>

        <!-- 2. Total Mahasiswa -->
        <div class="bg-white p-8 rounded-3xl border border-zinc-200/80 shadow-sm flex flex-col justify-between hover:border-zinc-300 transition-colors">
            <div>
                <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-wider block mb-2">ACTIVE ARTISTS</span>
                <p class="text-xs text-zinc-500 font-semibold mb-1">Total Mahasiswa Aktif</p>
                <h4 class="text-5xl font-extrabold font-mono-tag text-zinc-950 tracking-tight mt-3">{{ $totalMahasiswa }}</h4>
            </div>
            <div class="mt-8 pt-4 border-t border-zinc-100 flex items-center justify-between text-xs font-mono-tag text-zinc-400">
                <span>ROLE: MAHASISWA</span>
                <span class="text-zinc-600 font-semibold">VERIFIED</span>
            </div>
        </div>

        <!-- 3. Total Admin -->
        <div class="bg-white p-8 rounded-3xl border border-zinc-200/80 shadow-sm flex flex-col justify-between hover:border-zinc-300 transition-colors">
            <div>
                <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-wider block mb-2">CURATORIAL STAFF</span>
                <p class="text-xs text-zinc-500 font-semibold mb-1">Total Kurator &amp; Admin</p>
                <h4 class="text-5xl font-extrabold font-mono-tag text-zinc-950 tracking-tight mt-3">{{ $totalAdmin }}</h4>
            </div>
            <div class="mt-8 pt-4 border-t border-zinc-100 flex items-center justify-between text-xs font-mono-tag text-zinc-400">
                <span>ROLE: ADMIN</span>
                <span class="text-zinc-600 font-semibold">AUTHORIZED</span>
            </div>
        </div>
    </div>
</div>
@endsection
