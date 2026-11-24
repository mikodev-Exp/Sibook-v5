@extends('layouts.role', ['role' => 'mahasiswa'])

@section('title', 'Logbook Kegiatan')

@section('content')
<div x-data="{
    showCreateModal: false,
    showLanjutkanModal: false,
    showEditModal: false,
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
}" class="max-w-6xl mx-auto space-y-8">

    {{-- Alerts & Notifications --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between gap-3 text-xs font-mono-tag shadow-xs">
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
        <div x-data="{ show: true }" x-show="show" x-transition class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center justify-between gap-3 text-xs font-mono-tag shadow-xs">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" @click="show = false" class="text-rose-500 hover:text-rose-700">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- Header & Aksi (Studio Header) --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-mono-tag text-zinc-400 uppercase tracking-widest mb-1.5">
                <span>ACTIVITY ARCHIVE</span>
            </div>
            <h1 class="text-3xl font-extrabold text-zinc-950 tracking-tight">Logbook Kegiatan Harian</h1>
            <p class="text-xs text-zinc-500 mt-1">Dokumentasi portofolio kerja dan rekam aktivitas penugasan statistik.</p>
        </div>

        <div class="flex items-center gap-3">
            {{-- Tombol Lanjutkan Proyek (Buka Pop-Up Modal) --}}
            <button type="button"
                    @click="showLanjutkanModal = true; selectedDraft = null;" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-zinc-200 bg-white hover:bg-zinc-50 text-zinc-800 text-xs font-mono-tag font-semibold transition shadow-xs hover:shadow-sm active:scale-95 cursor-pointer">
                <svg class="w-3.5 h-3.5 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-mono-tag font-semibold transition shadow-sm hover:shadow active:scale-95 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span>BUAT LOGBOOK BARU</span>
            </button>
        </div>
    </div>

    {{-- Stat Chips --}}
    <div class="flex flex-wrap items-center gap-2.5 font-mono-tag text-xs">
        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white text-zinc-800 border border-zinc-200 shadow-xs">
            <span class="font-bold text-zinc-950">{{ $totalLogbook }}</span> TOTAL AKTIVITAS
        </span>
        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
            <span class="font-bold">{{ $selesai }}</span> SELESAI / APPROVED
        </span>
        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
            <span class="font-bold">{{ $belumSelesai }}</span> DRAF / BERJALAN
        </span>
    </div>

    {{-- Tabel Riwayat Logbook (Gallery Ledger) --}}
    <div id="mahasiswa-logbook-container"
         data-async-container
         @click="SiBookAsync.handleClick($event, 'mahasiswa-logbook-container')"
         class="bg-white rounded-3xl shadow-sm border border-zinc-200/80 overflow-hidden flex flex-col min-h-[520px]">
        
        <div class="p-6 sm:p-8 border-b border-zinc-100 flex justify-between items-center">
            <div>
                <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-widest block mb-0.5">LEDGER RECORDS</span>
                <h2 class="text-base font-extrabold text-zinc-950 tracking-tight">Catatan Aktivitas Karya</h2>
            </div>
            <span class="text-xs font-mono-tag text-zinc-400">CHRONOLOGICAL ORDER</span>
        </div>

        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left border-collapse text-xs font-mono-tag">
                <thead>
                    <tr class="border-b border-zinc-100 text-zinc-400 uppercase tracking-wider bg-zinc-50/50">
                        <th class="py-3.5 px-6">Tanggal &amp; Waktu</th>
                        <th class="py-3.5 px-4">Kegiatan &amp; Divisi</th>
                        <th class="py-3.5 px-4">Deskripsi</th>
                        <th class="py-3.5 px-3">Bukti</th>
                        <th class="py-3.5 px-3">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
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
                            <td class="py-4 px-6 whitespace-nowrap font-mono-tag">
                                <div class="font-bold text-zinc-950 text-xs">
                                    {{ \Carbon\Carbon::parse($row->tanggal)->isoFormat('D MMM Y') }}
                                </div>
                                <div class="text-[11px] text-zinc-400 mt-0.5">
                                    {{ $row->jam_mulai ? substr($row->jam_mulai, 0, 5) : '-' }} - {{ $row->jam_selesai ? substr($row->jam_selesai, 0, 5) : '...' }}
                                </div>
                            </td>
                            <td class="py-4 px-4">
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
                            <td class="py-4 px-4 max-w-xs">
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
                            <td class="py-4 px-6 text-right whitespace-nowrap space-x-1.5 font-mono-tag">
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
                                    <td class="py-3 px-6 pl-12 relative whitespace-nowrap font-mono-tag">
                                        <div class="absolute left-6 top-1/2 -translate-y-1/2 text-zinc-400">
                                            &rdquor;
                                        </div>
                                        <div class="font-medium text-zinc-800 text-xs">
                                            {{ \Carbon\Carbon::parse($child->tanggal)->isoFormat('D MMM Y') }}
                                        </div>
                                        <div class="text-[10.5px] text-zinc-400">
                                            {{ $child->jam_mulai ? substr($child->jam_mulai, 0, 5) : '-' }} - {{ $child->jam_selesai ? substr($child->jam_selesai, 0, 5) : '...' }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="text-xs text-zinc-800 font-medium">Lanjutan: {{ $child->kegiatan ?: $row->kegiatan }}</div>
                                        <div class="text-[10.5px] text-zinc-400 font-mono-tag">{{ $child->tim }} - {{ $child->jenis_gsbpm }}</div>
                                    </td>
                                    <td class="py-3 px-4 max-w-xs">
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
                                    <td class="py-3 px-6 text-right whitespace-nowrap space-x-1.5 font-mono-tag">
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

        <div class="p-5 border-t border-zinc-100 bg-zinc-50/50">
            {{ $riwayat->links() }}
        </div>
    </div>


    {{-- ========================================================================= --}}
    {{-- MODAL 1: BUAT LOGBOOK BARU (POP-UP ATRAKTIF & RINGAN)                      --}}
    {{-- ========================================================================= --}}
    <div x-show="showCreateModal" 
         x-cloak 
         @keydown.escape.window="showCreateModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto bg-zinc-950/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="bg-white rounded-3xl border border-zinc-200/80 shadow-2xl max-w-2xl w-full max-h-[92vh] flex flex-col overflow-hidden"
             @click.outside="showCreateModal = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">

            {{-- Modal Header --}}
            <div class="border-b border-zinc-100 p-6 sm:p-7 flex items-start justify-between bg-zinc-50/50">
                <div>
                    <span class="text-[10px] font-mono-tag uppercase tracking-widest text-zinc-400 block mb-1">// NEW ENTRY PROTOCOL</span>
                    <h2 class="text-xl font-extrabold text-zinc-950 tracking-tight">Buat Entri Logbook Baru</h2>
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
                <div class="overflow-y-auto p-6 sm:p-7 space-y-4 text-xs">
                    
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
                            <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider mb-1 font-mono-tag">Jenis GSBPM</label>
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
                <div class="border-t border-zinc-100 p-4 sm:p-5 bg-zinc-50/70 flex flex-col-reverse sm:flex-row items-center justify-between gap-3">
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
                                class="flex-1 sm:flex-none px-5 py-2.5 rounded-xl bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-mono-tag font-bold transition shadow-sm flex items-center justify-center gap-1.5">
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
         class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto bg-zinc-950/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="bg-white rounded-3xl border border-zinc-200/80 shadow-2xl max-w-2xl w-full max-h-[92vh] flex flex-col overflow-hidden"
             @click.outside="showLanjutkanModal = false; selectedDraft = null;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">

            {{-- Modal Header --}}
            <div class="border-b border-zinc-100 p-6 sm:p-7 flex items-start justify-between bg-zinc-50/50">
                <div>
                    <span class="text-[10px] font-mono-tag uppercase tracking-widest text-zinc-400 block mb-1">// PROJECT CONTINUATION</span>
                    <h2 class="text-xl font-extrabold text-zinc-950 tracking-tight" x-text="selectedDraft ? 'Lanjutkan Aktivitas Proyek' : 'Pilih Proyek Logbook'"></h2>
                    <p class="text-xs text-zinc-500 mt-0.5" x-text="selectedDraft ? 'Melanjutkan catatan progres proyek yang belum selesai.' : 'Pilih proyek atau pekerjaan draf yang ingin diteruskan hari ini.'"></p>
                </div>
                <button type="button" 
                        @click="showLanjutkanModal = false; selectedDraft = null;"
                        class="w-8 h-8 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-500 hover:text-zinc-900 flex items-center justify-center transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- VIEW A: DAFTAR DRAF PROYEK --}}
            <div x-show="!selectedDraft" class="flex-1 overflow-y-auto p-6 sm:p-7 space-y-4">
                @if($drafts->isEmpty())
                    <div class="py-12 px-6 text-center space-y-3">
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
                                            class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-mono-tag font-semibold rounded-xl transition shadow-xs active:scale-95 cursor-pointer">
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

                    <div class="overflow-y-auto p-6 sm:p-7 space-y-4 text-xs">
                        {{-- Mode Banner --}}
                        <div class="p-4 bg-zinc-50 border border-zinc-200/80 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
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
                                    class="shrink-0 px-3 py-1.5 rounded-xl border border-zinc-200 bg-white hover:bg-zinc-100 text-zinc-700 text-[11px] font-mono-tag font-semibold transition">
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
                    <div class="border-t border-zinc-100 p-4 sm:p-5 bg-zinc-50/70 flex flex-col-reverse sm:flex-row items-center justify-between gap-3">
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
                                    class="flex-1 sm:flex-none px-5 py-2.5 rounded-xl bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-mono-tag font-bold transition shadow-sm flex items-center justify-center gap-1.5">
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
         class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto bg-zinc-950/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="bg-white rounded-3xl border border-zinc-200/80 shadow-2xl max-w-2xl w-full max-h-[92vh] flex flex-col overflow-hidden"
             @click.outside="showEditModal = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">

            {{-- Modal Header --}}
            <div class="border-b border-zinc-100 p-6 sm:p-7 flex items-start justify-between bg-zinc-50/50">
                <div>
                    <span class="text-[10px] font-mono-tag uppercase tracking-widest text-zinc-400 block mb-1">// EDIT ENTRY PROTOCOL</span>
                    <h2 class="text-xl font-extrabold text-zinc-950 tracking-tight">Edit Catatan Logbook</h2>
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
                <div class="overflow-y-auto p-6 sm:p-7 space-y-4 text-xs">

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
                            <label class="block text-[11px] font-semibold text-zinc-700 uppercase tracking-wider mb-1 font-mono-tag">Jenis GSBPM</label>
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
                <div class="border-t border-zinc-100 p-4 sm:p-5 bg-zinc-50/70 flex flex-col-reverse sm:flex-row items-center justify-between gap-3">
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
                                class="flex-1 sm:flex-none px-5 py-2.5 rounded-xl bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-mono-tag font-bold transition shadow-sm flex items-center justify-center gap-1.5">
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
