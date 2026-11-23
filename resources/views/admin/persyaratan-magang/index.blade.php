@extends('layouts.role', ['role' => 'admin'])

@section('title', 'Requirement & Kuota Magang')

@section('content')
<div x-data="requirementAdmin()" x-init="init()" class="space-y-6 pb-16">

    {{-- ── 1. Page Header (dengan Logo BPS) ──────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-150 p-6 shadow-[0_1px_3px_rgba(0,0,0,0.02)] flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-gray-50 border border-gray-100 rounded-2xl p-2 flex items-center justify-center shrink-0 shadow-sm">
                <x-bps-logo class="h-10 w-auto object-contain" />
            </div>
            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Requirement & Kuota Magang</h1>
                    @if($setting->status === 'open')
                        <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Dibuka
                        </span>
                    @elseif($setting->status === 'soon')
                        <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            Segera Dibuka
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            Ditutup
                        </span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    Kelola syarat pendaftaran, tautan internal SPS, dan alokasi kuota per jurusan untuk ditampilkan di landing page.
                    <span class="text-gray-400">· Diperbarui {{ $lastUpdated->diffForHumans() }}</span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 self-start md:self-center flex-wrap">
            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition">
                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
            <a href="{{ route('home') }}#kuota-magang" target="_blank"
               class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition">
                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Lihat di Landing Page
            </a>
        </div>
    </div>

    {{-- ── 2. Ringkasan Kuota Global & Status ──────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card Total Kuota --}}
        <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
            <p class="text-xs font-medium text-gray-500">Total Kuota Disediakan</p>
            <div class="flex items-baseline justify-between mt-2">
                <p class="text-3xl font-extrabold text-gray-900">{{ $totalQuota }}</p>
                <span class="text-xs text-gray-400 font-medium">{{ $groups->count() }} kelompok</span>
            </div>
            <div class="w-full bg-gray-100 h-1.5 rounded-full mt-3 overflow-hidden">
                <div class="bg-indigo-600 h-full rounded-full" style="width: 100%"></div>
            </div>
        </div>

        {{-- Card Mahasiswa Aktif --}}
        <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
            <p class="text-xs font-medium text-gray-500">Mahasiswa Magang Aktif</p>
            <div class="flex items-baseline justify-between mt-2">
                <p class="text-3xl font-extrabold text-orange-600">{{ $totalActive }}</p>
                <span class="text-xs text-orange-600 font-semibold">
                    {{ $totalQuota > 0 ? round(($totalActive / $totalQuota) * 100) : 0 }}% terisi
                </span>
            </div>
            <div class="w-full bg-gray-100 h-1.5 rounded-full mt-3 overflow-hidden">
                <div class="bg-orange-500 h-full rounded-full transition-all duration-300"
                     style="width: {{ $totalQuota > 0 ? min(100, round(($totalActive / $totalQuota) * 100)) : 0 }}%"></div>
            </div>
        </div>

        {{-- Card Sisa Slot --}}
        <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
            <p class="text-xs font-medium text-gray-500">Sisa Slot Tersedia</p>
            <div class="flex items-baseline justify-between mt-2">
                <p class="text-3xl font-extrabold text-emerald-600">{{ $totalRemaining }}</p>
                <span class="text-xs {{ $totalRemaining > 0 ? 'text-emerald-600' : 'text-rose-600' }} font-semibold">
                    {{ $totalRemaining > 0 ? 'Tersedia' : 'Penuh' }}
                </span>
            </div>
            <div class="w-full bg-gray-100 h-1.5 rounded-full mt-3 overflow-hidden">
                <div class="bg-emerald-500 h-full rounded-full transition-all duration-300"
                     style="width: {{ $totalQuota > 0 ? min(100, round(($totalRemaining / $totalQuota) * 100)) : 0 }}%"></div>
            </div>
        </div>

        {{-- Card Status Registrasi --}}
        <div class="bg-white rounded-2xl border border-gray-150 p-5 shadow-[0_1px_3px_rgba(0,0,0,0.02)] flex flex-col justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500">Status Pendaftaran</p>
                <p class="text-lg font-bold text-gray-900 mt-1 capitalize">{{ $setting->status_label }}</p>
                <p class="text-[11px] text-gray-400 truncate mt-0.5">{{ $setting->status_note ?: 'Tidak ada catatan status.' }}</p>
            </div>
            <button @click="openSettingsModal()" class="mt-3 text-xs font-semibold text-[#E06D28] hover:text-[#c55d1f] inline-flex items-center gap-1">
                Ubah Status & Syarat
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>

    {{-- ── 3. Grid 2 Kolom: Persyaratan Magang & Link SPS ────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kartu Persyaratan (2 Col) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-150 p-6 shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Persyaratan Magang</h2>
                        <p class="text-xs text-gray-400">Daftar persyaratan yang tampil di landing page untuk calon pendaftar.</p>
                    </div>
                </div>
                <button @click="openSettingsModal()"
                        class="px-3.5 py-1.5 rounded-xl bg-[#FEF6EE] hover:bg-[#fedec5] text-[#E06D28] text-xs font-semibold transition border border-orange-200 inline-flex items-center gap-1.5 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Edit Persyaratan
                </button>
            </div>

            {{-- List Poin Persyaratan --}}
            <div class="mt-4 space-y-2.5">
                @forelse($setting->requirements ?? [] as $index => $req)
                    <div class="flex items-start gap-3 p-3 rounded-xl bg-gray-50/70 border border-gray-100 text-xs text-gray-700">
                        <span class="w-5 h-5 rounded-md bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center shrink-0 text-[10.5px]">
                            {{ $index + 1 }}
                        </span>
                        <span class="flex-1 leading-relaxed">{{ $req }}</span>
                    </div>
                @empty
                    <div class="py-8 text-center text-gray-400 text-xs">
                        Belum ada poin persyaratan yang diatur.
                    </div>
                @endforelse
            </div>

            {{-- Catatan Persyaratan --}}
            @if($setting->requirements_note)
                <div class="mt-4 p-3.5 rounded-xl bg-amber-50/80 border border-amber-200/70 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="text-xs text-amber-800 leading-relaxed">
                        <span class="font-semibold">Catatan:</span> {{ $setting->requirements_note }}
                    </div>
                </div>
            @endif
        </div>

        {{-- Kartu Link SPS (1 Col - Hanya Panel Admin) --}}
        <div class="bg-white rounded-2xl border border-gray-150 p-6 shadow-[0_1px_3px_rgba(0,0,0,0.02)] flex flex-col">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-50 border border-orange-100 text-[#E06D28] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-base font-bold text-gray-900">Tautan SPS</h2>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600 uppercase">Internal</span>
                        </div>
                        <p class="text-[11px] text-gray-400">Hanya untuk panel admin.</p>
                    </div>
                </div>
                <button @click="openLinksModal()"
                        class="p-2 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition" title="Kelola Link">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </button>
            </div>

            <div class="mt-4 space-y-2.5 flex-1">
                @forelse($setting->links ?? [] as $link)
                    <div class="p-3 rounded-xl border border-gray-100 bg-gray-50/60 hover:bg-gray-50 transition group">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-xs font-semibold text-gray-800 truncate">{{ $link['label'] ?? 'Tautan' }}</p>
                            <div class="flex items-center gap-1.5 shrink-0">
                                {{-- Tombol Salin --}}
                                <button type="button"
                                        @click="copyToClipboard('{{ $link['url'] ?? '' }}', $el)"
                                        class="px-2 py-1 text-[11px] font-medium text-gray-600 hover:text-gray-900 hover:bg-white rounded-lg border border-gray-200 transition inline-flex items-center gap-1">
                                    <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                    <span>Salin</span>
                                </button>
                                {{-- Tombol Buka Tab Baru --}}
                                <a href="{{ $link['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer"
                                   class="px-2 py-1 text-[11px] font-medium text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg border border-indigo-200 transition inline-flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    Buka
                                </a>
                            </div>
                        </div>
                        <p class="text-[11px] text-gray-400 truncate mt-1 select-all font-mono">{{ $link['url'] ?? '' }}</p>
                    </div>
                @empty
                    <div class="py-8 text-center text-gray-400 text-xs">
                        Belum ada link SPS yang disimpan.
                    </div>
                @endforelse
            </div>

            <button @click="openLinksModal()"
                    class="mt-4 w-full py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-semibold rounded-xl border border-dashed border-gray-300 transition flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Kelola Link SPS
            </button>
        </div>
    </div>

    {{-- ── 4. Kelompok Kuota Magang & Jurusan (Drag & Drop) ───────── --}}
    <div class="bg-white rounded-2xl border border-gray-150 p-6 shadow-[0_1px_3px_rgba(0,0,0,0.02)] space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-gray-100">
            <div>
                <h2 class="text-base font-bold text-gray-900">Alokasi Kuota Berdasarkan Kelompok Jurusan</h2>
                <p class="text-xs text-gray-400 mt-0.5">
                    Seret (drag) chip jurusan antar kelompok untuk mengatur distribusinya. Di HP/layar sentuh, cukup ketuk chip jurusan untuk memindahkan.
                </p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <button @click="openMajorModal()"
                        class="px-3.5 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-semibold rounded-xl border border-gray-200 transition inline-flex items-center gap-1.5 shadow-sm">
                    <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Jurusan
                </button>
                <button @click="openGroupModal()"
                        class="px-3.5 py-2 bg-[#E06D28] hover:bg-[#c55d1f] text-white text-xs font-semibold rounded-xl transition inline-flex items-center gap-1.5 shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Kelompok
                </button>
            </div>
        </div>

        {{-- Grid Kelompok Kuota --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($groups as $group)
                @php
                    $isFull = $group->is_full;
                    $rem = $group->remaining;
                    $statusColor = $group->status_color; // green, yellow, red
                    $badgeStyle = match($statusColor) {
                        'red'    => 'bg-rose-50 text-rose-700 border-rose-200',
                        'yellow' => 'bg-amber-50 text-amber-700 border-amber-200',
                        default  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    };
                @endphp
                <div class="bg-gray-50/60 rounded-2xl border border-gray-200/80 p-5 flex flex-col justify-between hover:border-gray-300 transition-all shadow-sm group relative"
                     id="group-card-{{ $group->id }}">

                    <div>
                        {{-- Top Header Kelompok --}}
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-sm font-bold text-gray-900 truncate" title="{{ $group->name }}">{{ $group->name }}</h3>
                                    @if($group->is_catch_all)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            Kelompok "Lainnya"
                                        </span>
                                    @endif
                                </div>
                                <p class="text-[11px] text-gray-400 mt-0.5">
                                    <span id="group-major-count-{{ $group->id }}">{{ $group->majors->count() }}</span> jurusan terdaftar
                                </p>
                            </div>

                            {{-- Menu Aksi Kelompok --}}
                            <div class="flex items-center gap-1 shrink-0">
                                <button @click="editGroup(@js($group))"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-white transition" title="Edit Kelompok">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                <form method="POST" action="{{ route('admin.persyaratan-magang.kelompok.destroy', $group) }}"
                                      onsubmit="return confirm('Hapus kelompok kuota {{ addslashes($group->name) }}? Jurusan di dalamnya akan berstatus Belum Dikelompokkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-rose-600 hover:bg-white transition" title="Hapus Kelompok">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Panel Kuota & Tombol +/- Cepat --}}
                        <div class="mt-4 p-3 rounded-xl bg-white border border-gray-150 shadow-xs flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Sisa Slot</span>
                                <div class="flex items-baseline gap-2 mt-0.5">
                                    <span class="text-xl font-extrabold text-gray-900" id="remaining-val-{{ $group->id }}">{{ $rem }}</span>
                                    <span class="text-xs text-gray-400">/ <span id="quota-val-{{ $group->id }}">{{ $group->quota }}</span> kuota</span>
                                </div>
                            </div>

                            {{-- Tombol +/- Cepat untuk Mahasiswa Aktif --}}
                            <div class="flex items-center gap-1.5">
                                <button type="button"
                                        @click="updateActiveQuick({{ $group->id }}, -1)"
                                        class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold flex items-center justify-center transition text-sm">
                                    -
                                </button>
                                <span class="text-xs font-bold text-gray-800 px-1" id="active-val-{{ $group->id }}" title="Mahasiswa Magang Aktif">
                                    {{ $group->active }}
                                </span>
                                <button type="button"
                                        @click="updateActiveQuick({{ $group->id }}, 1)"
                                        class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold flex items-center justify-center transition text-sm">
                                    +
                                </button>
                                <span class="ml-1 px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $badgeStyle }}" id="badge-status-{{ $group->id }}">
                                    {{ $rem <= 0 ? 'Penuh' : $rem . ' Sisa' }}
                                </span>
                            </div>
                        </div>

                        {{-- Area Drop Jurusan (SortableJS container) --}}
                        <div class="mt-4">
                            <p class="text-[11px] font-semibold text-gray-500 mb-2">Daftar Jurusan:</p>
                            <div class="major-sortable-container min-h-[60px] p-2 bg-white/70 rounded-xl border border-dashed border-gray-200 flex flex-wrap gap-1.5 transition-colors"
                                 data-group-id="{{ $group->id }}">
                                @foreach($group->majors as $major)
                                    <div class="major-chip inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50/70 hover:bg-indigo-100 text-indigo-700 border border-indigo-200/70 text-xs font-medium cursor-grab active:cursor-grabbing transition shadow-2xs select-none"
                                         data-major-id="{{ $major->id }}"
                                         @click="openMoveModal(@js($major))">
                                        <svg class="w-3 h-3 text-indigo-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M7 4a2 2 0 114 0 2 2 0 01-4 0zm0 6a2 2 0 114 0 2 2 0 01-4 0zm0 6a2 2 0 114 0 2 2 0 01-4 0z"/></svg>
                                        <span>{{ $major->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-gray-200/60 flex items-center justify-between text-[11px] text-gray-400">
                        <span>Urutan: {{ $group->sort_order }}</span>
                        <button type="button" @click="editGroup(@js($group))" class="text-[#E06D28] hover:underline font-medium">
                            Ubah Kuota & Jurusan
                        </button>
                    </div>
                </div>
            @empty
                <div class="md:col-span-2 lg:col-span-3 p-8 rounded-2xl bg-gray-50 border border-dashed border-gray-300 text-center">
                    <div class="w-10 h-10 rounded-xl bg-orange-50 text-[#E06D28] mx-auto flex items-center justify-center mb-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <p class="text-xs font-bold text-gray-700">Belum ada kelompok kuota yang dibuat</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">Klik tombol "Tambah Kelompok" di atas untuk membuat kelompok jurusan dan kuotanya.</p>
                </div>
            @endforelse
        </div>

        {{-- ── Area Belum Dikelompokkan (Unassigned Majors) ──────── --}}
        <div class="p-5 rounded-2xl bg-amber-50/40 border border-amber-200/80">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-xs">!</span>
                    <h3 class="text-sm font-bold text-gray-900">Jurusan Belum Dikelompokkan</h3>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-800" id="unassigned-count">
                        {{ $unassignedMajors->count() }}
                    </span>
                </div>
                <p class="text-[11px] text-amber-800">
                    *Jurusan di area ini otomatis tampil di kelompok <strong>"Lainnya"</strong> pada halaman landing.
                </p>
            </div>

            <div class="major-sortable-container min-h-[50px] p-3 bg-white rounded-xl border border-dashed border-amber-300 flex flex-wrap gap-2"
                 data-group-id="unassigned">
                @forelse($unassignedMajors as $major)
                    <div class="major-chip inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-800 border border-gray-300/80 text-xs font-medium cursor-grab active:cursor-grabbing transition shadow-2xs select-none"
                         data-major-id="{{ $major->id }}"
                         @click="openMoveModal(@js($major))">
                        <svg class="w-3 h-3 text-gray-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M7 4a2 2 0 114 0 2 2 0 01-4 0zm0 6a2 2 0 114 0 2 2 0 01-4 0zm0 6a2 2 0 114 0 2 2 0 01-4 0z"/></svg>
                        <span>{{ $major->name }}</span>
                    </div>
                @empty
                    <div class="text-gray-400 text-xs italic py-1 empty-notice">
                        Semua jurusan telah dimasukkan ke dalam kelompok kuota.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── MODAL 1: Edit Persyaratan & Status Magang ──────────────── --}}
    <div x-show="showSettingsModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto"
         @keydown.escape.window="showSettingsModal = false">
        <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[92vh] flex flex-col shadow-2xl my-auto"
             @click.outside="showSettingsModal = false">
            <form method="POST" action="{{ route('admin.persyaratan-magang.pengaturan.update') }}" class="flex flex-col h-full">
                @csrf
                @method('PUT')

                {{-- Modal Header --}}
                <div class="flex items-center justify-between p-5 border-b border-gray-100 shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-900">Edit Persyaratan & Status Magang</h3>
                    </div>
                    <button type="button" @click="showSettingsModal = false" class="text-gray-400 hover:text-gray-600 p-1">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 space-y-5 overflow-y-auto flex-1">
                    {{-- Status Pembukaan --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Status Pembukaan Magang *</label>
                            <select name="status" x-model="settingsForm.status"
                                    class="w-full text-xs rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="open">Dibuka (Open)</option>
                                <option value="soon">Segera Dibuka (Coming Soon)</option>
                                <option value="closed">Ditutup (Closed)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Estimasi Tanggal Buka (Opsional)</label>
                            <input type="date" name="opens_at" x-model="settingsForm.opens_at"
                                   class="w-full text-xs rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>
                    </div>

                    {{-- Keterangan Status --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Keterangan / Banner Catatan Status</label>
                        <input type="text" name="status_note" x-model="settingsForm.status_note"
                               placeholder="Contoh: Pembukaan magang dibuka lagi pada akhir Agustus"
                               class="w-full text-xs rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>

                    {{-- Daftar Poin Persyaratan Dinamis --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-xs font-semibold text-gray-700">Daftar Poin Persyaratan *</label>
                            <button type="button" @click="addRequirementItem()"
                                    class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Tambah Poin
                            </button>
                        </div>

                        <div class="space-y-2">
                            <template x-for="(item, index) in settingsForm.requirements" :key="index">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-lg bg-gray-100 text-gray-600 font-bold flex items-center justify-center shrink-0 text-xs" x-text="index + 1"></span>
                                    <input type="text" name="requirements[]" x-model="settingsForm.requirements[index]"
                                           placeholder="Tuliskan poin persyaratan..." required
                                           class="w-full text-xs rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
                                    {{-- Geser Urutan --}}
                                    <button type="button" @click="moveRequirement(index, -1)" :disabled="index === 0"
                                            class="p-1.5 text-gray-400 hover:text-gray-700 disabled:opacity-30">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                    </button>
                                    <button type="button" @click="moveRequirement(index, 1)" :disabled="index === settingsForm.requirements.length - 1"
                                            class="p-1.5 text-gray-400 hover:text-gray-700 disabled:opacity-30">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    {{-- Hapus Poin --}}
                                    <button type="button" @click="removeRequirementItem(index)"
                                            class="p-1.5 text-rose-400 hover:text-rose-600">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Catatan Persyaratan --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Catatan Tambahan Persyaratan</label>
                        <textarea name="requirements_note" x-model="settingsForm.requirements_note" rows="3"
                                  placeholder="Catatan penutup terkait persyaratan pendaftaran..."
                                  class="w-full text-xs rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="p-4 border-t border-gray-100 flex items-center justify-end gap-2 shrink-0 bg-gray-50/50">
                    <button type="button" @click="showSettingsModal = false"
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-100 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 rounded-xl text-xs font-semibold bg-[#E06D28] hover:bg-[#c55d1f] text-white transition shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── MODAL 2: Kelola Tautan SPS ─────────────────────────────── --}}
    <div x-show="showLinksModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto"
         @keydown.escape.window="showLinksModal = false">
        <div class="bg-white rounded-2xl w-full max-w-xl max-h-[92vh] flex flex-col shadow-2xl my-auto"
             @click.outside="showLinksModal = false">
            <form method="POST" action="{{ route('admin.persyaratan-magang.tautan.update') }}" class="flex flex-col h-full">
                @csrf
                @method('PUT')

                <div class="flex items-center justify-between p-5 border-b border-gray-100 shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-orange-50 text-[#E06D28] flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-900">Kelola Tautan Internal SPS</h3>
                    </div>
                    <button type="button" @click="showLinksModal = false" class="text-gray-400 hover:text-gray-600 p-1">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-6 space-y-4 overflow-y-auto flex-1">
                    <p class="text-xs text-gray-500">
                        Tautan ini hanya dapat diakses oleh Admin untuk kemudahan rujukan kerja dan tidak akan ditampilkan pada landing page publik.
                    </p>

                    <div class="space-y-3">
                        <template x-for="(link, index) in linksForm" :key="index">
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-200/80 space-y-2 relative">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-[11px] font-bold text-gray-600" x-text="'Tautan #' + (index + 1)"></span>
                                    <button type="button" @click="removeLinkItem(index)" class="text-rose-500 hover:text-rose-700 text-xs">
                                        Hapus
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <input type="text" :name="'links['+index+'][label]'" x-model="link.label" placeholder="Nama Label (contoh: Portal SPS)" required
                                           class="text-xs rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
                                    <input type="url" :name="'links['+index+'][url]'" x-model="link.url" placeholder="https://sps.bps.go.id" required
                                           class="text-xs rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
                                </div>
                            </div>
                        </template>
                    </div>

                    <button type="button" @click="addLinkItem()"
                            class="w-full py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-semibold rounded-xl border border-dashed border-gray-300 transition flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Tautan Baru
                    </button>
                </div>

                <div class="p-4 border-t border-gray-100 flex items-center justify-end gap-2 shrink-0 bg-gray-50/50">
                    <button type="button" @click="showLinksModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-semibold bg-[#E06D28] hover:bg-[#c55d1f] text-white transition shadow-sm">Simpan Tautan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── MODAL 3: Tambah / Edit Kelompok Kuota (Dengan Panel Live Preview Landing) --}}
    <div x-show="showGroupModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto"
         @keydown.escape.window="showGroupModal = false">
        <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[94vh] flex flex-col shadow-2xl my-auto"
             @click.outside="showGroupModal = false">
            <form :method="'POST'" :action="groupForm.id ? '{{ url('admin/persyaratan-magang/kelompok') }}/' + groupForm.id : '{{ route('admin.persyaratan-magang.kelompok.store') }}'" class="flex flex-col h-full">
                @csrf
                <template x-if="groupForm.id">
                    <input type="hidden" name="_method" value="PUT" />
                </template>

                <div class="flex items-center justify-between p-5 border-b border-gray-100 shrink-0">
                    <h3 class="text-base font-bold text-gray-900" x-text="groupForm.id ? 'Edit Kelompok Kuota' : 'Tambah Kelompok Kuota Baru'"></h3>
                    <button type="button" @click="showGroupModal = false" class="text-gray-400 hover:text-gray-600 p-1">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6 overflow-y-auto flex-1">
                    {{-- Kolom Kiri: Form Input --}}
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Kelompok *</label>
                            <input type="text" name="name" x-model="groupForm.name" required
                                   placeholder="Contoh: Sains, Teknologi, Teknik & Matematika"
                                   class="w-full text-xs rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Total Kuota *</label>
                                <input type="number" name="quota" x-model.number="groupForm.quota" min="0" required
                                       class="w-full text-xs rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Magang Aktif *</label>
                                <input type="number" name="active" x-model.number="groupForm.active" min="0" :max="groupForm.quota" required
                                       class="w-full text-xs rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
                                <span x-show="groupForm.active > groupForm.quota" class="text-[10.5px] text-rose-500 font-semibold block mt-1">
                                    Aktif tidak boleh melebihi kuota!
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Urutan Tampil</label>
                                <input type="number" name="sort_order" x-model.number="groupForm.sort_order"
                                       class="w-full text-xs rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>
                            <div class="flex items-center pt-5">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="is_catch_all" value="1" x-model="groupForm.is_catch_all"
                                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                    <span class="text-xs text-gray-700 font-medium">Jadikan kelompok "Lainnya"</span>
                                </label>
                            </div>
                        </div>

                        {{-- Pemilihan Jurusan --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Pilih Jurusan yang Masuk ke Kelompok Ini:</label>
                            <div class="max-h-48 overflow-y-auto p-3 bg-gray-50 rounded-xl border border-gray-200 space-y-1.5">
                                @foreach($allMajors as $m)
                                    <label class="flex items-center gap-2 text-xs text-gray-700 cursor-pointer hover:bg-white p-1 rounded-lg transition">
                                        <input type="checkbox" name="major_ids[]" value="{{ $m->id }}"
                                               x-model="groupForm.major_ids"
                                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                        <span>{{ $m->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Kolom Kanan: LIVE PREVIEW KARTU LANDING --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Live Preview Tampilan Landing</span>
                            <span class="text-[11px] text-indigo-600 font-semibold">Bento Card Style</span>
                        </div>

                        {{-- Tampilan Kartu Mirip Landing Page (Indigo Bento Card) --}}
                        <div class="bg-white rounded-3xl border border-indigo-100 p-6 shadow-lg shadow-indigo-100/50 flex flex-col justify-between min-h-[300px]">
                            <div>
                                <div class="flex items-center justify-between gap-3 mb-3">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        Kelompok Jurusan
                                    </span>
                                    <div class="text-right">
                                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-full"
                                              :class="getPreviewStatusClass()">
                                            <span x-text="getPreviewStatusText()"></span>
                                        </span>
                                    </div>
                                </div>

                                <h4 class="text-lg font-extrabold text-gray-900 tracking-tight leading-snug"
                                    x-text="groupForm.name || 'Nama Kelompok Jurusan'"></h4>

                                {{-- Angka Sisa Kuota Besar --}}
                                <div class="mt-4 flex items-baseline gap-2">
                                    <span class="text-4xl font-extrabold text-gray-900"
                                          x-text="Math.max(0, (groupForm.quota || 0) - (groupForm.active || 0))"></span>
                                    <span class="text-xs text-gray-500">slot tersisa dari <span class="font-bold text-gray-800" x-text="groupForm.quota || 0"></span> kuota</span>
                                </div>

                                {{-- Progress Ring / Bar --}}
                                <div class="w-full bg-gray-100 h-2.5 rounded-full mt-3 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-300"
                                         :class="getPreviewBarColor()"
                                         :style="'width: ' + getPreviewProgress() + '%'"></div>
                                </div>

                                {{-- Chip Jurusan Terpilih --}}
                                <div class="mt-4 pt-3 border-t border-gray-100">
                                    <p class="text-[11px] font-semibold text-gray-400 mb-1.5">Jurusan tercakup:</p>
                                    <div class="flex flex-wrap gap-1.5 max-h-24 overflow-y-auto">
                                        <template x-for="mid in groupForm.major_ids" :key="mid">
                                            <span class="px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 text-[11px] font-medium border border-indigo-100"
                                                  x-text="getMajorNameById(mid)"></span>
                                        </template>
                                        <span x-show="groupForm.major_ids.length === 0" class="text-xs text-gray-400 italic">Belum ada jurusan dipilih.</span>
                                    </div>
                                </div>
                            </div>

                            <p class="text-[10px] text-gray-400 mt-4 text-center">Tampilan di landing page publik BPS Kota Surabaya</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 border-t border-gray-100 flex items-center justify-end gap-2 shrink-0 bg-gray-50/50">
                    <button type="button" @click="showGroupModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-100">Batal</button>
                    <button type="submit" :disabled="groupForm.active > groupForm.quota"
                            class="px-5 py-2 rounded-xl text-xs font-semibold bg-[#E06D28] hover:bg-[#c55d1f] disabled:opacity-50 text-white transition shadow-sm">
                        Simpan Kelompok
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── MODAL 4: Tambah Jurusan Baru ───────────────────────────── --}}
    <div x-show="showMajorModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto"
         @keydown.escape.window="showMajorModal = false">
        <div class="bg-white rounded-2xl w-full max-w-md flex flex-col shadow-2xl my-auto"
             @click.outside="showMajorModal = false">
            <form method="POST" action="{{ route('admin.persyaratan-magang.jurusan.store') }}">
                @csrf
                <div class="flex items-center justify-between p-5 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900">Tambah Jurusan Baru</h3>
                    <button type="button" @click="showMajorModal = false" class="text-gray-400 hover:text-gray-600 p-1">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Jurusan / Program Studi *</label>
                        <input type="text" name="name" required placeholder="Contoh: Sains Data, Ilmu Komunikasi"
                               class="w-full text-xs rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Masukkan ke Kelompok (Opsional)</label>
                        <select name="quota_group_id" class="w-full text-xs rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Belum Dikelompokkan --</option>
                            @foreach($groups as $g)
                                <option value="{{ $g->id }}">{{ $g->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="p-4 border-t border-gray-100 flex items-center justify-end gap-2 bg-gray-50/50">
                    <button type="button" @click="showMajorModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-semibold bg-[#E06D28] hover:bg-[#c55d1f] text-white transition shadow-sm">Simpan Jurusan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── MODAL 5: Mobile Tap Picker: Pindah ke Kelompok... ──────── --}}
    <div x-show="showMoveModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto"
         @keydown.escape.window="showMoveModal = false">
        <div class="bg-white rounded-2xl w-full max-w-sm flex flex-col shadow-2xl my-auto"
             @click.outside="showMoveModal = false">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Pindahkan Jurusan</h3>
                    <p class="text-xs text-indigo-600 font-semibold truncate" x-text="selectedMajor?.name"></p>
                </div>
                <button type="button" @click="showMoveModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-5 space-y-2">
                <p class="text-xs font-semibold text-gray-600 mb-2">Pilih Kelompok Tujuan:</p>
                <template x-for="grp in groupsList" :key="grp.id">
                    <button type="button"
                            @click="moveMajorToGroup(selectedMajor.id, grp.id)"
                            class="w-full text-left px-3.5 py-2.5 rounded-xl text-xs font-medium border border-gray-200 hover:border-indigo-400 hover:bg-indigo-50/50 flex items-center justify-between transition">
                        <span x-text="grp.name"></span>
                        <span class="text-[10px] text-gray-400" x-text="grp.is_catch_all ? 'Kelompok Lainnya' : ''"></span>
                    </button>
                </template>
                <button type="button"
                        @click="moveMajorToGroup(selectedMajor.id, null)"
                        class="w-full text-left px-3.5 py-2.5 rounded-xl text-xs font-medium border border-dashed border-amber-300 hover:bg-amber-50 text-amber-900 transition">
                    Pindahkan ke "Belum Dikelompokkan"
                </button>
            </div>
        </div>
    </div>

</div>

{{-- SortableJS CDN untuk Drag & Drop Chip antar Kelompok --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
function requirementAdmin() {
    return {
        showSettingsModal: false,
        showLinksModal: false,
        showGroupModal: false,
        showMajorModal: false,
        showMoveModal: false,

        selectedMajor: null,

        settingsForm: {
            status: @js($setting->status),
            status_note: @js($setting->status_note),
            opens_at: @js($setting->opens_at?->format('Y-m-d')),
            requirements: @js($setting->requirements ?? []),
            requirements_note: @js($setting->requirements_note),
        },

        linksForm: @js($setting->links ?? []),

        groupForm: {
            id: null,
            name: '',
            quota: 0,
            active: 0,
            sort_order: 0,
            is_catch_all: false,
            major_ids: [],
        },

        allMajorsList: @js($allMajors),
        groupsList: @js($groups),

        init() {
            this.initSortable();
        },

        initSortable() {
            if (typeof Sortable === 'undefined') return;

            const containers = document.querySelectorAll('.major-sortable-container');
            containers.forEach(container => {
                new Sortable(container, {
                    group: 'majors-group',
                    animation: 150,
                    ghostClass: 'opacity-40',
                    onEnd: (evt) => {
                        const majorId = evt.item.getAttribute('data-major-id');
                        const targetGroupId = evt.to.getAttribute('data-group-id');
                        this.persistMove(majorId, targetGroupId);
                    }
                });
            });
        },

        persistMove(majorId, targetGroupId) {
            fetch('{{ route('admin.persyaratan-magang.jurusan.pindah') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    major_id: majorId,
                    target_group_id: targetGroupId
                })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.ok) {
                    alert(data.message || 'Gagal memindahkan jurusan.');
                    location.reload();
                }
            })
            .catch(() => {
                alert('Terjadi kesalahan jaringan.');
                location.reload();
            });
        },

        openSettingsModal() {
            this.showSettingsModal = true;
        },

        addRequirementItem() {
            this.settingsForm.requirements.push('');
        },

        removeRequirementItem(index) {
            this.settingsForm.requirements.splice(index, 1);
        },

        moveRequirement(index, direction) {
            const target = index + direction;
            if (target < 0 || target >= this.settingsForm.requirements.length) return;
            const temp = this.settingsForm.requirements[index];
            this.settingsForm.requirements[index] = this.settingsForm.requirements[target];
            this.settingsForm.requirements[target] = temp;
        },

        openLinksModal() {
            this.showLinksModal = true;
        },

        addLinkItem() {
            this.linksForm.push({ label: '', url: '' });
        },

        removeLinkItem(index) {
            this.linksForm.splice(index, 1);
        },

        copyToClipboard(text, btnElement) {
            if (!text) return;
            navigator.clipboard.writeText(text).then(() => {
                const span = btnElement.querySelector('span');
                const oldText = span.innerText;
                span.innerText = 'Disalin!';
                btnElement.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
                setTimeout(() => {
                    span.innerText = oldText;
                    btnElement.classList.remove('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
                }, 1500);
            });
        },

        openGroupModal() {
            this.groupForm = {
                id: null,
                name: '',
                quota: 10,
                active: 0,
                sort_order: this.groupsList.length + 1,
                is_catch_all: false,
                major_ids: [],
            };
            this.showGroupModal = true;
        },

        editGroup(grp) {
            this.groupForm = {
                id: grp.id,
                name: grp.name,
                quota: grp.quota,
                active: grp.active,
                sort_order: grp.sort_order,
                is_catch_all: Boolean(grp.is_catch_all),
                major_ids: grp.majors ? grp.majors.map(m => m.id) : [],
            };
            this.showGroupModal = true;
        },

        openMajorModal() {
            this.showMajorModal = true;
        },

        openMoveModal(major) {
            // Dipanggil saat klik chip (terutama untuk mobile)
            this.selectedMajor = major;
            this.showMoveModal = true;
        },

        moveMajorToGroup(majorId, targetGroupId) {
            this.showMoveModal = false;
            this.persistMove(majorId, targetGroupId);
            setTimeout(() => location.reload(), 400);
        },

        updateActiveQuick(groupId, delta) {
            fetch(`{{ url('admin/persyaratan-magang/kelompok') }}/${groupId}/aktif`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ delta: delta })
            })
            .then(res => res.json())
            .then(data => {
                if (data.ok) {
                    const activeEl = document.getElementById(`active-val-${groupId}`);
                    const remEl = document.getElementById(`remaining-val-${groupId}`);
                    const badgeEl = document.getElementById(`badge-status-${groupId}`);

                    if (activeEl) activeEl.innerText = data.active;
                    if (remEl) remEl.innerText = data.remaining;
                    if (badgeEl) {
                        badgeEl.innerText = data.remaining <= 0 ? 'Penuh' : data.remaining + ' Sisa';
                        badgeEl.className = 'ml-1 px-2 py-0.5 rounded-full text-[10px] font-bold border ' +
                            (data.status_color === 'red' ? 'bg-rose-50 text-rose-700 border-rose-200' :
                            (data.status_color === 'yellow' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200'));
                    }
                } else {
                    alert(data.message || 'Gagal memperbarui kuota.');
                }
            })
            .catch(() => alert('Terjadi kesalahan jaringan.'));
        },

        getMajorNameById(id) {
            const found = this.allMajorsList.find(m => m.id == id);
            return found ? found.name : 'Jurusan #' + id;
        },

        getPreviewProgress() {
            if (!this.groupForm.quota || this.groupForm.quota <= 0) return 0;
            return Math.min(100, Math.round(((this.groupForm.active || 0) / this.groupForm.quota) * 100));
        },

        getPreviewBarColor() {
            const rem = Math.max(0, (this.groupForm.quota || 0) - (this.groupForm.active || 0));
            if (rem <= 0) return 'bg-rose-500';
            const pct = (rem / (this.groupForm.quota || 1)) * 100;
            if (pct <= 30) return 'bg-amber-500';
            return 'bg-emerald-500';
        },

        getPreviewStatusText() {
            const rem = Math.max(0, (this.groupForm.quota || 0) - (this.groupForm.active || 0));
            if (rem <= 0) return 'Penuh';
            const pct = (rem / (this.groupForm.quota || 1)) * 100;
            if (pct <= 30) return 'Tersisa ' + rem + ' (Terbatas)';
            return 'Tersedia (' + rem + ')';
        },

        getPreviewStatusClass() {
            const rem = Math.max(0, (this.groupForm.quota || 0) - (this.groupForm.active || 0));
            if (rem <= 0) return 'bg-rose-50 text-rose-700 border border-rose-200';
            const pct = (rem / (this.groupForm.quota || 1)) * 100;
            if (pct <= 30) return 'bg-amber-50 text-amber-700 border border-amber-200';
            return 'bg-emerald-50 text-emerald-700 border border-emerald-200';
        }
    };
}
</script>
@endsection
