@extends('layouts.role', ['role' => 'admin'])

@section('title', 'Presensi Magang')

@section('content')
<div class="max-w-[1400px] mx-auto space-y-4">

    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-xs text-gray-500 font-medium">
        <span>Presensi</span>
        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-400">Presensi Magang</span>
    </nav>

    {{-- Judul Halaman --}}
    <h1 class="text-2xl lg:text-3xl font-extrabold text-gray-900 tracking-tight">
        Presensi Magang
    </h1>

    {{-- Card Tabel Utama --}}
    <div id="presensi-table-container"
         data-async-container
         @click="SiBookAsync.handleClick($event, 'presensi-table-container')"
         class="bg-white rounded-2xl border border-gray-150 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden">
        
        {{-- Toolbar Atas --}}
        <div class="p-4 sm:p-5 flex flex-col sm:flex-row items-center justify-between gap-3 border-b border-gray-100">
            <div class="text-xs text-gray-500 font-medium">
                Monitoring presensi GPS dan luar kantor mahasiswa magang.
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto justify-end">
                {{-- Tombol Export Rekap Presensi --}}
                <a href="{{ route('admin.presensi.export', request()->all()) }}"
                   class="no-async inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition shadow-sm flex-shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Export Rekap (.xlsx)</span>
                </a>

                {{-- Cari Mahasiswa --}}
                <div class="relative w-full sm:w-56">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           @input.debounce.300ms="SiBookAsync.handleSearch($event, 'presensi-table-container')"
                           placeholder="Cari mahasiswa..." 
                           class="w-full pl-8 pr-3 py-1.5 text-xs bg-white border border-gray-200 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition shadow-sm">
                </div>

                {{-- Filter Tanggal --}}
                <form method="GET" action="{{ route('admin.presensi.index') }}" 
                      @submit.prevent="SiBookAsync.handleFormSubmit($event, 'presensi-table-container')"
                      class="flex items-center gap-2 w-full sm:w-auto">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}" 
                           @change="SiBookAsync.handleFormSubmit($event, 'presensi-table-container')"
                           class="text-xs bg-white border border-gray-200 rounded-lg text-gray-800 p-1.5 focus:outline-none focus:ring-1 focus:ring-orange-500 shadow-sm cursor-pointer">
                    @if(request('tanggal') || request('search'))
                        <a href="{{ route('admin.presensi.index') }}" class="text-xs text-gray-500 hover:text-gray-800 underline ml-1">Reset</a>
                    @endif
                </form>
            </div>
        </div>

        {{-- Tabel Presensi --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-[13px]">
                <thead>
                    <tr class="border-b border-gray-100 font-semibold text-gray-700 bg-white">
                        <th class="py-3.5 pl-6 pr-3">Mahasiswa</th>
                        <th class="py-3.5 px-3">Tanggal</th>
                        <th class="py-3.5 px-3">Jam Masuk</th>
                        <th class="py-3.5 px-3">Jam Pulang</th>
                        <th class="py-3.5 px-3">Tipe Presensi</th>
                        <th class="py-3.5 px-3">Jarak Masuk</th>
                        <th class="py-3.5 pl-3 pr-6">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-800">
                    @forelse($presensis as $presensi)
                        @php
                            $status = $presensi->status?->value ?? (string) $presensi->status;
                            $statusBadge = match($status) {
                                'hadir' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'terlambat' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'izin' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'sakit' => 'bg-purple-50 text-purple-700 border-purple-200',
                                default => 'bg-rose-50 text-rose-700 border-rose-200',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="py-4 pl-6 pr-3 font-semibold text-gray-900">
                                {{ $presensi->user->name ?? '-' }}
                            </td>
                            <td class="py-4 px-3 text-gray-600 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($presensi->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-4 px-3 font-mono text-xs text-gray-800">
                                {{ $presensi->jam_masuk ? substr($presensi->jam_masuk, 0, 5) : '-' }}
                            </td>
                            <td class="py-4 px-3 font-mono text-xs text-gray-800">
                                {{ $presensi->jam_pulang ? substr($presensi->jam_pulang, 0, 5) : '-' }}
                            </td>
                            <td class="py-4 px-3">
                                <span class="capitalize text-xs font-medium text-gray-700">
                                    {{ str_replace('_', ' ', $presensi->tipe_presensi ?? 'kantor') }}
                                </span>
                            </td>
                            <td class="py-4 px-3 text-xs text-gray-500 font-mono">
                                {{ $presensi->jarak_masuk_m ? round($presensi->jarak_masuk_m) . ' m' : '-' }}
                            </td>
                            <td class="py-4 pl-3 pr-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusBadge }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-gray-400">Belum ada catatan presensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($presensis->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between">
                <div class="text-xs text-gray-500">
                    Menampilkan {{ $presensis->firstItem() }} - {{ $presensis->lastItem() }} dari {{ $presensis->total() }} data
                </div>
                <div>
                    {{ $presensis->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
