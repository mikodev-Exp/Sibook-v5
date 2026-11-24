@extends('layouts.role', ['role' => 'mahasiswa'])

@section('title', 'Logbook Kegiatan')

@section('content')
<div x-data="{
    showCreateModal: false,
    showLanjutkanModal: false,
    showEditModal: false,
    showGuideBookModal: false,
    selectedDraft: null,
    editData: {
        id: '',
        tim: '',
        jenis_gsbpm: '',
        tanggal: '',
        jam_mulai: '',
        jam_selesai: '',
        kegiatan: '',
        deskripsi: '',
        link_bukti: '',
        status: 'draft',
        updateUrl: ''
    },
    openEdit(data) {
        this.editData = { ...data };
        this.showEditModal = true;
    },
    setNow(el) {
        if (!el) return;
        const d = new Date();
        const hh = String(d.getHours()).padStart(2, '0');
        const mm = String(d.getMinutes()).padStart(2, '0');
        el.value = `${hh}:${mm}`;
    },
    autoFillSelesai(formEl) {
        const jamSelesaiEl = formEl.querySelector('input[name=\'jam_selesai\']');
        if (jamSelesaiEl && !jamSelesaiEl.value) {
            this.setNow(jamSelesaiEl);
        }
    }
}" class="max-w-6xl mx-auto space-y-6 sm:space-y-8 px-2 sm:px-0">

    {{-- Alerts & Notifications --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition class="p-3.5 sm:p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between gap-3 text-xs font-mono-tag shadow-xs">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition class="p-3.5 sm:p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center justify-between gap-3 text-xs font-mono-tag shadow-xs">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" @click="show = false" class="text-rose-500 hover:text-rose-700">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- Header & Aksi (Studio Header Responsif HP) --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-mono-tag text-zinc-400 uppercase tracking-widest mb-1">
                <span>ACTIVITY ARCHIVE</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-zinc-950 tracking-tight">Logbook Kegiatan Harian</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Dokumentasi portofolio kerja dan rekam aktivitas penugasan statistik.</p>
        </div>

        {{-- Group Tombol Aksi (Responsif Handphone & Desktop) --}}
        <div class="flex flex-wrap sm:flex-nowrap items-stretch sm:items-center gap-2 sm:gap-3 w-full sm:w-auto">
            {{-- Tombol GuideBook GSBPM --}}
            <button type="button"
                    @click="showGuideBookModal = true" 
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-xl border border-blue-200 bg-blue-50/80 hover:bg-blue-100 text-blue-900 text-xs font-mono-tag font-semibold transition shadow-xs hover:shadow-sm active:scale-95 cursor-pointer">
                <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span>GUIDEBOOK</span>
            </button>

            {{-- Tombol Lanjutkan Proyek (Buka Pop-Up Modal) --}}
            <button type="button"
                    @click="showLanjutkanModal = true; selectedDraft = null;" 
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-xl border border-zinc-200 bg-white hover:bg-zinc-50 text-zinc-800 text-xs font-mono-tag font-semibold transition shadow-xs hover:shadow-sm active:scale-95 cursor-pointer">
                <svg class="w-3.5 h-3.5 text-zinc-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>LANJUTKAN PROYEK</span>
                @if($belumSelesai > 0)
                    <span class="ml-1 px-1.5 py-0.2 bg-amber-100 text-amber-900 text-[10px] rounded-full font-bold">{{ $belumSelesai }}</span>
                @endif
            </button>

            {{-- Tombol Buat Logbook Baru (Buka Pop-Up Modal) --}}
            <button type="button"
                    @click="showCreateModal = true;" 
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2.5 rounded-xl bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-mono-tag font-semibold transition shadow-sm hover:shadow active:scale-95 cursor-pointer">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span>BUAT LOGBOOK BARU</span>
            </button>
        </div>
    </div>

    {{-- Stat Chips --}}
    <div class="flex flex-wrap items-center gap-2 sm:gap-2.5 font-mono-tag text-xs">
        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white text-zinc-800 border border-zinc-200 shadow-xs">
            <span class="font-bold text-zinc-950">{{ $totalLogbook }}</span> TOTAL AKTIVITAS
        </span>
        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
            <span class="font-bold">{{ $selesai }}</span> SELESAI / APPROVED
        </span>
        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
            <span class="font-bold">{{ $belumSelesai }}</span> DRAF / BERJALAN
        </span>
    </div>

    {{-- Tabel Riwayat Logbook (Gallery Ledger) --}}
    <div id="mahasiswa-logbook-container"
         data-async-container
         @click="SiBookAsync.handleClick($event, 'mahasiswa-logbook-container')"
         class="bg-white rounded-3xl shadow-sm border border-zinc-200/80 overflow-hidden flex flex-col min-h-[520px]">
        
        <div class="p-5 sm:p-8 border-b border-zinc-100 flex justify-between items-center">
            <div>
                <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-widest block mb-0.5">LEDGER RECORDS</span>
                <h2 class="text-base font-extrabold text-zinc-950 tracking-tight">Catatan Aktivitas Karya</h2>
            </div>
            <span class="text-xs font-mono-tag text-zinc-400">CHRONOLOGICAL ORDER</span>
        </div>

        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left border-collapse text-xs font-mono-tag min-w-[620px] sm:min-w-full">
                <thead>
                    <tr class="border-b border-zinc-100 text-zinc-400 uppercase tracking-wider bg-zinc-50/50">
                        <th class="py-3.5 px-4 sm:px-6">Tanggal &amp; Waktu</th>
                        <th class="py-3.5 px-3 sm:px-4">Kegiatan &amp; Divisi</th>
                        <th class="py-3.5 px-3 sm:px-4">Deskripsi</th>
                        <th class="py-3.5 px-3">Bukti</th>
                        <th class="py-3.5 px-3">Status</th>
                        <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 text-zinc-800">
                    @forelse($riwayat as $row)
                        @php
                            $rowStatus = $row->status instanceof \App\Enums\StatusLogbook 
                                ? $row->status->value 
                                : (string) $row->status;

                            $badge = match($rowStatus) {
                                'approved'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'rejected'  => 'bg-rose-50 text-rose-700 border-rose-200',
                                'submitted' => 'bg-zinc-100 text-zinc-700 border-zinc-200',
                                default     => 'bg-amber-50 text-amber-700 border-amber-200',
                            };

                            $label = match($rowStatus) {
                                'approved'  => 'APPROVED',
                                'rejected'  => 'REJECTED',
                                'submitted' => 'SUBMITTED',
                                default     => 'DRAFT',
                            };
                        @endphp
                        {{-- Baris Induk --}}
                        <tr class="hover:bg-zinc-50/60 transition group font-sans">
                            <td class="py-4 px-4 sm:px-6 whitespace-nowrap font-mono-tag">
                                <div class="font-bold text-zinc-950 text-xs">
                                    {{ \Carbon\Carbon::parse($row->tanggal)->isoFormat('D MMM Y') }}
                                </div>
                                <div class="text-[11px] text-zinc-400 mt-0.5">
                                    {{ $row->jam_mulai ? substr($row->jam_mulai, 0, 5) : '-' }} - {{ $row->jam_selesai ? substr($row->jam_selesai, 0, 5) : '...' }}
                                </div>
                            </td>
                            <td class="py-4 px-3 sm:px-4">
                                <div class="font-bold text-zinc-950 text-xs">{{ $row->kegiatan ?: $row->deskripsi }}</div>
                                <div class="flex items-center gap-1.5 mt-1 font-mono-tag">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] bg-zinc-100 text-zinc-700 border border-zinc-200">
                                        {{ $row->tim }}
                                    </span>
                                    @if($row->jenis_gsbpm)
                                        <span class="text-[10px] text-zinc-400">GSBPM: {{ $row->jenis_gsbpm }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-3 sm:px-4 max-w-xs">
                                <div class="text-xs text-zinc-600 line-clamp-2 leading-relaxed" title="{{ $row->deskripsi }}">
                                    {{ $row->deskripsi }}
                                </div>
                            </td>
                            <td class="py-4 px-3 whitespace-nowrap font-mono-tag">
                                @if($row->link_bukti)
                                    <a href="{{ $row->link_bukti }}" target="_blank"
                                       class="no-async text-xs font-semibold text-zinc-950 hover:underline inline-flex items-center gap-1">
                                        <span>BUKTI</span>
                                        <svg class="w-3 h-3 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                @else
                                    <span class="text-xs text-zinc-300">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-3 whitespace-nowrap font-mono-tag">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ $badge }}">
                                    {{ $label }}
                                </span>
                            </td>
                            <td class="py-4 px-4 sm:px-6 text-right whitespace-nowrap space-x-1.5 font-mono-tag">
                                {{-- Tombol Edit via Pop-up Modal --}}
                                <button type="button"
                                        @click='openEdit({
                                            id: {{ $row->id }},
                                            tim: {{ json_encode($row->tim ?? '') }},
                                            jenis_gsbpm: {{ json_encode($row->jenis_gsbpm ?? '') }},
                                            tanggal: "{{ $row->tanggal ? $row->tanggal->format('Y-m-d') : '' }}",
                                            jam_mulai: "{{ $row->jam_mulai ? substr($row->jam_mulai, 0, 5) : '' }}",
                                            jam_selesai: "{{ $row->jam_selesai ? substr($row->jam_selesai, 0, 5) : '' }}",
                                            kegiatan: {{ json_encode($row->kegiatan ?: $row->deskripsi) }},
                                            deskripsi: {{ json_encode($row->deskripsi) }},
                                            link_bukti: {{ json_encode($row->link_bukti) }},
                                            status: "{{ $rowStatus }}",
                                            updateUrl: "{{ route('mahasiswa.logbook.update', $row->id) }}"
                                        })'
                                        class="inline-block px-3 py-1 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 rounded-lg text-xs font-semibold transition active:scale-95 cursor-pointer">
                                    EDIT
                                </button>
                                <form action="{{ route('mahasiswa.logbook.destroy', $row->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus logbook ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-semibold transition">
                                        HAPUS
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- Baris Lanjutan (Children) --}}
                        @if($row->children && $row->children->isNotEmpty())
                            @foreach($row->children as $child)
                                @php
                                    $cStatus = $child->status instanceof \App\Enums\StatusLogbook 
                                        ? $child->status->value 
                                        : (string) $child->status;

                                    $cBadge = match($cStatus) {
                                        'approved'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'rejected'  => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'submitted' => 'bg-zinc-100 text-zinc-700 border-zinc-200',
                                        default     => 'bg-amber-50 text-amber-700 border-amber-200',
                                    };

                                    $cLabel = match($cStatus) {
                                        'approved'  => 'APPROVED',
                                        'rejected'  => 'REJECTED',
                                        'submitted' => 'SUBMITTED',
                                        default     => 'DRAFT',
                                    };
                                @endphp
                                <tr class="bg-zinc-50/70 hover:bg-zinc-100/60 transition border-b border-zinc-100 font-sans">
                                    <td class="py-3 px-4 sm:px-6 pl-8 sm:pl-12 relative whitespace-nowrap font-mono-tag">
                                        <div class="absolute left-4 sm:left-6 top-1/2 -translate-y-1/2 text-zinc-400">
                                            &rdquor;
                                        </div>
                                        <div class="font-medium text-zinc-800 text-xs">
                                            {{ \Carbon\Carbon::parse($child->tanggal)->isoFormat('D MMM Y') }}
                                        </div>
                                        <div class="text-[10.5px] text-zinc-400">
                                            {{ $child->jam_mulai ? substr($child->jam_mulai, 0, 5) : '-' }} - {{ $child->jam_selesai ? substr($child->jam_selesai, 0, 5) : '...' }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-3 sm:px-4">
                                        <div class="text-xs text-zinc-800 font-medium">Lanjutan: {{ $child->kegiatan ?: $row->kegiatan }}</div>
                                        <div class="text-[10.5px] text-zinc-400 font-mono-tag">{{ $child->tim }} - {{ $child->jenis_gsbpm }}</div>
                                    </td>
                                    <td class="py-3 px-3 sm:px-4 max-w-xs">
                                        <div class="text-xs text-zinc-500 line-clamp-1" title="{{ $child->deskripsi }}">{{ $child->deskripsi }}</div>
                                    </td>
                                    <td class="py-3 px-3 whitespace-nowrap font-mono-tag">
                                        @if($child->link_bukti)
                                            <a href="{{ $child->link_bukti }}" target="_blank"
                                               class="no-async text-xs font-semibold text-zinc-950 hover:underline">
                                                BUKTI
                                            </a>
                                        @else
                                            <span class="text-xs text-zinc-400">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 whitespace-nowrap font-mono-tag">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $cBadge }}">
                                            {{ $cLabel }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 sm:px-6 text-right whitespace-nowrap space-x-1.5 font-mono-tag">
                                        {{-- Tombol Edit via Pop-up Modal untuk Baris Anak --}}
                                        <button type="button"
                                                @click='openEdit({
                                                    id: {{ $child->id }},
                                                    tim: {{ json_encode($child->tim ?? '') }},
                                                    jenis_gsbpm: {{ json_encode($child->jenis_gsbpm ?? '') }},
                                                    tanggal: "{{ $child->tanggal ? $child->tanggal->format('Y-m-d') : '' }}",
                                                    jam_mulai: "{{ $child->jam_mulai ? substr($child->jam_mulai, 0, 5) : '' }}",
                                                    jam_selesai: "{{ $child->jam_selesai ? substr($child->jam_selesai, 0, 5) : '' }}",
                                                    kegiatan: {{ json_encode($child->kegiatan ?: $row->kegiatan) }},
                                                    deskripsi: {{ json_encode($child->deskripsi) }},
                                                    link_bukti: {{ json_encode($child->link_bukti) }},
                                                    status: "{{ $cStatus }}",
                                                    updateUrl: "{{ route('mahasiswa.logbook.update', $child->id) }}"
                                                })'
                                                class="inline-block px-2.5 py-0.5 bg-zinc-200 hover:bg-zinc-300 text-zinc-800 rounded text-xs font-semibold active:scale-95 cursor-pointer">
                                            EDIT
                                        </button>
                                        <form action="{{ route('mahasiswa.logbook.destroy', $child->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus logbook ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-2.5 py-0.5 bg-rose-100 hover:bg-rose-200 text-rose-700 rounded text-xs font-semibold">
                                                HAPUS
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="py-14 text-center">
                                <div class="flex flex-col items-center justify-center text-zinc-400 font-mono-tag">
                                    <p class="text-xs font-bold text-zinc-600">// BELUM ADA RIWAYAT LOGBOOK</p>
                                    <p class="text-[11px] text-zinc-400 mt-1">Mulai dengan mencatat kegiatan harian Anda melalui tombol di atas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 sm:p-5 border-t border-zinc-100 bg-zinc-50/50">
            {{ $riwayat->links() }}
        </div>
    </div>


    {{-- ========================================================================= --}}
    {{-- MODAL GUIDEBOOK GSBPM (POP-UP PANDUAN LENGKAP & SCROLLABLE / RESPONSIVE)  --}}
    {{-- ========================================================================= --}}
    <div x-show="showGuideBookModal" 
         x-cloak 
         @keydown.escape.window="showGuideBookModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-2.5 sm:p-6 overflow-y-auto bg-zinc-950/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="bg-white rounded-3xl border border-zinc-200/80 shadow-2xl max-w-4xl w-full max-h-[94vh] sm:max-h-[88vh] flex flex-col overflow-hidden"
             @click.outside="showGuideBookModal = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">

            {{-- Modal Header Sticky --}}
            <div class="border-b border-zinc-100 p-4 sm:p-6 flex items-start justify-between bg-zinc-50/70 shrink-0">
                <div class="min-w-0 pr-3">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-mono-tag uppercase tracking-widest text-blue-600 font-bold px-2 py-0.5 bg-blue-50 border border-blue-200 rounded-md">
                            GSBPM v5.1 MANUAL
                        </span>
                        <span class="text-[10px] font-mono-tag text-zinc-400 hidden sm:inline">// BPS KOTA SURABAYA</span>
                    </div>
                    <h2 class="text-lg sm:text-xl font-extrabold text-zinc-950 tracking-tight leading-tight">Guide Book GSBPM untuk Logbook Magang</h2>
                    <p class="text-xs text-zinc-500 mt-0.5 truncate">Panduan memetakan kegiatan magang ke fase-fase proses bisnis statistik.</p>
                </div>
                <button type="button" 
                        @click="showGuideBookModal = false"
                        class="w-8 h-8 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-500 hover:text-zinc-900 flex items-center justify-center transition shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Modal Body Konten GuideBook (Scrollable ke bawah) --}}
            <div class="overflow-y-auto p-4 sm:p-8 space-y-8 text-xs leading-relaxed text-zinc-700">

                {{-- Banner Metadata --}}
                <div class="p-4 rounded-2xl bg-gradient-to-r from-blue-50/80 to-indigo-50/60 border border-blue-200/70 text-zinc-800 space-y-1">
                    <div class="flex flex-wrap items-center justify-between gap-2 font-mono-tag text-[11px]">
                        <span class="font-bold text-blue-900">Panduan Logbook Magang BPS Kota Surabaya</span>
                        <span class="text-zinc-500">Disusun untuk: <strong>Difa Islaili Rahmadatul Kimayah</strong></span>
                    </div>
                    <p class="text-[11px] text-zinc-600 leading-relaxed pt-1">
                        <em>Catatan penggunaan:</em> Struktur fase dan sub-proses mengacu pada GSBPM versi 5.1. Pemetaan kegiatan magang bersifat usulan berdasarkan pekerjaan statistik yang dilaksanakan. Selalu sesuaikan dengan format logbook BPS dan arahan pembimbing lapangan.
                    </p>
                </div>

                {{-- 1. Apa itu GSBPM? --}}
                <section id="guide-sec-1" class="space-y-3 pt-2">
                    <div class="flex items-center gap-2 border-b border-zinc-200 pb-2">
                        <span class="w-6 h-6 rounded-lg bg-zinc-950 text-white font-mono-tag font-bold text-xs flex items-center justify-center">1</span>
                        <h3 class="text-sm font-bold text-zinc-950 uppercase tracking-wide">Apa itu GSBPM?</h3>
                    </div>
                    <p>
                        <strong>GSBPM (Generic Statistical Business Process Model)</strong> adalah model proses bisnis statistik generik yang dikembangkan oleh komunitas statistik internasional (UNECE bersama badan statistik berbagai negara). Model ini menggambarkan seluruh rangkaian kegiatan yang dilakukan lembaga statistik untuk menghasilkan statistik resmi, mulai dari mengidentifikasi kebutuhan data sampai mengevaluasi hasilnya.
                    </p>
                    <p>
                        GSBPM bukan metode statistik dan bukan pedoman teknis satu survei tertentu. Fungsinya sebagai <em>kerangka bahasa bersama</em>: setiap kegiatan statistik, sekecil apa pun, dapat dikaitkan dengan satu fase atau sub-proses di dalamnya.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                        <div class="p-3.5 bg-zinc-50 rounded-2xl border border-zinc-200/70 space-y-1.5">
                            <span class="font-bold text-zinc-900 font-mono-tag text-[11px] block">// TUJUAN &amp; MANFAAT</span>
                            <ul class="list-disc list-inside space-y-1 text-[11.5px] text-zinc-600">
                                <li>Menyeragamkan istilah dan cara memandang proses produksi statistik.</li>
                                <li>Membantu perencanaan, pembagian peran, dan pemantauan pekerjaan statistik.</li>
                                <li>Memudahkan penilaian kualitas dan identifikasi tahap yang perlu diperbaiki.</li>
                                <li>Memudahkan perbandingan dan pertukaran praktik antar-lembaga.</li>
                            </ul>
                        </div>
                        <div class="p-3.5 bg-blue-50/50 rounded-2xl border border-blue-200/60 space-y-1.5">
                            <span class="font-bold text-blue-950 font-mono-tag text-[11px] block">// MENGAPA RELEVAN UNTUK LOGBOOK?</span>
                            <p class="text-[11.5px] text-blue-900 leading-relaxed">
                                Logbook yang hanya berisi <em>"mengerjakan file Excel"</em> kurang menggambarkan kontribusi nyata Anda. Dengan GSBPM, setiap kegiatan memiliki posisi yang jelas dalam rantai produksi statistik, sehingga kinerja magang tampak terstruktur, terukur, dan mudah dipahami oleh pembimbing maupun penguji.
                            </p>
                        </div>
                    </div>
                </section>

                {{-- 2. Gambaran Delapan Fase GSBPM --}}
                <section id="guide-sec-2" class="space-y-3">
                    <div class="flex items-center gap-2 border-b border-zinc-200 pb-2">
                        <span class="w-6 h-6 rounded-lg bg-zinc-950 text-white font-mono-tag font-bold text-xs flex items-center justify-center">2</span>
                        <h3 class="text-sm font-bold text-zinc-950 uppercase tracking-wide">Gambaran Delapan Fase GSBPM</h3>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-zinc-200/80 shadow-xs">
                        <table class="w-full text-left border-collapse min-w-[500px]">
                            <thead class="bg-zinc-50 font-mono-tag text-[11px] text-zinc-500 uppercase border-b border-zinc-200">
                                <tr>
                                    <th class="py-2.5 px-4 w-12 text-center">No</th>
                                    <th class="py-2.5 px-4">Fase</th>
                                    <th class="py-2.5 px-4">Inti Kegiatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100 text-zinc-800">
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4 text-center font-mono-tag font-bold">1</td><td class="py-2.5 px-4 font-bold text-purple-700">Specify Needs (Menentukan Kebutuhan)</td><td class="py-2.5 px-4">Memahami kebutuhan data dan tujuan statistik</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4 text-center font-mono-tag font-bold">2</td><td class="py-2.5 px-4 font-bold text-blue-700">Design (Perancangan)</td><td class="py-2.5 px-4">Merancang output, instrumen, sampel, dan alur pengolahan</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4 text-center font-mono-tag font-bold">3</td><td class="py-2.5 px-4 font-bold text-indigo-700">Build (Pembangunan)</td><td class="py-2.5 px-4">Membangun dan menguji instrumen, aplikasi, dan sistem produksi</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4 text-center font-mono-tag font-bold">4</td><td class="py-2.5 px-4 font-bold text-cyan-700">Collect (Pengumpulan)</td><td class="py-2.5 px-4">Menyiapkan dan menjalankan pengumpulan data</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4 text-center font-mono-tag font-bold">5</td><td class="py-2.5 px-4 font-bold text-amber-700">Process (Pengolahan)</td><td class="py-2.5 px-4">Membersihkan, memvalidasi, dan menyiapkan data</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4 text-center font-mono-tag font-bold">6</td><td class="py-2.5 px-4 font-bold text-orange-700">Analyse (Analisis)</td><td class="py-2.5 px-4">Menyusun dan menginterpretasi output statistik</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4 text-center font-mono-tag font-bold">7</td><td class="py-2.5 px-4 font-bold text-emerald-700">Disseminate (Diseminasi)</td><td class="py-2.5 px-4">Menyebarluaskan produk statistik ke pengguna</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4 text-center font-mono-tag font-bold">8</td><td class="py-2.5 px-4 font-bold text-rose-700">Evaluate (Evaluasi)</td><td class="py-2.5 px-4">Mengevaluasi proses dan merencanakan perbaikan</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="text-[11px] text-zinc-500 italic">
                        * Selain delapan fase tersebut ada proses lintas fase yang berjalan di semua tahap: manajemen kualitas, manajemen metadata, manajemen data, manajemen proses dan alur kerja, serta manajemen kapabilitas dan kemitraan.
                    </p>
                </section>

                {{-- 3. Rincian Fase dan Sub-proses --}}
                <section id="guide-sec-3" class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-zinc-200 pb-2">
                        <span class="w-6 h-6 rounded-lg bg-zinc-950 text-white font-mono-tag font-bold text-xs flex items-center justify-center">3</span>
                        <h3 class="text-sm font-bold text-zinc-950 uppercase tracking-wide">Rincian Fase dan Sub-proses</h3>
                    </div>

                    <div class="grid grid-cols-1 gap-3.5">
                        {{-- Fase 1 --}}
                        <div class="p-3.5 rounded-2xl border border-purple-200/80 bg-purple-50/20">
                            <span class="font-bold text-purple-900 font-mono-tag text-xs block mb-2">Fase 1 — Specify Needs (Menentukan Kebutuhan)</span>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-[11.5px] min-w-[420px]">
                                    <thead class="text-zinc-400 font-mono-tag border-b border-purple-100"><tr><th class="py-1 px-2 w-16">Kode</th><th class="py-1 px-3">Sub-proses</th><th class="py-1 px-3">Contoh Kegiatan Magang</th></tr></thead>
                                    <tbody class="divide-y divide-purple-100/60">
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-purple-800">1.1</td><td class="py-1.5 px-3 font-semibold">Mengidentifikasi kebutuhan</td><td class="py-1.5 px-3">Mencatat kebutuhan data dari pengguna atau unit kerja</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-purple-800">1.2</td><td class="py-1.5 px-3 font-semibold">Konsultasi &amp; konfirmasi</td><td class="py-1.5 px-3">Diskusi dengan pembimbing/tim untuk memastikan kebutuhan</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-purple-800">1.3</td><td class="py-1.5 px-3 font-semibold">Menetapkan tujuan output</td><td class="py-1.5 px-3">Menentukan keluaran apa yang akan dihasilkan</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-purple-800">1.4</td><td class="py-1.5 px-3 font-semibold">Mengidentifikasi konsep</td><td class="py-1.5 px-3">Menyepakati definisi dan konsep yang dipakai</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-purple-800">1.5</td><td class="py-1.5 px-3 font-semibold">Memeriksa ketersediaan data</td><td class="py-1.5 px-3">Mengecek ketersediaan sumber data yang sudah ada</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-purple-800">1.6</td><td class="py-1.5 px-3 font-semibold">Menyusun business case</td><td class="py-1.5 px-3">Menyusun usulan kegiatan atau inovasi</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Fase 2 --}}
                        <div class="p-3.5 rounded-2xl border border-blue-200/80 bg-blue-50/20">
                            <span class="font-bold text-blue-900 font-mono-tag text-xs block mb-2">Fase 2 — Design (Perancangan)</span>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-[11.5px] min-w-[420px]">
                                    <thead class="text-zinc-400 font-mono-tag border-b border-blue-100"><tr><th class="py-1 px-2 w-16">Kode</th><th class="py-1 px-3">Sub-proses</th><th class="py-1 px-3">Contoh Kegiatan Magang</th></tr></thead>
                                    <tbody class="divide-y divide-blue-100/60">
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-blue-800">2.1</td><td class="py-1.5 px-3 font-semibold">Merancang output</td><td class="py-1.5 px-3">Merancang format tabel atau laporan keluaran</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-blue-800">2.2</td><td class="py-1.5 px-3 font-semibold">Merancang deskripsi variabel</td><td class="py-1.5 px-3">Menyusun daftar variabel dan definisinya</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-blue-800">2.3</td><td class="py-1.5 px-3 font-semibold">Merancang pengumpulan data</td><td class="py-1.5 px-3">Merancang kuesioner, alur pencacahan, atau formulir input</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-blue-800">2.4</td><td class="py-1.5 px-3 font-semibold">Merancang kerangka &amp; sampel</td><td class="py-1.5 px-3">Menyiapkan rancangan daftar SLS atau sampel</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-blue-800">2.5</td><td class="py-1.5 px-3 font-semibold">Merancang pengolahan/analisis</td><td class="py-1.5 px-3">Merancang aturan validasi dan alur pengolahan</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-blue-800">2.6</td><td class="py-1.5 px-3 font-semibold">Merancang sistem &amp; alur kerja</td><td class="py-1.5 px-3">Merancang alur aplikasi, tahapan kerja, dan pembagian tugas</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Fase 3 --}}
                        <div class="p-3.5 rounded-2xl border border-indigo-200/80 bg-indigo-50/20">
                            <span class="font-bold text-indigo-900 font-mono-tag text-xs block mb-2">Fase 3 — Build (Pembangunan)</span>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-[11.5px] min-w-[420px]">
                                    <thead class="text-zinc-400 font-mono-tag border-b border-indigo-100"><tr><th class="py-1 px-2 w-16">Kode</th><th class="py-1 px-3">Sub-proses</th><th class="py-1 px-3">Contoh Kegiatan Magang</th></tr></thead>
                                    <tbody class="divide-y divide-indigo-100/60">
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-indigo-800">3.1</td><td class="py-1.5 px-3 font-semibold">Instrumen pengumpulan</td><td class="py-1.5 px-3">Membuat formulir input atau template pengumpulan</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-indigo-800">3.2</td><td class="py-1.5 px-3 font-semibold">Komponen pengolahan/analisis</td><td class="py-1.5 px-3">Membuat file kompilasi, rumus, atau makro pengolahan</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-indigo-800">3.3</td><td class="py-1.5 px-3 font-semibold">Komponen diseminasi</td><td class="py-1.5 px-3">Membuat template laporan atau tampilan output</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-indigo-800">3.4</td><td class="py-1.5 px-3 font-semibold">Konfigurasi alur kerja</td><td class="py-1.5 px-3">Mengatur urutan proses antar-komponen</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-indigo-800">3.5</td><td class="py-1.5 px-3 font-semibold">Menguji sistem produksi</td><td class="py-1.5 px-3">Uji coba aplikasi atau tool sistem</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-indigo-800">3.6</td><td class="py-1.5 px-3 font-semibold">Menguji proses bisnis statistik</td><td class="py-1.5 px-3">Uji coba alur dari input sampai output</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-indigo-800">3.7</td><td class="py-1.5 px-3 font-semibold">Finalisasi sistem produksi</td><td class="py-1.5 px-3">Penyempurnaan akhir dan penyiapan penggunaan tool</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Fase 4 --}}
                        <div class="p-3.5 rounded-2xl border border-cyan-200/80 bg-cyan-50/20">
                            <span class="font-bold text-cyan-900 font-mono-tag text-xs block mb-2">Fase 4 — Collect (Pengumpulan)</span>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-[11.5px] min-w-[420px]">
                                    <thead class="text-zinc-400 font-mono-tag border-b border-cyan-100"><tr><th class="py-1 px-2 w-16">Kode</th><th class="py-1 px-3">Sub-proses</th><th class="py-1 px-3">Contoh Kegiatan Magang</th></tr></thead>
                                    <tbody class="divide-y divide-cyan-100/60">
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-cyan-800">4.1</td><td class="py-1.5 px-3 font-semibold">Kerangka &amp; memilih sampel</td><td class="py-1.5 px-3">Menyusun daftar unit yang akan didata</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-cyan-800">4.2</td><td class="py-1.5 px-3 font-semibold">Menyiapkan pengumpulan</td><td class="py-1.5 px-3">Menyiapkan petugas, alokasi wilayah, dan perlengkapan</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-cyan-800">4.3</td><td class="py-1.5 px-3 font-semibold">Menjalankan pengumpulan</td><td class="py-1.5 px-3">Entri data, memantau progres lapangan</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-cyan-800">4.4</td><td class="py-1.5 px-3 font-semibold">Finalisasi pengumpulan</td><td class="py-1.5 px-3">Menutup pengumpulan dan menyerahkan data mentah</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Fase 5 --}}
                        <div class="p-3.5 rounded-2xl border border-amber-200/80 bg-amber-50/20">
                            <span class="font-bold text-amber-900 font-mono-tag text-xs block mb-2">Fase 5 — Process (Pengolahan)</span>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-[11.5px] min-w-[420px]">
                                    <thead class="text-zinc-400 font-mono-tag border-b border-amber-100"><tr><th class="py-1 px-2 w-16">Kode</th><th class="py-1 px-3">Sub-proses</th><th class="py-1 px-3">Contoh Kegiatan Magang</th></tr></thead>
                                    <tbody class="divide-y divide-amber-100/60">
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-amber-800">5.1</td><td class="py-1.5 px-3 font-semibold">Integrasi data</td><td class="py-1.5 px-3">Menggabungkan beberapa file atau sumber data</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-amber-800">5.2</td><td class="py-1.5 px-3 font-semibold">Klasifikasi &amp; pengkodean</td><td class="py-1.5 px-3">Memberi kode wilayah, kategori, atau jenis usaha</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-amber-800">5.3</td><td class="py-1.5 px-3 font-semibold">Review &amp; validasi</td><td class="py-1.5 px-3">Mencocokkan data dengan daftar acuan, memeriksa konsistensi</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-amber-800">5.4</td><td class="py-1.5 px-3 font-semibold">Editing &amp; imputasi</td><td class="py-1.5 px-3">Memperbaiki nama, format, atau nilai yang salah</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-amber-800">5.5</td><td class="py-1.5 px-3 font-semibold">Menurunkan variabel baru</td><td class="py-1.5 px-3">Menghitung kolom turunan atau indikator</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-amber-800">5.6</td><td class="py-1.5 px-3 font-semibold">Menghitung bobot</td><td class="py-1.5 px-3">Menghitung bobot sampel bila diperlukan</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-amber-800">5.7</td><td class="py-1.5 px-3 font-semibold">Menghitung agregat</td><td class="py-1.5 px-3">Merekap per wilayah, petugas, atau kategori</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-amber-800">5.8</td><td class="py-1.5 px-3 font-semibold">Finalisasi file data</td><td class="py-1.5 px-3">Menyimpan file bersih dengan format dan penamaan baku</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Fase 6 --}}
                        <div class="p-3.5 rounded-2xl border border-orange-200/80 bg-orange-50/20">
                            <span class="font-bold text-orange-900 font-mono-tag text-xs block mb-2">Fase 6 — Analyse (Analisis)</span>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-[11.5px] min-w-[420px]">
                                    <thead class="text-zinc-400 font-mono-tag border-b border-orange-100"><tr><th class="py-1 px-2 w-16">Kode</th><th class="py-1 px-3">Sub-proses</th><th class="py-1 px-3">Contoh Kegiatan Magang</th></tr></thead>
                                    <tbody class="divide-y divide-orange-100/60">
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-orange-800">6.1</td><td class="py-1.5 px-3 font-semibold">Menyusun draf output</td><td class="py-1.5 px-3">Membuat tabel rekap atau grafik awal</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-orange-800">6.2</td><td class="py-1.5 px-3 font-semibold">Validasi output</td><td class="py-1.5 px-3">Memeriksa kewajaran angka hasil rekap</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-orange-800">6.3</td><td class="py-1.5 px-3 font-semibold">Interpretasi &amp; penjelasan</td><td class="py-1.5 px-3">Menulis narasi atau analisis singkat hasil statistik</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-orange-800">6.4</td><td class="py-1.5 px-3 font-semibold">Kendali pengungkapan</td><td class="py-1.5 px-3">Memastikan data individu terlindungi dan tidak terbuka</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-orange-800">6.5</td><td class="py-1.5 px-3 font-semibold">Finalisasi output</td><td class="py-1.5 px-3">Menyempurnakan laporan atau tabel akhir publikasi</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Fase 7 --}}
                        <div class="p-3.5 rounded-2xl border border-emerald-200/80 bg-emerald-50/20">
                            <span class="font-bold text-emerald-900 font-mono-tag text-xs block mb-2">Fase 7 — Disseminate (Diseminasi)</span>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-[11.5px] min-w-[420px]">
                                    <thead class="text-zinc-400 font-mono-tag border-b border-emerald-100"><tr><th class="py-1 px-2 w-16">Kode</th><th class="py-1 px-3">Sub-proses</th><th class="py-1 px-3">Contoh Kegiatan Magang</th></tr></thead>
                                    <tbody class="divide-y divide-emerald-100/60">
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-emerald-800">7.1</td><td class="py-1.5 px-3 font-semibold">Memperbarui sistem output</td><td class="py-1.5 px-3">Mengunggah atau memperbarui data pada portal sistem</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-emerald-800">7.2</td><td class="py-1.5 px-3 font-semibold">Membuat produk diseminasi</td><td class="py-1.5 px-3">Membuat buku panduan, laporan, infografis, publikasi</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-emerald-800">7.3</td><td class="py-1.5 px-3 font-semibold">Mengelola rilis</td><td class="py-1.5 px-3">Menjadwalkan dan menyerahkan produk ke unit terkait</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-emerald-800">7.4</td><td class="py-1.5 px-3 font-semibold">Promosi produk</td><td class="py-1.5 px-3">Mensosialisasikan hasil atau tool kepada pengguna</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-emerald-800">7.5</td><td class="py-1.5 px-3 font-semibold">Layanan pengguna</td><td class="py-1.5 px-3">Menjawab pertanyaan dan memandu pengguna</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Fase 8 --}}
                        <div class="p-3.5 rounded-2xl border border-rose-200/80 bg-rose-50/20">
                            <span class="font-bold text-rose-900 font-mono-tag text-xs block mb-2">Fase 8 — Evaluate (Evaluasi)</span>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-[11.5px] min-w-[420px]">
                                    <thead class="text-zinc-400 font-mono-tag border-b border-rose-100"><tr><th class="py-1 px-2 w-16">Kode</th><th class="py-1 px-3">Sub-proses</th><th class="py-1 px-3">Contoh Kegiatan Magang</th></tr></thead>
                                    <tbody class="divide-y divide-rose-100/60">
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-rose-800">8.1</td><td class="py-1.5 px-3 font-semibold">Mengumpulkan masukan evaluasi</td><td class="py-1.5 px-3">Mencatat kendala dan saran dari pengguna atau pembimbing</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-rose-800">8.2</td><td class="py-1.5 px-3 font-semibold">Melaksanakan evaluasi</td><td class="py-1.5 px-3">Menilai kekurangan alur kerja atau tool yang digunakan</td></tr>
                                        <tr><td class="py-1.5 px-2 font-mono-tag font-bold text-rose-800">8.3</td><td class="py-1.5 px-3 font-semibold">Rencana tindak lanjut</td><td class="py-1.5 px-3">Menyusun perbaikan untuk periode berikutnya</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- 4. Pemetaan Kegiatan Magang ke GSBPM (Usulan) --}}
                <section id="guide-sec-4" class="space-y-3">
                    <div class="flex items-center gap-2 border-b border-zinc-200 pb-2">
                        <span class="w-6 h-6 rounded-lg bg-zinc-950 text-white font-mono-tag font-bold text-xs flex items-center justify-center">4</span>
                        <h3 class="text-sm font-bold text-zinc-950 uppercase tracking-wide">Pemetaan Kegiatan Magang ke GSBPM (Usulan)</h3>
                    </div>
                    <p class="text-[11.5px] text-zinc-600">
                        Berikut usulan pemetaan pekerjaan magang di BPS Kota Surabaya ke fase GSBPM. Sesuaikan dengan kegiatan riil yang Anda kerjakan:
                    </p>

                    <div class="overflow-x-auto rounded-2xl border border-zinc-200/80 shadow-xs">
                        <table class="w-full text-left border-collapse min-w-[580px]">
                            <thead class="bg-zinc-50 font-mono-tag text-[11px] text-zinc-500 uppercase border-b border-zinc-200">
                                <tr>
                                    <th class="py-2.5 px-4">Kegiatan Magang</th>
                                    <th class="py-2.5 px-3">Fase</th>
                                    <th class="py-2.5 px-3">Sub-proses</th>
                                    <th class="py-2.5 px-4">Alasan Pemetaan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100 text-zinc-800 text-[11.5px]">
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4 font-semibold">Menyusun rancangan alur dan fitur SIMOVIE</td><td class="py-2.5 px-3 font-bold text-blue-700">2 Design</td><td class="py-2.5 px-3 font-mono-tag">2.6</td><td class="py-2.5 px-4 text-zinc-600">Merancang sistem produksi dan alur kerja</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4 font-semibold">Membangun atau mengembangkan SIMOVIE</td><td class="py-2.5 px-3 font-bold text-indigo-700">3 Build</td><td class="py-2.5 px-3 font-mono-tag">3.2, 3.4</td><td class="py-2.5 px-4 text-zinc-600">Membangun komponen pengolahan dan alur kerja</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4 font-semibold">Menguji dan menyempurnakan SIMOVIE</td><td class="py-2.5 px-3 font-bold text-indigo-700">3 Build</td><td class="py-2.5 px-3 font-mono-tag">3.5, 3.6, 3.7</td><td class="py-2.5 px-4 text-zinc-600">Uji coba sistem dan finalisasi</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4 font-semibold">Membangun Kompiler Progress Sensus SE2026</td><td class="py-2.5 px-3 font-bold text-indigo-700">3 Build</td><td class="py-2.5 px-3 font-mono-tag">3.2, 3.4</td><td class="py-2.5 px-4 text-zinc-600">Membangun komponen kompilasi progres</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4 font-semibold">Menggunakan Kompiler untuk merekap progres sensus</td><td class="py-2.5 px-3 font-bold text-amber-700">5 Process / 4 Collect</td><td class="py-2.5 px-3 font-mono-tag">5.1, 5.7 / 4.3</td><td class="py-2.5 px-4 text-zinc-600">Menggabungkan data &amp; merekap; atau memantau progres lapangan</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4 font-semibold">Menyusun buku panduan pengguna (user manual) SIMOVIE / Kompiler</td><td class="py-2.5 px-3 font-bold text-emerald-700">7 Disseminate</td><td class="py-2.5 px-3 font-mono-tag">7.2, 7.5</td><td class="py-2.5 px-4 text-zinc-600">Produk diseminasi dan dukungan pengguna</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4 font-semibold">Menyusun laporan inovasi SIMOVIE dan Kompiler</td><td class="py-2.5 px-3 font-bold text-emerald-700">7 Disseminate / 8 Evaluate</td><td class="py-2.5 px-3 font-mono-tag">7.2, 8.2</td><td class="py-2.5 px-4 text-zinc-600">Dokumentasi hasil dan evaluasi inovasi</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4 font-semibold">Entri data UMKM 6 SE2026</td><td class="py-2.5 px-3 font-bold text-cyan-700">4 Collect</td><td class="py-2.5 px-3 font-mono-tag">4.3</td><td class="py-2.5 px-4 text-zinc-600">Menjalankan pengumpulan atau entri data</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4 font-semibold">Menyesuaikan nama alokasi &amp; petugas dengan daftar SLS</td><td class="py-2.5 px-3 font-bold text-amber-700">5 Process</td><td class="py-2.5 px-3 font-mono-tag">5.3, 5.4</td><td class="py-2.5 px-4 text-zinc-600">Validasi terhadap acuan dan perbaikan data</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4 font-semibold">Merapikan file alokasi UMKM 6 (satu baris per data) &amp; penamaan standar</td><td class="py-2.5 px-3 font-bold text-amber-700">5 Process</td><td class="py-2.5 px-3 font-mono-tag">5.8</td><td class="py-2.5 px-4 text-zinc-600">Finalisasi file data bersih</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4 font-semibold">Mengganti isian file dengan data file lain sesuai format acuan</td><td class="py-2.5 px-3 font-bold text-amber-700">5 Process</td><td class="py-2.5 px-3 font-mono-tag">5.1, 5.4</td><td class="py-2.5 px-4 text-zinc-600">Integrasi dan perbaikan konsistensi data</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4 font-semibold">Menyiapkan daftar SLS dan alokasi petugas</td><td class="py-2.5 px-3 font-bold text-cyan-700">4 Collect</td><td class="py-2.5 px-3 font-mono-tag">4.2</td><td class="py-2.5 px-4 text-zinc-600">Persiapan pengumpulan lapangan</td></tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                {{-- 5. Cara Mengisi Logbook dengan GSBPM --}}
                <section id="guide-sec-5" class="space-y-3">
                    <div class="flex items-center gap-2 border-b border-zinc-200 pb-2">
                        <span class="w-6 h-6 rounded-lg bg-zinc-950 text-white font-mono-tag font-bold text-xs flex items-center justify-center">5</span>
                        <h3 class="text-sm font-bold text-zinc-950 uppercase tracking-wide">Cara Mengisi Logbook dengan GSBPM</h3>
                    </div>

                    <div class="space-y-2">
                        <h4 class="font-bold text-zinc-900 font-mono-tag text-xs">// 5 PRINSIP DASAR</h4>
                        <ol class="list-decimal list-inside space-y-1 text-zinc-600 pl-1">
                            <li><strong>Satu kegiatan, satu fase utama:</strong> Bila kegiatan mencakup beberapa fase, pilih yang paling dominan lalu sebutkan fase lain di uraian.</li>
                            <li><strong>Gunakan kata kerja operasional:</strong> Contoh: <em>menyusun, memvalidasi, merekap, menguji, mengunggah, mendokumentasikan</em>.</li>
                            <li><strong>Sebut objek dan volumenya:</strong> Contoh: <em>"...sebanyak 120 baris"</em>, <em>"...untuk 3 SLS"</em>.</li>
                            <li><strong>Sebut hasil atau output nyata:</strong> Contoh: <em>"...menghasilkan file Excel final siap pakai"</em>.</li>
                            <li><strong>Cantumkan kendala &amp; tindak lanjut:</strong> Agar fase <em>Evaluate</em> terlihat pada portofolio Anda.</li>
                        </ol>
                    </div>

                    <div class="p-3.5 bg-zinc-50 rounded-2xl border border-zinc-200 space-y-2">
                        <span class="font-bold text-zinc-900 font-mono-tag text-xs block">// RUMUS KALIMAT URAIAN KEGIATAN</span>
                        <div class="p-2.5 bg-white border border-zinc-200 rounded-xl font-mono-tag text-xs text-blue-900">
                            <strong>[Kata kerja]</strong> + <strong>[objek]</strong> + <strong>[tujuan atau dasar]</strong> + <strong>[hasil/output]</strong>
                        </div>
                        <p class="text-[11.5px] text-zinc-600">
                            <strong>Contoh:</strong> <em>"Memvalidasi nama petugas dan alokasi pada file UMKM 6 terhadap daftar SLS agar sesuai acuan resmi, menghasilkan file alokasi yang konsisten."</em>
                        </p>
                    </div>
                </section>

                {{-- 6. Contoh Entri Logbook --}}
                <section id="guide-sec-6" class="space-y-3">
                    <div class="flex items-center gap-2 border-b border-zinc-200 pb-2">
                        <span class="w-6 h-6 rounded-lg bg-zinc-950 text-white font-mono-tag font-bold text-xs flex items-center justify-center">6</span>
                        <h3 class="text-sm font-bold text-zinc-950 uppercase tracking-wide">Contoh Entri Logbook</h3>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-zinc-200/80 shadow-xs">
                        <table class="w-full text-left border-collapse min-w-[580px]">
                            <thead class="bg-zinc-50 font-mono-tag text-[11px] text-zinc-500 uppercase border-b border-zinc-200">
                                <tr>
                                    <th class="py-2.5 px-3">Hari</th>
                                    <th class="py-2.5 px-4">Kegiatan</th>
                                    <th class="py-2.5 px-3">Fase &amp; Sub-proses</th>
                                    <th class="py-2.5 px-3">Output</th>
                                    <th class="py-2.5 px-3">Kendala / Tindak Lanjut</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100 text-zinc-800 text-[11.5px]">
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-3 font-mono-tag font-bold">Senin</td><td class="py-2.5 px-4">Memvalidasi &amp; menyesuaikan nama alokasi serta petugas file UMKM 6 SE2026 terhadap daftar SLS</td><td class="py-2.5 px-3 font-bold text-amber-700">5.3 Review &amp; validasi</td><td class="py-2.5 px-3">File alokasi UMKM 6 yang konsisten</td><td class="py-2.5 px-3 text-zinc-500">Beberapa nama berbeda penulisan; diseragamkan mengikuti acuan</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-3 font-mono-tag font-bold">Selasa</td><td class="py-2.5 px-4">Melakukan entri data UMKM 6 SE2026 untuk baris dengan penginput sesuai pembagian tugas</td><td class="py-2.5 px-3 font-bold text-cyan-700">4.3 Menjalankan pengumpulan</td><td class="py-2.5 px-3">Data tersentri pada file kerja</td><td class="py-2.5 px-3 text-zinc-500">Perlu pengecekan ulang kolom wilayah</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-3 font-mono-tag font-bold">Rabu</td><td class="py-2.5 px-4">Merapikan file menjadi satu baris per data dan menyimpannya dengan penamaan sesuai ketentuan</td><td class="py-2.5 px-3 font-bold text-amber-700">5.8 Finalisasi file data</td><td class="py-2.5 px-3">File akhir siap dikirim</td><td class="py-2.5 px-3 text-zinc-500">Tidak ada kendala</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-3 font-mono-tag font-bold">Kamis</td><td class="py-2.5 px-4">Menguji alur Kompiler Progress Sensus SE2026 dengan data contoh</td><td class="py-2.5 px-3 font-bold text-indigo-700">3.5 Menguji sistem produksi</td><td class="py-2.5 px-3">Catatan hasil uji &amp; formula</td><td class="py-2.5 px-3 text-zinc-500">Ditemukan selisih rekap; formula diperbaiki</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-3 font-mono-tag font-bold">Jumat</td><td class="py-2.5 px-4">Menyusun buku panduan pengguna SIMOVIE dalam dokumen Word</td><td class="py-2.5 px-3 font-bold text-emerald-700">7.2 Produk diseminasi</td><td class="py-2.5 px-3">Draf user manual</td><td class="py-2.5 px-3 text-zinc-500">Menunggu review pembimbing</td></tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                {{-- 7. Daftar Kata Kerja Operasional per Fase --}}
                <section id="guide-sec-7" class="space-y-3">
                    <div class="flex items-center gap-2 border-b border-zinc-200 pb-2">
                        <span class="w-6 h-6 rounded-lg bg-zinc-950 text-white font-mono-tag font-bold text-xs flex items-center justify-center">7</span>
                        <h3 class="text-sm font-bold text-zinc-950 uppercase tracking-wide">Daftar Kata Kerja Operasional per Fase</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-[11.5px]">
                        <div class="p-3 bg-zinc-50 rounded-xl border border-zinc-200/70">
                            <span class="font-bold text-purple-800 font-mono-tag block mb-1">1. Specify Needs</span>
                            <span class="text-zinc-600">mengidentifikasi, mengonsultasikan, mendefinisikan, mengecek ketersediaan data.</span>
                        </div>
                        <div class="p-3 bg-zinc-50 rounded-xl border border-zinc-200/70">
                            <span class="font-bold text-blue-800 font-mono-tag block mb-1">2. Design</span>
                            <span class="text-zinc-600">merancang, menyusun rancangan, mendesain alur, menentukan variabel output.</span>
                        </div>
                        <div class="p-3 bg-zinc-50 rounded-xl border border-zinc-200/70">
                            <span class="font-bold text-indigo-800 font-mono-tag block mb-1">3. Build</span>
                            <span class="text-zinc-600">membangun, mengembangkan, memprogram, menguji coba, menyempurnakan tool.</span>
                        </div>
                        <div class="p-3 bg-zinc-50 rounded-xl border border-zinc-200/70">
                            <span class="font-bold text-cyan-800 font-mono-tag block mb-1">4. Collect</span>
                            <span class="text-zinc-600">menyiapkan petugas, mengalokasikan wilayah, mencacah, menginput/entri data, memantau progres.</span>
                        </div>
                        <div class="p-3 bg-zinc-50 rounded-xl border border-zinc-200/70">
                            <span class="font-bold text-amber-800 font-mono-tag block mb-1">5. Process</span>
                            <span class="text-zinc-600">mengintegrasikan, mengkodekan, memvalidasi, mengedit/imputasi, merekap agregat, memfinalisasi file.</span>
                        </div>
                        <div class="p-3 bg-zinc-50 rounded-xl border border-zinc-200/70">
                            <span class="font-bold text-orange-800 font-mono-tag block mb-1">6. Analyse</span>
                            <span class="text-zinc-600">menganalisis, menginterpretasikan, membuat grafik/tabel, memvalidasi output angka.</span>
                        </div>
                        <div class="p-3 bg-zinc-50 rounded-xl border border-zinc-200/70">
                            <span class="font-bold text-emerald-800 font-mono-tag block mb-1">7. Disseminate</span>
                            <span class="text-zinc-600">mempublikasikan, menyusun user manual, mensosialisasikan, melayani pengguna data.</span>
                        </div>
                        <div class="p-3 bg-zinc-50 rounded-xl border border-zinc-200/70">
                            <span class="font-bold text-rose-800 font-mono-tag block mb-1">8. Evaluate</span>
                            <span class="text-zinc-600">mengevaluasi, mencatat masukan pengguna, menyusun rekomendasi perbaikan.</span>
                        </div>
                    </div>
                </section>

                {{-- 8. Proses Lintas Fase & 9. Kesalahan Umum --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- 8 --}}
                    <div class="p-4 bg-zinc-50 rounded-2xl border border-zinc-200 space-y-2">
                        <span class="font-bold text-zinc-950 font-mono-tag text-xs block">// 8. PROSES LINTAS FASE</span>
                        <p class="text-[11.5px] text-zinc-600 leading-relaxed">
                            Beberapa kegiatan berjalan di sepanjang proses produksi statistik:
                        </p>
                        <ul class="list-disc list-inside space-y-1 text-[11.5px] text-zinc-600">
                            <li><strong>Manajemen kualitas:</strong> mengecek ulang hasil rekap, menyusun daftar temuan kesalahan.</li>
                            <li><strong>Manajemen metadata:</strong> menjelaskan arti kolom dan istilah pada file atau manual.</li>
                            <li><strong>Manajemen data:</strong> menyimpan, menamai, dan mengarsipkan file sesuai ketentuan, menjaga kerahasiaan data.</li>
                            <li><strong>Manajemen alur kerja:</strong> menyusun SOP atau langkah kerja yang dapat direplikasi.</li>
                        </ul>
                    </div>

                    {{-- 9 --}}
                    <div class="p-4 bg-amber-50/50 rounded-2xl border border-amber-200 space-y-2">
                        <span class="font-bold text-amber-950 font-mono-tag text-xs block">// 9. KESALAHAN UMUM &amp; TIPS</span>
                        <ul class="space-y-1.5 text-[11.5px] text-amber-950">
                            <li>&bull; <strong>Terlalu umum:</strong> Hindari <em>"mengerjakan tugas pembimbing"</em>. Sebutkan nama tugas, objek, dan hasilnya.</li>
                            <li>&bull; <strong>Salah fase:</strong> Entri data lapangan adalah <em>Collect (4.3)</em>, bukan Process. Merapikan dan memvalidasi data baru termasuk <em>Process (5)</em>.</li>
                            <li>&bull; <strong>Tanpa output:</strong> Usahakan setiap entri memiliki bukti hasil nyata (file, rekap, draf manual).</li>
                            <li>&bull; <strong>Data sensitif:</strong> Jangan pernah menulis identitas/responden individual di logbook; cukup sebut jumlah baris &amp; agregatnya.</li>
                        </ul>
                    </div>
                </div>

                {{-- 10. Ringkasan Cepat untuk Logbook --}}
                <section id="guide-sec-10" class="space-y-3">
                    <div class="flex items-center gap-2 border-b border-zinc-200 pb-2">
                        <span class="w-6 h-6 rounded-lg bg-zinc-950 text-white font-mono-tag font-bold text-xs flex items-center justify-center">10</span>
                        <h3 class="text-sm font-bold text-zinc-950 uppercase tracking-wide">Ringkasan Cepat Penentuan Fase</h3>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-zinc-200/80 shadow-xs">
                        <table class="w-full text-left border-collapse min-w-[450px]">
                            <thead class="bg-zinc-50 font-mono-tag text-[11px] text-zinc-500 uppercase border-b border-zinc-200">
                                <tr>
                                    <th class="py-2.5 px-4">Jika Kegiatan Anda...</th>
                                    <th class="py-2.5 px-4">Maka Pilih Fase:</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100 text-zinc-800 text-[11.5px]">
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4">Memahami kebutuhan data dan menyusun usulan</td><td class="py-2.5 px-4 font-bold text-purple-700">1 Specify Needs</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4">Merancang alur, formulir, atau sistem aplikasi</td><td class="py-2.5 px-4 font-bold text-blue-700">2 Design</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4">Membuat, memprogram, atau menguji coba tool/aplikasi</td><td class="py-2.5 px-4 font-bold text-indigo-700">3 Build</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4">Menyiapkan petugas atau menginput data mentah</td><td class="py-2.5 px-4 font-bold text-cyan-700">4 Collect</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4">Memvalidasi, merapikan, mengkodekan, dan merekap data</td><td class="py-2.5 px-4 font-bold text-amber-700">5 Process</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4">Membuat tabel publikasi, grafik visual, atau narasi interpretasi</td><td class="py-2.5 px-4 font-bold text-orange-700">6 Analyse</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4">Membuat buku manual, laporan inovasi, atau materi sosialisasi</td><td class="py-2.5 px-4 font-bold text-emerald-700">7 Disseminate</td></tr>
                                <tr class="hover:bg-zinc-50/60"><td class="py-2.5 px-4">Mencatat kendala dan merencanakan tindak lanjut perbaikan</td><td class="py-2.5 px-4 font-bold text-rose-700">8 Evaluate</td></tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                {{-- 11. Checklist Sebelum Mengumpulkan Logbook --}}
                <section id="guide-sec-11" class="space-y-3">
                    <div class="flex items-center gap-2 border-b border-zinc-200 pb-2">
                        <span class="w-6 h-6 rounded-lg bg-emerald-600 text-white font-mono-tag font-bold text-xs flex items-center justify-center">11</span>
                        <h3 class="text-sm font-bold text-zinc-950 uppercase tracking-wide">Checklist Sebelum Mengirim / Mengumpulkan Logbook</h3>
                    </div>

                    <div class="p-4 bg-emerald-50/40 border border-emerald-200 rounded-2xl space-y-2 text-[11.5px] text-zinc-800">
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input type="checkbox" checked disabled class="mt-0.5 rounded text-emerald-600 focus:ring-0">
                            <span>Setiap entri memiliki tanggal dan uraian kegiatan yang jelas dan spesifik.</span>
                        </label>
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input type="checkbox" checked disabled class="mt-0.5 rounded text-emerald-600 focus:ring-0">
                            <span>Setiap entri mencantumkan fase GSBPM (dan sub-proses bila memungkinkan).</span>
                        </label>
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input type="checkbox" checked disabled class="mt-0.5 rounded text-emerald-600 focus:ring-0">
                            <span>Kata kerja operasional digunakan dan objek pekerjaan disebutkan secara lugas.</span>
                        </label>
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input type="checkbox" checked disabled class="mt-0.5 rounded text-emerald-600 focus:ring-0">
                            <span>Terdapat output atau bukti hasil nyata yang dapat ditunjukkan (link dokumen pendukung).</span>
                        </label>
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input type="checkbox" checked disabled class="mt-0.5 rounded text-emerald-600 focus:ring-0">
                            <span>Volume pekerjaan dicantumkan (jumlah baris, dokumen, atau wilayah).</span>
                        </label>
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input type="checkbox" checked disabled class="mt-0.5 rounded text-emerald-600 focus:ring-0">
                            <span>Kendala dan tindak lanjut perbaikan ditulis bila ada (fase Evaluate).</span>
                        </label>
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input type="checkbox" checked disabled class="mt-0.5 rounded text-emerald-600 focus:ring-0">
                            <span>Tidak mengandung data rahasia responden individual.</span>
                        </label>
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input type="checkbox" checked disabled class="mt-0.5 rounded text-emerald-600 focus:ring-0">
                            <span>Format sesuai ketentuan BPS dan siap untuk diverifikasi oleh pembimbing lapangan.</span>
                        </label>
                    </div>
                </section>

            </div>

            {{-- Modal Footer Sticky --}}
            <div class="border-t border-zinc-100 p-3.5 sm:p-4 bg-zinc-50 flex items-center justify-between shrink-0">
                <span class="text-[11px] font-mono-tag text-zinc-400 hidden sm:inline">BPS Kota Surabaya &bull; Panduan GSBPM</span>
                <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                    <button type="button" 
                            @click="showGuideBookModal = false"
                            class="w-full sm:w-auto px-5 py-2 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-mono-tag font-semibold rounded-xl transition shadow-xs">
                        Tutup Panduan
                    </button>
                </div>
            </div>

        </div>
    </div>


    {{-- ========================================================================= --}}
    {{-- MODAL 1: BUAT LOGBOOK BARU (POP-UP ATRAKTIF & RINGAN)                      --}}
    {{-- ========================================================================= --}}
    <div x-show="showCreateModal" 
         x-cloak 
         @keydown.escape.window="showCreateModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-2.5 sm:p-6 overflow-y-auto bg-zinc-950/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="bg-white rounded-3xl border border-zinc-200/80 shadow-2xl max-w-2xl w-full max-h-[94vh] sm:max-h-[92vh] flex flex-col overflow-hidden"
             @click.outside="showCreateModal = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">

            {{-- Modal Header --}}
            <div class="border-b border-zinc-100 p-4 sm:p-7 flex items-start justify-between bg-zinc-50/50">
                <div>
                    <span class="text-[10px] font-mono-tag uppercase tracking-widest text-zinc-400 block mb-1">// NEW ENTRY PROTOCOL</span>
                    <h2 class="text-lg sm:text-xl font-extrabold text-zinc-950 tracking-tight">Buat Entri Logbook Baru</h2>
                    <p class="text-xs text-zinc-500 mt-0.5">Catat dokumentasi hasil kerja dan aktivitas harian magang.</p>
                </div>
                <button type="button" 
                        @click="showCreateModal = false"
                        class="w-8 h-8 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-500 hover:text-zinc-900 flex items-center justify-center transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Modal Body Form --}}
            <form action="{{ route('mahasiswa.logbook.store') }}" method="POST" x-ref="createForm" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div class="overflow-y-auto p-4 sm:p-7 space-y-4 text-xs">
                    
                    @if(!empty($sudahBuatHariIni))
                        <div class="p-3.5 bg-amber-50 border border-amber-200/80 rounded-2xl flex items-start gap-2.5 text-amber-900 text-xs">
                            <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <div class="leading-relaxed">
                                <strong>Catatan:</strong> Anda sudah tercatat membuat logbook untuk hari ini. Bila ingin menambahkan aktivitas lanjutan pada proyek yang sedang berjalan, silakan gunakan fitur <strong>"Lanjutkan Proyek"</strong>.
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Tim / Divisi --}}
                        <div>
                            <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider mb-1 font-mono-tag">Tugas dari Tim / Divisi</label>
                            <select name="tim" required class="w-full text-xs font-mono-tag border border-zinc-200 rounded-xl px-3 py-2.5 bg-zinc-50/60 focus:bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 transition">
                                <option value="">-- Pilih Tim / Divisi --</option>
                                @foreach(['Distribusi', 'ZI', 'PSS', 'POTIK', 'Sosial', 'TU', 'Neraca', 'Humas', 'IPDS', 'Umum', 'UKK'] as $t)
                                    <option value="{{ $t }}">{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Jenis GSBPM --}}
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider font-mono-tag">Jenis GSBPM</label>
                                <button type="button" @click="showGuideBookModal = true" class="text-[10px] text-blue-600 hover:underline font-mono-tag">Lihat Panduan &rarr;</button>
                            </div>
                            <select name="jenis_gsbpm" required class="w-full text-xs font-mono-tag border border-zinc-200 rounded-xl px-3 py-2.5 bg-zinc-50/60 focus:bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 transition">
                                <option value="">-- Pilih Fase GSBPM --</option>
                                @foreach(['Specify Needs', 'Design', 'Build', 'Collect', 'Process', 'Analyse', 'Disseminate', 'Evaluate'] as $g)
                                    <option value="{{ $g }}">{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tanggal --}}
                        <div>
                            <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider mb-1 font-mono-tag">Tanggal Pengerjaan</label>
                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                                   class="w-full text-xs font-mono-tag border border-zinc-200 rounded-xl px-3 py-2.5 bg-zinc-50/60 focus:bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 transition">
                        </div>

                        {{-- Jam Mulai & Selesai --}}
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider mb-1 font-mono-tag">Jam Mulai</label>
                                <input type="time" name="jam_mulai" value="08:00" required 
                                       class="w-full text-xs font-mono-tag border border-zinc-200 rounded-xl px-2.5 py-2.5 bg-zinc-50/60 focus:bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 transition">
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider font-mono-tag">Jam Selesai</label>
                                    <button type="button" @click="setNow($refs.createJamSelesai)" class="text-[10px] text-zinc-500 hover:text-zinc-950 font-mono-tag underline">Sekarang</button>
                                </div>
                                <input type="time" name="jam_selesai" x-ref="createJamSelesai"
                                       class="w-full text-xs font-mono-tag border border-zinc-200 rounded-xl px-2.5 py-2.5 bg-zinc-50/60 focus:bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 transition">
                            </div>
                        </div>
                    </div>

                    {{-- Nama Kegiatan --}}
                    <div>
                        <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider mb-1 font-mono-tag">Nama / Judul Kegiatan</label>
                        <input type="text" name="kegiatan" required placeholder="Contoh: Pengolahan dan Verifikasi Anomali Data Survei BPS..."
                               class="w-full text-xs border border-zinc-200 rounded-xl px-3.5 py-2.5 bg-zinc-50/60 focus:bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 transition placeholder:text-zinc-400">
                    </div>

                    {{-- Deskripsi Aktivitas --}}
                    <div>
                        <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider mb-1 font-mono-tag">Deskripsi Detail Pekerjaan</label>
                        <textarea name="deskripsi" rows="3" required placeholder="Jelaskan secara spesifik hasil analisis, metodologi tugas, kendala yang dihadapi, atau progres yang dicapai..."
                                  class="w-full text-xs border border-zinc-200 rounded-xl p-3.5 bg-zinc-50/60 focus:bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 transition resize-none leading-relaxed placeholder:text-zinc-400"></textarea>
                    </div>

                    {{-- Tautan Bukti Dokumen --}}
                    <div>
                        <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider mb-1 font-mono-tag">Tautan Bukti Dokumen (Google Drive / URL)</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-400">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            </span>
                            <input type="url" name="link_bukti" required placeholder="https://drive.google.com/..."
                                   class="w-full pl-9 pr-3.5 py-2.5 text-xs font-mono-tag border border-zinc-200 rounded-xl bg-zinc-50/60 focus:bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 transition placeholder:text-zinc-400">
                        </div>
                        <span class="text-[10.5px] text-zinc-400 mt-1 block">Tautan Google Drive atau link repositori dokumentasi output tugas.</span>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="border-t border-zinc-100 p-3.5 sm:p-5 bg-zinc-50/70 flex flex-col-reverse sm:flex-row items-center justify-between gap-2.5 sm:gap-3">
                    <button type="button" 
                            @click="showCreateModal = false"
                            class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-zinc-200 bg-white hover:bg-zinc-100 text-zinc-700 text-xs font-mono-tag font-semibold transition text-center">
                        Batal
                    </button>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button type="submit" 
                                name="is_selesai" 
                                value="0"
                                class="flex-1 sm:flex-none px-4 py-2.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200 text-xs font-mono-tag font-semibold transition">
                            Simpan Draf
                        </button>
                        <button type="submit" 
                                name="is_selesai" 
                                value="1"
                                @click="autoFillSelesai($refs.createForm)"
                                class="flex-1 sm:flex-none px-4 sm:px-5 py-2.5 rounded-xl bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-mono-tag font-bold transition shadow-sm flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Kirim &amp; Selesaikan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    {{-- ========================================================================= --}}
    {{-- MODAL 2: LANJUTKAN PROYEK (POP-UP DAFTAR PROYEK & FORM INTEGRASI LANGSUNG) --}}
    {{-- ========================================================================= --}}
    <div x-show="showLanjutkanModal" 
         x-cloak 
         @keydown.escape.window="showLanjutkanModal = false; selectedDraft = null;"
         class="fixed inset-0 z-50 flex items-center justify-center p-2.5 sm:p-6 overflow-y-auto bg-zinc-950/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="bg-white rounded-3xl border border-zinc-200/80 shadow-2xl max-w-2xl w-full max-h-[94vh] sm:max-h-[92vh] flex flex-col overflow-hidden"
             @click.outside="showLanjutkanModal = false; selectedDraft = null;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">

            {{-- Modal Header --}}
            <div class="border-b border-zinc-100 p-4 sm:p-7 flex items-start justify-between bg-zinc-50/50">
                <div>
                    <span class="text-[10px] font-mono-tag uppercase tracking-widest text-zinc-400 block mb-1">// PROJECT CONTINUATION</span>
                    <h2 class="text-lg sm:text-xl font-extrabold text-zinc-950 tracking-tight" x-text="selectedDraft ? 'Lanjutkan Aktivitas Proyek' : 'Pilih Proyek Logbook'"></h2>
                    <p class="text-xs text-zinc-500 mt-0.5" x-text="selectedDraft ? 'Melanjutkan catatan progres proyek yang belum selesai.' : 'Pilih proyek atau pekerjaan draf yang ingin diteruskan hari ini.'"></p>
                </div>
                <button type="button" 
                        @click="showLanjutkanModal = false; selectedDraft = null;"
                        class="w-8 h-8 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-500 hover:text-zinc-900 flex items-center justify-center transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- VIEW A: DAFTAR DRAF PROYEK --}}
            <div x-show="!selectedDraft" class="flex-1 overflow-y-auto p-4 sm:p-7 space-y-4">
                @if($drafts->isEmpty())
                    <div class="py-12 px-4 text-center space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-zinc-900">Tidak Ada Proyek Draf Tertunda</h4>
                        <p class="text-xs text-zinc-500 max-w-sm mx-auto leading-relaxed">
                            Seluruh logbook Anda saat ini sudah berstatus selesai/terkirim. Anda dapat langsung membuat entri logbook baru.
                        </p>
                        <div class="pt-2">
                            <button type="button"
                                    @click="showLanjutkanModal = false; showCreateModal = true;"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-mono-tag font-semibold rounded-xl transition shadow-xs">
                                <span>+ Buat Logbook Baru Sekarang</span>
                            </button>
                        </div>
                    </div>
                @else
                    <div class="p-3 bg-zinc-50 border border-zinc-200/60 rounded-2xl text-xs text-zinc-600 font-mono-tag">
                        Terdapat <strong>{{ $drafts->count() }}</strong> proyek berstatus draf yang siap dilanjutkan.
                    </div>

                    <div class="space-y-3">
                        @foreach($drafts as $draft)
                            <div class="p-4 sm:p-5 rounded-2xl border border-zinc-200/80 hover:border-zinc-950/40 hover:bg-zinc-50/60 transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4 group">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 mb-1.5 font-mono-tag">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-zinc-100 text-zinc-800 border border-zinc-200 uppercase">
                                            {{ $draft->tim }}
                                        </span>
                                        @if($draft->jenis_gsbpm)
                                            <span class="text-[10px] text-zinc-400">GSBPM: {{ $draft->jenis_gsbpm }}</span>
                                        @endif
                                        <span class="text-[10px] text-amber-700 font-bold px-2 py-0.5 bg-amber-50 rounded-full border border-amber-200">
                                            Draf Berjalan
                                        </span>
                                    </div>
                                    <h3 class="text-sm font-bold text-zinc-950 group-hover:text-black transition">{{ $draft->kegiatan }}</h3>
                                    <p class="text-xs text-zinc-500 line-clamp-2 mt-1 leading-relaxed">{{ $draft->deskripsi }}</p>
                                    <div class="text-[11px] text-zinc-400 font-mono-tag mt-2">
                                        Dimulai: {{ \Carbon\Carbon::parse($draft->tanggal)->isoFormat('D MMM Y') }}
                                        @if($draft->jam_mulai)
                                            ({{ substr($draft->jam_mulai, 0, 5) }})
                                        @endif
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <button type="button"
                                            @click='selectedDraft = {{ json_encode([
                                                "id" => $draft->id,
                                                "kegiatan" => $draft->kegiatan,
                                                "tim" => $draft->tim,
                                                "jenis_gsbpm" => $draft->jenis_gsbpm,
                                                "deskripsi" => $draft->deskripsi,
                                                "link_bukti" => $draft->link_bukti,
                                            ]) }}'
                                            class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-mono-tag font-semibold rounded-xl transition shadow-xs active:scale-95 cursor-pointer w-full sm:w-auto justify-center">
                                        <span>Lanjutkan &rarr;</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- VIEW B: FORM LANJUTKAN PROYEK (LANGSUNG DI MODAL) --}}
            <div x-show="selectedDraft" class="flex flex-col flex-1 overflow-hidden">
                <form action="{{ route('mahasiswa.logbook.store') }}" method="POST" x-ref="lanjutkanForm" class="flex flex-col flex-1 overflow-hidden">
                    @csrf
                    <input type="hidden" name="parent_id" :value="selectedDraft ? selectedDraft.id : ''">
                    <input type="hidden" name="tim" :value="selectedDraft ? selectedDraft.tim : ''">
                    <input type="hidden" name="jenis_gsbpm" :value="selectedDraft ? selectedDraft.jenis_gsbpm : ''">

                    <div class="overflow-y-auto p-4 sm:p-7 space-y-4 text-xs">
                        {{-- Mode Banner --}}
                        <div class="p-3.5 sm:p-4 bg-zinc-50 border border-zinc-200/80 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-widest block mb-0.5">// PROYEK TERHUBUNG</span>
                                <h4 class="text-xs font-bold text-zinc-950" x-text="selectedDraft ? selectedDraft.kegiatan : ''"></h4>
                                <div class="flex items-center gap-2 mt-1.5 font-mono-tag">
                                    <span class="px-2 py-0.5 rounded text-[10px] bg-zinc-200 text-zinc-800" x-text="selectedDraft ? 'Tim ' + selectedDraft.tim : ''"></span>
                                    <span class="text-[10px] text-zinc-500" x-text="selectedDraft ? 'GSBPM: ' + selectedDraft.jenis_gsbpm : ''"></span>
                                </div>
                            </div>
                            <button type="button" 
                                    @click="selectedDraft = null"
                                    class="shrink-0 px-3 py-1.5 rounded-xl border border-zinc-200 bg-white hover:bg-zinc-100 text-zinc-700 text-[11px] font-mono-tag font-semibold transition text-center">
                                &larr; Pilih Proyek Lain
                            </button>
                        </div>

                        {{-- Judul Kegiatan (Readonly / Locked) --}}
                        <div>
                            <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider mb-1 font-mono-tag">Nama Kegiatan (Terkunci Sesuai Proyek)</label>
                            <input type="text" name="kegiatan" :value="selectedDraft ? selectedDraft.kegiatan : ''" readonly
                                   class="w-full text-xs font-semibold border border-zinc-200 rounded-xl px-3.5 py-2.5 bg-zinc-100 text-zinc-600 cursor-not-allowed">
                        </div>

                        {{-- Grid Tanggal & Jam Lanjutan --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider mb-1 font-mono-tag">Tanggal Pengerjaan Hari Ini</label>
                                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                                       class="w-full text-xs font-mono-tag border border-zinc-200 rounded-xl px-3 py-2.5 bg-zinc-50/60 focus:bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 transition">
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider mb-1 font-mono-tag">Jam Mulai</label>
                                    <input type="time" name="jam_mulai" value="08:00" required 
                                           class="w-full text-xs font-mono-tag border border-zinc-200 rounded-xl px-2.5 py-2.5 bg-zinc-50/60 focus:bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 transition">
                                </div>
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider font-mono-tag">Jam Selesai</label>
                                        <button type="button" @click="setNow($refs.lanjutkanJamSelesai)" class="text-[10px] text-zinc-500 hover:text-zinc-950 font-mono-tag underline">Sekarang</button>
                                    </div>
                                    <input type="time" name="jam_selesai" x-ref="lanjutkanJamSelesai"
                                           class="w-full text-xs font-mono-tag border border-zinc-200 rounded-xl px-2.5 py-2.5 bg-zinc-50/60 focus:bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 transition">
                                </div>
                            </div>
                        </div>

                        {{-- Deskripsi Aktivitas Lanjutan --}}
                        <div>
                            <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider mb-1 font-mono-tag">Deskripsi Progres Lanjutan Hari Ini</label>
                            <textarea name="deskripsi" rows="3" required placeholder="Jelaskan progres lanjutan yang dikerjakan hari ini untuk proyek ini..."
                                      class="w-full text-xs border border-zinc-200 rounded-xl p-3.5 bg-zinc-50/60 focus:bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 transition resize-none leading-relaxed placeholder:text-zinc-400"></textarea>
                        </div>

                        {{-- Tautan Bukti Dokumen --}}
                        <div>
                            <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider mb-1 font-mono-tag">Tautan Bukti Dokumen</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-400">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                </span>
                                <input type="url" name="link_bukti" :value="selectedDraft ? selectedDraft.link_bukti : ''" required placeholder="https://drive.google.com/..."
                                       class="w-full pl-9 pr-3.5 py-2.5 text-xs font-mono-tag border border-zinc-200 rounded-xl bg-zinc-50/60 focus:bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 transition placeholder:text-zinc-400">
                            </div>
                        </div>
                    </div>

                    {{-- Footer Form Lanjutan --}}
                    <div class="border-t border-zinc-100 p-3.5 sm:p-5 bg-zinc-50/70 flex flex-col-reverse sm:flex-row items-center justify-between gap-2.5 sm:gap-3">
                        <button type="button" 
                                @click="selectedDraft = null"
                                class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-zinc-200 bg-white hover:bg-zinc-100 text-zinc-700 text-xs font-mono-tag font-semibold transition text-center">
                            &larr; Kembali ke Daftar
                        </button>

                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <button type="submit" 
                                    name="is_selesai" 
                                    value="0"
                                    class="flex-1 sm:flex-none px-4 py-2.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200 text-xs font-mono-tag font-semibold transition">
                                Simpan Draf Lanjutan
                            </button>
                            <button type="submit" 
                                    name="is_selesai" 
                                    value="1"
                                    @click="autoFillSelesai($refs.lanjutkanForm)"
                                    class="flex-1 sm:flex-none px-4 sm:px-5 py-2.5 rounded-xl bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-mono-tag font-bold transition shadow-sm flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Kirim &amp; Selesaikan Proyek</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- ========================================================================= --}}
    {{-- MODAL 3: EDIT LOGBOOK (POP-UP MODAL EDIT DATA AKTIVITAS)                  --}}
    {{-- ========================================================================= --}}
    <div x-show="showEditModal" 
         x-cloak 
         @keydown.escape.window="showEditModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-2.5 sm:p-6 overflow-y-auto bg-zinc-950/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="bg-white rounded-3xl border border-zinc-200/80 shadow-2xl max-w-2xl w-full max-h-[94vh] sm:max-h-[92vh] flex flex-col overflow-hidden"
             @click.outside="showEditModal = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">

            {{-- Modal Header --}}
            <div class="border-b border-zinc-100 p-4 sm:p-7 flex items-start justify-between bg-zinc-50/50">
                <div>
                    <span class="text-[10px] font-mono-tag uppercase tracking-widest text-zinc-400 block mb-1">// EDIT ENTRY PROTOCOL</span>
                    <h2 class="text-lg sm:text-xl font-extrabold text-zinc-950 tracking-tight">Edit Catatan Logbook</h2>
                    <p class="text-xs text-zinc-500 mt-0.5">Perbarui rincian atau selesaikan draf penugasan ini.</p>
                </div>
                <button type="button" 
                        @click="showEditModal = false"
                        class="w-8 h-8 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-500 hover:text-zinc-900 flex items-center justify-center transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Modal Body Form --}}
            <form :action="editData.updateUrl" method="POST" x-ref="editForm" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                @method('PUT')
                <div class="overflow-y-auto p-4 sm:p-7 space-y-4 text-xs">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Tim / Divisi --}}
                        <div>
                            <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider mb-1 font-mono-tag">Tugas dari Tim / Divisi</label>
                            <select name="tim" x-model="editData.tim" required class="w-full text-xs font-mono-tag border border-zinc-200 rounded-xl px-3 py-2.5 bg-zinc-50/60 focus:bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 transition">
                                <option value="">-- Pilih Tim / Divisi --</option>
                                @foreach(['Distribusi', 'ZI', 'PSS', 'POTIK', 'Sosial', 'TU', 'Neraca', 'Humas', 'IPDS', 'Umum', 'UKK'] as $t)
                                    <option value="{{ $t }}">{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Jenis GSBPM --}}
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider font-mono-tag">Jenis GSBPM</label>
                                <button type="button" @click="showGuideBookModal = true" class="text-[10px] text-blue-600 hover:underline font-mono-tag">Lihat Panduan &rarr;</button>
                            </div>
                            <select name="jenis_gsbpm" x-model="editData.jenis_gsbpm" required class="w-full text-xs font-mono-tag border border-zinc-200 rounded-xl px-3 py-2.5 bg-zinc-50/60 focus:bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 transition">
                                <option value="">-- Pilih Fase GSBPM --</option>
                                @foreach(['Specify Needs', 'Design', 'Build', 'Collect', 'Process', 'Analyse', 'Disseminate', 'Evaluate'] as $g)
                                    <option value="{{ $g }}">{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tanggal --}}
                        <div>
                            <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider mb-1 font-mono-tag">Tanggal Pengerjaan</label>
                            <input type="date" name="tanggal" x-model="editData.tanggal" required
                                   class="w-full text-xs font-mono-tag border border-zinc-200 rounded-xl px-3 py-2.5 bg-zinc-50/60 focus:bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 transition">
                        </div>

                        {{-- Jam Mulai & Selesai --}}
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider mb-1 font-mono-tag">Jam Mulai</label>
                                <input type="time" name="jam_mulai" x-model="editData.jam_mulai" required 
                                       class="w-full text-xs font-mono-tag border border-zinc-200 rounded-xl px-2.5 py-2.5 bg-zinc-50/60 focus:bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 transition">
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider font-mono-tag">Jam Selesai</label>
                                    <button type="button" @click="setNow($refs.editJamSelesai); editData.jam_selesai = $refs.editJamSelesai.value;" class="text-[10px] text-zinc-500 hover:text-zinc-950 font-mono-tag underline">Sekarang</button>
                                </div>
                                <input type="time" name="jam_selesai" x-model="editData.jam_selesai" x-ref="editJamSelesai"
                                       class="w-full text-xs font-mono-tag border border-zinc-200 rounded-xl px-2.5 py-2.5 bg-zinc-50/60 focus:bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 transition">
                            </div>
                        </div>
                    </div>

                    {{-- Nama Kegiatan --}}
                    <div>
                        <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider mb-1 font-mono-tag">Nama / Judul Kegiatan</label>
                        <input type="text" name="kegiatan" x-model="editData.kegiatan" required placeholder="Contoh: Pengolahan dan Verifikasi Anomali Data Survei BPS..."
                               class="w-full text-xs border border-zinc-200 rounded-xl px-3.5 py-2.5 bg-zinc-50/60 focus:bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 transition placeholder:text-zinc-400">
                    </div>

                    {{-- Deskripsi Aktivitas --}}
                    <div>
                        <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider mb-1 font-mono-tag">Deskripsi Detail Pekerjaan</label>
                        <textarea name="deskripsi" x-model="editData.deskripsi" rows="3" required placeholder="Jelaskan secara spesifik hasil analisis, metodologi tugas, kendala yang dihadapi, atau progres yang dicapai..."
                                  class="w-full text-xs border border-zinc-200 rounded-xl p-3.5 bg-zinc-50/60 focus:bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 transition resize-none leading-relaxed placeholder:text-zinc-400"></textarea>
                    </div>

                    {{-- Tautan Bukti Dokumen --}}
                    <div>
                        <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider mb-1 font-mono-tag">Tautan Bukti Dokumen</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-400">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            </span>
                            <input type="url" name="link_bukti" x-model="editData.link_bukti" required placeholder="https://drive.google.com/..."
                                   class="w-full pl-9 pr-3.5 py-2.5 text-xs font-mono-tag border border-zinc-200 rounded-xl bg-zinc-50/60 focus:bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 transition placeholder:text-zinc-400">
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="border-t border-zinc-100 p-3.5 sm:p-5 bg-zinc-50/70 flex flex-col-reverse sm:flex-row items-center justify-between gap-2.5 sm:gap-3">
                    <button type="button" 
                            @click="showEditModal = false"
                            class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-zinc-200 bg-white hover:bg-zinc-100 text-zinc-700 text-xs font-mono-tag font-semibold transition text-center">
                        Batal
                    </button>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button type="submit" 
                                name="is_selesai" 
                                value="0"
                                class="flex-1 sm:flex-none px-4 py-2.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200 text-xs font-mono-tag font-semibold transition">
                            Simpan Draf
                        </button>
                        <button type="submit" 
                                name="is_selesai" 
                                value="1"
                                @click="autoFillSelesai($refs.editForm)"
                                class="flex-1 sm:flex-none px-4 sm:px-5 py-2.5 rounded-xl bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-mono-tag font-bold transition shadow-sm flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan &amp; Selesaikan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
