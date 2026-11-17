@extends('layouts.role', ['role' => 'admin'])

@section('title', 'Permohonan Magang')

@push('styles')
<style>
    @keyframes fadeSlideIn {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .row-appear { animation: fadeSlideIn .25s ease both; }
    .modal-backdrop { backdrop-filter: blur(4px); }
</style>
@endpush

@section('content')
<div class="max-w-[1500px] mx-auto space-y-5"
     x-data="permohonanAdmin()"
     x-init="init()">

    {{-- ── Breadcrumbs ───────────────────────────────────── --}}
    <nav class="flex items-center gap-1.5 text-xs text-gray-400 font-medium">
        <span class="text-gray-700 font-semibold">Permohonan Magang</span>
        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span>Daftar Pengajuan</span>
    </nav>

    {{-- ── Header + Stats ────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl lg:text-3xl font-extrabold text-gray-900 tracking-tight">Permohonan Magang</h1>
            <p class="text-sm text-gray-500 mt-0.5">Verifikasi berkas pengajuan calon peserta magang</p>
        </div>
        {{-- Stat chips --}}
        <div class="flex flex-wrap gap-2">
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gray-100 text-xs font-semibold text-gray-600">
                <span class="w-5 h-5 flex items-center justify-center rounded-full bg-white text-gray-800 font-bold shadow-sm">{{ $stats['total'] }}</span> Total
            </div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 text-xs font-semibold text-amber-700 border border-amber-200">
                <span class="w-5 h-5 flex items-center justify-center rounded-full bg-amber-100 font-bold">{{ $stats['pending'] }}</span> Menunggu
            </div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 text-xs font-semibold text-blue-700 border border-blue-200">
                <span class="w-5 h-5 flex items-center justify-center rounded-full bg-blue-100 font-bold">{{ $stats['reviewed'] }}</span> Ditinjau
            </div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-xs font-semibold text-emerald-700 border border-emerald-200">
                <span class="w-5 h-5 flex items-center justify-center rounded-full bg-emerald-100 font-bold">{{ $stats['approved'] }}</span> Disetujui
            </div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-50 text-xs font-semibold text-rose-700 border border-rose-200">
                <span class="w-5 h-5 flex items-center justify-center rounded-full bg-rose-100 font-bold">{{ $stats['rejected'] }}</span> Ditolak
            </div>
        </div>
    </div>

    {{-- Flash message --}}
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

    {{-- ── Main Card ──────────────────────────────────────── --}}
    <div id="permohonan-table-container"
         data-async-container
         @click="SiBookAsync.handleClick($event, 'permohonan-table-container')"
         class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col min-h-[580px] lg:min-h-[calc(100vh-220px)]">

        {{-- Toolbar --}}
        <div class="p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 border-b border-gray-100">
            {{-- Status Filter Pills --}}
            <div class="flex items-center gap-1.5 flex-wrap">
                @php $curStatus = request('status'); @endphp
                @foreach([
                    [null,                 'Semua',              'gray'],
                    ['submitted',          'Menunggu',           'amber'],
                    ['reviewed_by_admin',  'Ditinjau Admin',     'blue'],
                    ['approved',           'Disetujui',          'emerald'],
                    ['rejected',           'Ditolak',            'rose'],
                ] as [$val, $label, $color])
                    @php
                        $isActive = $curStatus === $val;
                        $href = $val
                            ? route('admin.permohonan.index', array_merge(request()->except('page'), ['status' => $val]))
                            : route('admin.permohonan.index', request()->except(['status','page']));
                        $activeClass = match($color) {
                            'amber'   => 'bg-amber-50 text-amber-700 border border-amber-300',
                            'blue'    => 'bg-blue-50 text-blue-700 border border-blue-300',
                            'emerald' => 'bg-emerald-50 text-emerald-700 border border-emerald-300',
                            'rose'    => 'bg-rose-50 text-rose-700 border border-rose-300',
                            default   => 'bg-orange-50 text-orange-700 border border-orange-300',
                        };
                    @endphp
                    <a href="{{ $href }}"
                       class="px-3 py-1 rounded-lg text-xs font-semibold transition-all duration-150
                              {{ $isActive ? $activeClass : 'text-gray-500 hover:text-gray-800 hover:bg-gray-100 border border-transparent' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- Search --}}
            <form method="GET" action="{{ route('admin.permohonan.index') }}"
                  @submit.prevent="SiBookAsync.handleFormSubmit($event, 'permohonan-table-container')"
                  class="relative w-full sm:w-72">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       @input.debounce.350ms="SiBookAsync.handleSearch($event, 'permohonan-table-container')"
                       placeholder="Cari nama, NIM, universitas…"
                       class="w-full pl-9 pr-3 py-1.5 text-xs bg-white border border-gray-200 rounded-lg
                              text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2
                              focus:ring-orange-400 focus:border-orange-400 transition shadow-sm">
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left border-collapse text-[12.5px]">
                <thead>
                    <tr class="border-b border-gray-100 font-semibold text-gray-500 bg-gray-50/70 uppercase tracking-wider text-[11px]">
                        <th class="py-3 pl-5 pr-3">Pemohon</th>
                        <th class="py-3 px-3">Institusi</th>
                        <th class="py-3 px-3">Periode Rencana</th>
                        <th class="py-3 px-3">Berkas</th>
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-3">Catatan Admin</th>
                        <th class="py-3 pl-3 pr-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($permohonans as $p)
                        @php
                            $user    = $p->user;
                            $profile = $user?->mahasiswaProfile;
                            $st      = $p->status instanceof \App\Enums\StatusPermohonan
                                        ? $p->status
                                        : \App\Enums\StatusPermohonan::from($p->status);
                            $badgeClass = match($st) {
                                \App\Enums\StatusPermohonan::Approved        => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                \App\Enums\StatusPermohonan::ReviewedByAdmin => 'bg-blue-50 text-blue-700 border border-blue-200',
                                \App\Enums\StatusPermohonan::Rejected        => 'bg-rose-50 text-rose-700 border border-rose-200',
                                \App\Enums\StatusPermohonan::Submitted       => 'bg-amber-50 text-amber-700 border border-amber-200',
                                \App\Enums\StatusPermohonan::Draft           => 'bg-gray-100 text-gray-500 border border-gray-200',
                                \App\Enums\StatusPermohonan::Cancelled       => 'bg-gray-100 text-gray-400 border border-gray-200',
                            };
                            $dokCount = $p->dokumens?->count() ?? 0;
                        @endphp
                        <tr class="hover:bg-gray-50/60 transition-colors row-appear group">
                            {{-- Pemohon --}}
                            <td class="py-3.5 pl-5 pr-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-orange-100 flex items-center justify-center shrink-0">
                                        <span class="text-[10px] font-bold text-orange-600">{{ strtoupper(substr($user?->name ?? '?', 0, 2)) }}</span>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900 leading-tight">{{ $user?->name ?? '-' }}</div>
                                        <div class="text-[11px] text-gray-400">{{ $user?->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Institusi --}}
                            <td class="py-3.5 px-3">
                                <div class="font-medium text-gray-800 leading-tight">{{ $profile?->universitas ?? '-' }}</div>
                                <div class="text-[11px] text-gray-400">{{ $profile?->program_studi ?? '-' }}</div>
                                @if($profile?->nim)
                                    <div class="text-[10px] font-mono text-gray-400 mt-0.5">NIM: {{ $profile->nim }}</div>
                                @endif
                            </td>

                            {{-- Periode --}}
                            <td class="py-3.5 px-3 text-gray-500 whitespace-nowrap">
                                @if($p->tanggal_mulai_rencana && $p->tanggal_selesai_rencana)
                                    <div>{{ \Carbon\Carbon::parse($p->tanggal_mulai_rencana)->isoFormat('D MMM Y') }}</div>
                                    <div class="text-[11px] text-gray-400">s/d {{ \Carbon\Carbon::parse($p->tanggal_selesai_rencana)->isoFormat('D MMM Y') }}</div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            {{-- Berkas --}}
                            <td class="py-3.5 px-3">
                                @if($dokCount > 0)
                                    <button type="button"
                                            @click="openDetail({{ $p->id }})"
                                            class="inline-flex items-center gap-1 text-xs font-semibold text-orange-600 hover:text-orange-700 transition no-async">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        {{ $dokCount }} Berkas
                                    </button>
                                @else
                                    <span class="text-[11px] text-gray-400 italic">Belum ada</span>
                                @endif
                            </td>

                            {{-- Status badge --}}
                            <td class="py-3.5 px-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $badgeClass }}">
                                    {{ $st->label() }}
                                </span>
                                @if($p->reviewed_at)
                                    <div class="text-[10px] text-gray-400 mt-0.5">
                                        {{ \Carbon\Carbon::parse($p->reviewed_at)->isoFormat('D MMM Y, HH:mm') }}
                                    </div>
                                @endif
                            </td>

                            {{-- Catatan Admin --}}
                            <td class="py-3.5 px-3 max-w-[160px]">
                                @if($p->catatan_admin)
                                    <p class="text-[11px] text-gray-600 leading-relaxed line-clamp-2 italic">"{{ $p->catatan_admin }}"</p>
                                @else
                                    <span class="text-[11px] text-gray-300">—</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="py-3.5 pl-3 pr-5">
                                <div class="flex items-center justify-end gap-1.5">
                                    {{-- Tombol Detail --}}
                                    <a href="{{ route('admin.permohonan.show', $p->id) }}"
                                       class="no-async inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 text-[11px] font-semibold transition">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Detail
                                    </a>

                                    {{-- Action dropdown (hanya untuk status yang bisa diaksi) --}}
                                    @if(in_array($st->value, ['submitted', 'reviewed_by_admin']))
                                        <div x-data="{ open: false }" class="relative">
                                            <button @click="open = !open" type="button"
                                                    class="no-async inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-orange-500 hover:bg-orange-600 text-white text-[11px] font-semibold transition shadow-sm">
                                                Proses
                                                <svg class="w-3 h-3" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                            </button>

                                            <div x-show="open"
                                                 @click.away="open = false"
                                                 x-transition:enter="transition ease-out duration-100"
                                                 x-transition:enter-start="opacity-0 scale-95"
                                                 x-transition:enter-end="opacity-100 scale-100"
                                                 x-transition:leave="transition ease-in duration-75"
                                                 x-transition:leave-start="opacity-100 scale-100"
                                                 x-transition:leave-end="opacity-0 scale-95"
                                                 class="absolute right-0 mt-1 w-56 bg-white rounded-xl border border-gray-200 shadow-lg z-30 overflow-hidden">

                                                {{-- Setujui --}}
                                                @if($st->value === 'submitted')
                                                    <button type="button"
                                                            @click="open=false; openAction({{ $p->id }}, 'reviewed_by_admin')"
                                                            class="no-async w-full flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-blue-700 hover:bg-blue-50 transition">
                                                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                                        Tinjau Berkas (Admin)
                                                    </button>
                                                @endif
                                                @if($st->value === 'reviewed_by_admin')
                                                    <button type="button"
                                                            @click="open=false; openAction({{ $p->id }}, 'approved')"
                                                            class="no-async w-full flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-50 transition">
                                                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        Setujui Berkas
                                                    </button>
                                                @endif
                                                <div class="border-t border-gray-100"></div>
                                                <button type="button"
                                                        @click="open=false; openAction({{ $p->id }}, 'rejected')"
                                                        class="no-async w-full flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
                                                    <svg class="w-3.5 h-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    Tolak Permohonan
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-[11px] text-gray-300 italic">—</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-gray-400">
                                    <svg class="w-10 h-10 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="text-sm font-medium">Belum ada permohonan magang</p>
                                    <p class="text-xs">Permohonan akan muncul di sini setelah mahasiswa mengajukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Informasi & Pagination --}}
        <div class="mt-auto px-5 py-3.5 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-2 bg-gray-50/40">
            <div class="text-xs text-gray-500">
                @if($permohonans->total() > 0)
                    Menampilkan <span class="font-semibold text-gray-700">{{ $permohonans->firstItem() }}–{{ $permohonans->lastItem() }}</span>
                    dari <span class="font-semibold text-gray-700">{{ $permohonans->total() }}</span> permohonan
                @else
                    Tidak ada data permohonan
                @endif
            </div>
            @if($permohonans->hasPages())
                <div>{{ $permohonans->links() }}</div>
            @endif
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     MODAL – Action (Setujui / Tinjau / Tolak)
════════════════════════════════════════════════════════════════════════════ --}}
<div x-data="permohonanModal()"
     x-show="show"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @open-action.window="open($event.detail)"
     @keydown.escape.window="show=false"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop bg-black/30"
     style="display:none;">

    <div @click.stop
         x-show="show"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-2"
         class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-sm overflow-hidden">

        {{-- Modal header --}}
        <div class="px-5 py-4 flex items-center justify-between border-b border-gray-100"
             :class="{
                 'bg-blue-50': targetStatus==='reviewed_by_admin',
                 'bg-emerald-50': targetStatus==='approved',
                 'bg-rose-50': targetStatus==='rejected'
             }">
            <div>
                <h3 class="font-bold text-sm text-gray-900"
                    x-text="targetStatus==='reviewed_by_admin' ? 'Tinjau Berkas'
                           : targetStatus==='approved' ? 'Setujui Berkas'
                           : 'Tolak Permohonan'"></h3>
                <p class="text-[11px] text-gray-500 mt-0.5"
                   x-text="targetStatus==='rejected'
                           ? 'Permohonan ini akan ditolak.'
                           : (targetStatus==='approved' ? 'Akun pemohon otomatis aktif sebagai Mahasiswa.' : 'Tinjauan berkas tahap verifikasi admin.')"></p>
            </div>
            <button @click="show=false" class="w-7 h-7 rounded-full bg-black/5 hover:bg-black/10 flex items-center justify-center transition">
                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Modal body --}}
        <form :action="actionUrl()" method="POST" @submit.prevent="submit($event)">
            @csrf
            @method('PATCH')
            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="status" :value="targetStatus">

            <div class="px-5 py-4 space-y-3">
                <label class="block text-xs font-semibold text-gray-700 mb-1">
                    Catatan Admin
                    <span x-show="targetStatus==='rejected'" class="text-rose-500">*</span>
                </label>
                <textarea name="catatan_admin"
                          :placeholder="targetStatus==='rejected' ? 'Tulis alasan penolakan...' : 'Tambahkan catatan (opsional)'"
                          rows="3"
                          :required="targetStatus==='rejected'"
                          class="w-full px-3 py-2 text-xs border border-gray-200 rounded-xl text-gray-800
                                 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400
                                 focus:border-orange-400 resize-none transition leading-relaxed"></textarea>

                {{-- Info box --}}
                <div class="flex items-start gap-2 px-3 py-2 rounded-xl bg-orange-50 border border-orange-100">
                    <svg class="w-3.5 h-3.5 text-orange-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-[10.5px] text-orange-700 leading-relaxed"
                       x-text="targetStatus==='approved'
                               ? 'Saat disetujui, akun pemohon otomatis dialihkan menjadi role Mahasiswa dan periode magang aktif sesuai tanggal rencana.'
                               : (targetStatus==='rejected' ? 'Permohonan ditolak dan pemohon akan menerima catatan ini.' : 'Tinjauan berkas tahap verifikasi admin.')">
                    </p>
                </div>
            </div>

            {{-- Modal footer --}}
            <div class="px-5 py-3.5 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-2">
                <button type="button" @click="show=false"
                        class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit" :disabled="loading"
                        :class="{
                            'bg-blue-500 hover:bg-blue-600': targetStatus==='reviewed_by_admin',
                            'bg-emerald-500 hover:bg-emerald-600': targetStatus==='approved',
                            'bg-rose-500 hover:bg-rose-600': targetStatus==='rejected'
                        }"
                        class="px-4 py-2 rounded-xl text-xs font-semibold text-white transition shadow-sm disabled:opacity-60">
                    <span x-show="!loading"
                          x-text="targetStatus==='reviewed_by_admin' ? 'Ya, Tinjau'
                                 : targetStatus==='approved' ? 'Ya, Setujui'
                                 : 'Ya, Tolak'"></span>
                    <span x-show="loading">Memproses…</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function permohonanAdmin() {
    return {
        init() {
            // Listen to open-action events from table rows
            window.addEventListener('open-action', e => {
                window.dispatchEvent(new CustomEvent('open-action', { detail: e.detail }));
            });
        },
        openAction(id, status) {
            window.dispatchEvent(new CustomEvent('open-action', { detail: { id, status } }));
        },
        openDetail(id) {
            window.location.href = '{{ route("admin.permohonan.show", 999999) }}'.replace('999999', id);
        }
    };
}

function permohonanModal() {
    return {
        show: false,
        loading: false,
        targetId: null,
        targetStatus: 'reviewed_by_admin',
        open({ id, status }) {
            this.targetId = id;
            this.targetStatus = status;
            this.show = true;
        },
        actionUrl() {
            return '{{ url("admin/permohonan") }}/' + this.targetId + '/status';
        },
        async submit(event) {
            if (this.loading) return;
            this.loading = true;
            const form = event.target;
            const data = new FormData(form);
            data.set('_method', 'PATCH');
            try {
                const resp = await fetch(this.actionUrl(), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-HTTP-Method-Override': 'PATCH',
                    },
                    body: data,
                });
                if (resp.ok) {
                    this.show = false;
                    // Reload async container tanpa full page refresh
                    if (window.SiBookAsync) {
                        SiBookAsync.load(window.location.href, 'permohonan-table-container');
                    } else {
                        window.location.reload();
                    }
                } else {
                    const json = await resp.json().catch(() => ({}));
                    alert(json.message || 'Terjadi kesalahan. Silakan coba lagi.');
                }
            } catch (err) {
                alert('Gagal terhubung ke server.');
            } finally {
                this.loading = false;
            }
        }
    };
}
</script>
@endpush
@endsection
