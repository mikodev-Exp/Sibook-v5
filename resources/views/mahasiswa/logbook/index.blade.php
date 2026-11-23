@extends('layouts.role', ['role' => 'mahasiswa'])

@section('title', 'Logbook Kegiatan')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

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
            <a href="{{ route('mahasiswa.logbook.lanjutkan') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-zinc-200 bg-white hover:bg-zinc-50 text-zinc-800 text-xs font-mono-tag font-semibold transition shadow-xs">
                <svg class="w-3.5 h-3.5 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>LANJUTKAN PROYEK</span>
                @if($belumSelesai > 0)
                    <span class="ml-1 px-1.5 py-0.2 bg-amber-100 text-amber-900 text-[10px] rounded-full font-bold">{{ $belumSelesai }}</span>
                @endif
            </a>

            <a href="{{ route('mahasiswa.logbook.create') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-mono-tag font-semibold transition shadow-sm hover:shadow">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span>BUAT LOGBOOK BARU</span>
            </a>
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
                                <a href="{{ route('mahasiswa.logbook.edit', $row->id) }}"
                                   class="inline-block px-3 py-1 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 rounded-lg text-xs font-semibold transition">
                                    EDIT
                                </a>
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
                                        <a href="{{ route('mahasiswa.logbook.edit', $child->id) }}"
                                           class="inline-block px-2.5 py-0.5 bg-zinc-200 hover:bg-zinc-300 text-zinc-800 rounded text-xs font-semibold">
                                            EDIT
                                        </a>
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

</div>
@endsection
