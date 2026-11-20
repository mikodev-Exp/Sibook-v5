@extends('layouts.role', ['role' => 'mahasiswa'])

@section('title', 'Logbook Harian')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Header & Aksi --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Logbook Kegiatan</h1>
            <p class="text-xs text-gray-500 mt-1">Catat dan pantau aktivitas harian pelaksanaan program magang Anda.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('mahasiswa.logbook.lanjutkan') }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-xs font-bold transition shadow-sm">
                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Lanjutkan Proyek</span>
                @if($belumSelesai > 0)
                    <span class="ml-1 px-1.5 py-0.2 bg-amber-100 text-amber-800 text-[10px] rounded-full font-bold">{{ $belumSelesai }}</span>
                @endif
            </a>

            <a href="{{ route('mahasiswa.logbook.create') }}" 
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold transition shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Buat Logbook Baru</span>
            </a>
        </div>
    </div>

    {{-- Stat Chips --}}
    <div class="flex flex-wrap items-center gap-2">
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-white text-gray-700 border border-gray-200 shadow-sm">
            <span class="font-bold">{{ $totalLogbook }}</span> Total Aktivitas
        </span>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
            <span class="font-bold">{{ $selesai }}</span> Selesai / Terkirim
        </span>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
            <span class="font-bold">{{ $belumSelesai }}</span> Draf / Berjalan
        </span>
    </div>

    {{-- Tabel Riwayat Logbook --}}
    <div id="mahasiswa-logbook-container"
         data-async-container
         @click="SiBookAsync.handleClick($event, 'mahasiswa-logbook-container')"
         class="bg-white rounded-2xl shadow-[0_1px_3px_rgba(0,0,0,0.02)] border border-gray-150 overflow-hidden flex flex-col min-h-[520px]">
        
        <div class="p-5 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h2 class="text-sm font-bold text-gray-900">Riwayat Catatan Aktivitas</h2>
                <p class="text-[11px] text-gray-400 mt-0.5">Semua entri logbook yang telah dicatat</p>
            </div>
        </div>

        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left border-collapse text-[13px]">
                <thead>
                    <tr class="border-b border-gray-100 font-semibold text-gray-700 bg-gray-50/50">
                        <th class="py-3 px-5">Tanggal &amp; Waktu</th>
                        <th class="py-3 px-4">Kegiatan &amp; Tim</th>
                        <th class="py-3 px-4">Deskripsi</th>
                        <th class="py-3 px-3">Bukti</th>
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-800">
                    @forelse($riwayat as $row)
                                                @php
                            $rowStatus = $row->status instanceof \App\Enums\StatusLogbook 
                                ? $row->status->value 
                                : (string) $row->status;

                            // Mengubah warna: Draft (Kuning), Submitted (Hijau), Approved (Biru)
                            $badge = match($rowStatus) {
                                'approved'  => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                'rejected'  => 'bg-rose-50 text-rose-700 border-rose-200',
                                'submitted' => 'bg-emerald-50 text-emerald-700 border-emerald-200', // Hijau
                                default     => 'bg-amber-50 text-amber-700 border-amber-200',       // Kuning
                            };

                            // Mengubah label: Submitted menjadi Selesai
                            $label = match($rowStatus) {
                                'approved'  => 'Disetujui Admin',
                                'rejected'  => 'Ditolak',
                                'submitted' => 'Selesai', // <-- Diubah dari 'Terkirim'
                                default     => 'Draf',
                            };
                        @endphp
                        {{-- Baris Induk --}}
                        <tr class="hover:bg-gray-50/60 transition group">
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                <div class="font-semibold text-gray-900 text-xs">
                                    {{ \Carbon\Carbon::parse($row->tanggal)->isoFormat('D MMM Y') }}
                                </div>
                                <div class="text-[11px] text-gray-400 mt-0.5">
                                    {{ $row->jam_mulai ? substr($row->jam_mulai, 0, 5) : '-' }} - {{ $row->jam_selesai ? substr($row->jam_selesai, 0, 5) : '...' }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-gray-900 text-xs">{{ $row->kegiatan ?: $row->deskripsi }}</div>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-gray-100 text-gray-700">
                                        {{ $row->tim }}
                                    </span>
                                    @if($row->jenis_gsbpm)
                                        <span class="text-[10px] text-gray-400">GSBPM: {{ $row->jenis_gsbpm }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4 max-w-xs">
                                <div class="text-xs text-gray-600 line-clamp-2" title="{{ $row->deskripsi }}">
                                    {{ $row->deskripsi }}
                                </div>
                            </td>
                            <td class="py-3.5 px-3 whitespace-nowrap">
                                @if($row->link_bukti)
                                    <a href="{{ $row->link_bukti }}" target="_blank"
                                       class="no-async text-xs font-semibold text-orange-600 hover:underline inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        <span>Bukti</span>
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                                                        <td class="py-3.5 px-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold border {{ $badge }}">
                                    @if($rowStatus === 'submitted')
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    @endif
                                    {{ $label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-right whitespace-nowrap space-x-1.5">
                                <a href="{{ route('mahasiswa.logbook.edit', $row->id) }}"
                                   class="inline-block px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition">
                                    Edit
                                </a>
                                <form action="{{ route('mahasiswa.logbook.destroy', $row->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus logbook ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-semibold transition">
                                        Hapus
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
                                        'approved'  => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'rejected'  => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'submitted' => 'bg-emerald-50 text-emerald-700 border-emerald-200', // Hijau
                                        default     => 'bg-amber-50 text-amber-700 border-amber-200',
                                    };

                                    $cLabel = match($cStatus) {
                                        'approved'  => 'Disetujui',
                                        'rejected'  => 'Ditolak',
                                        'submitted' => 'Selesai',
                                        default     => 'Draf',
                                    };
                                @endphp
                                <tr class="bg-gray-50/70 hover:bg-gray-100/60 transition border-b border-gray-100">
                                    
                                    <!-- KOLOM 1: TANGGAL & WAKTU -->
                                    <td class="py-3 px-5 pl-10 relative whitespace-nowrap">
                                        <div class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                        </div>
                                        <div class="font-medium text-gray-800 text-xs">
                                            {{ \Carbon\Carbon::parse($child->tanggal)->isoFormat('D MMM Y') }}
                                        </div>
                                        <div class="text-[10.5px] text-gray-400">
                                            {{ $child->jam_mulai ? substr($child->jam_mulai, 0, 5) : '-' }} - {{ $child->jam_selesai ? substr($child->jam_selesai, 0, 5) : '...' }}
                                        </div>
                                    </td>

                                    <!-- KOLOM 2: KEGIATAN & TIM -->
                                    <td class="py-3 px-4">
                                        <div class="text-xs text-gray-700 font-medium">Lanjutan: {{ $child->kegiatan ?: $row->kegiatan }}</div>
                                        <div class="text-[10.5px] text-gray-400">{{ $child->tim }} - {{ $child->jenis_gsbpm }}</div>
                                    </td>

                                    <!-- KOLOM 3: DESKRIPSI -->
                                    <td class="py-3 px-4 max-w-xs">
                                        <div class="text-xs text-gray-500 line-clamp-1" title="{{ $child->deskripsi }}">{{ $child->deskripsi }}</div>
                                    </td>

                                    <!-- KOLOM 4: BUKTI (Biarkan seperti aslinya) -->
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        @if($child->link_bukti)
                                            <a href="{{ $child->link_bukti }}" target="_blank"
                                               class="no-async text-xs font-semibold text-orange-600 hover:underline">
                                                Bukti
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>

                                    <!-- KOLOM 5: STATUS (Di sinilah badge Selesai ditaruh) -->
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-semibold border {{ $cBadge }}">
                                            @if($cStatus === 'submitted')
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            @endif
                                            {{ $cLabel }}
                                        </span>
                                    </td>

                                    <!-- KOLOM 6: AKSI -->
                                    <td class="py-3 px-5 text-right whitespace-nowrap space-x-1.5">
                                        <a href="{{ route('mahasiswa.logbook.edit', $child->id) }}"
                                           class="inline-block px-2 py-0.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded text-xs font-semibold">
                                            Edit
                                        </a>
                                        <form action="{{ route('mahasiswa.logbook.destroy', $child->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus logbook ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-2 py-0.5 bg-rose-100 hover:bg-rose-200 text-rose-700 rounded text-xs font-semibold">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-10 h-10 mb-2 text-gray-300 stroke-[1.2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    <p class="text-xs font-semibold text-gray-600">Belum ada riwayat logbook</p>
                                    <p class="text-[11px] text-gray-400 mt-0.5">Mulai dengan mencatat kegiatan harian Anda melalui tombol di atas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 bg-gray-50/40">
            {{ $riwayat->links() }}
        </div>
    </div>

</div>
@endsection
