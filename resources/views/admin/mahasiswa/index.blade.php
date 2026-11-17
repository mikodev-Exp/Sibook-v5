@extends('layouts.role', ['role' => 'admin'])

@section('title', 'Mahasiswa Magang')

@section('content')
<div class="max-w-[1400px] mx-auto space-y-4">

    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-xs text-gray-500 font-medium">
        <span>Mahasiswa Magang</span>
        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-400">Daftar Aktif</span>
    </nav>

    {{-- Judul Halaman --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <h1 class="text-2xl lg:text-3xl font-extrabold text-gray-900 tracking-tight">
            Mahasiswa Magang
        </h1>
    </div>

    {{-- Card Tabel Utama --}}
    <div id="mahasiswa-table-container"
         data-async-container
         @click="SiBookAsync.handleClick($event, 'mahasiswa-table-container')"
         class="bg-white rounded-2xl border border-gray-150 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden">
        
        {{-- Toolbar Atas (Search) --}}
        <div class="p-4 sm:p-5 flex flex-col sm:flex-row items-center justify-between gap-3 border-b border-gray-100">
            <div class="text-xs text-gray-500 font-medium">
                Daftar mahasiswa magang yang terdaftar aktif di BPS Kota Surabaya.
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                <a href="{{ route('admin.mahasiswa.export', request()->all()) }}"
                   class="no-async inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition shadow-sm flex-shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Export Rekap (.xlsx)</span>
                </a>

                <form method="GET" action="{{ route('admin.mahasiswa.index') }}" 
                      @submit.prevent="SiBookAsync.handleFormSubmit($event, 'mahasiswa-table-container')"
                      class="relative flex-1 sm:w-72">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           @input.debounce.300ms="SiBookAsync.handleSearch($event, 'mahasiswa-table-container')"
                           placeholder="Cari nama, universitas, NIM..." 
                           class="w-full pl-9 pr-3 py-1.5 text-xs bg-white border border-gray-200 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition shadow-sm">
                </form>
            </div>
        </div>

        {{-- Tabel Mahasiswa --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 text-[13px] font-semibold text-gray-700 bg-white">
                        <th class="py-3.5 pl-6 pr-3">Mahasiswa</th>
                        <th class="py-3.5 px-3">Universitas & Program Studi</th>
                        <th class="py-3.5 px-3">Kontak</th>
                        <th class="py-3.5 px-3">Periode Magang</th>
                        <th class="py-3.5 pl-3 pr-6 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-[13px] text-gray-800">
                    @forelse($mahasiswas as $mhs)
                        @php
                            $profile = $mhs->mahasiswaProfile;
                            $magang = $mhs->magang;
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            {{-- Info Nama & NIM --}}
                            <td class="py-4 pl-6 pr-3">
                                <div class="font-semibold text-gray-900">{{ $mhs->name }}</div>
                                <div class="text-xs text-gray-400 font-mono mt-0.5">NIM: {{ $profile->nim ?? '-' }}</div>
                            </td>

                            {{-- Universitas & Prodi --}}
                            <td class="py-4 px-3">
                                <div class="font-medium text-gray-800">{{ $profile->universitas ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $profile->program_studi ?? ($profile->jurusan ?? '-') }}</div>
                            </td>

                            {{-- Kontak --}}
                            <td class="py-4 px-3 text-gray-600">
                                <div>{{ $mhs->email }}</div>
                                <div class="text-xs text-gray-400">{{ $profile->no_hp ?? '-' }}</div>
                            </td>

                            {{-- Periode --}}
                            <td class="py-4 px-3 whitespace-nowrap">
                                @if($magang)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ \Carbon\Carbon::parse($magang->tanggal_mulai)->format('d/m/y') }} - {{ \Carbon\Carbon::parse($magang->tanggal_selesai)->format('d/m/y') }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">Belum diset</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="py-4 pl-3 pr-6 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5 justify-end">
                                    {{-- Tombol Laporan: Dibuka di tab baru sesuai instruksi --}}
                                    <a href="{{ route('admin.mahasiswa.laporan', $mhs->id) }}" 
                                       target="_blank"
                                       class="no-async inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#4338CA] hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold transition shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span>Laporan</span>
                                    </a>

                                    {{-- Tombol Logbook --}}
                                    <a href="{{ route('admin.logbook.index', ['search' => $mhs->name]) }}" 
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-white hover:bg-orange-50 border border-gray-200 hover:border-orange-300 text-gray-700 hover:text-orange-600 rounded-lg text-xs font-semibold transition shadow-sm">
                                        <span>Logbook</span>
                                        <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-400">
                                <p class="text-sm font-medium text-gray-500">Belum ada mahasiswa magang aktif</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($mahasiswas->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between">
                <div class="text-xs text-gray-500">
                    Menampilkan {{ $mahasiswas->firstItem() }} - {{ $mahasiswas->lastItem() }} dari {{ $mahasiswas->total() }} mahasiswa
                </div>
                <div>
                    {{ $mahasiswas->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
