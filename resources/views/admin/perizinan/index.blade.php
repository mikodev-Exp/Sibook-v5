@extends('layouts.role', ['role' => 'admin'])

@section('title', 'Perizinan')

@section('content')
<div class="max-w-[1400px] mx-auto space-y-4">

    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 text-xs text-gray-500 font-medium">
        <span>Perizinan</span>
        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-400">Daftar Pengajuan Izin</span>
    </nav>

    {{-- Judul Halaman --}}
    <h1 class="text-2xl lg:text-3xl font-extrabold text-gray-900 tracking-tight">
        Perizinan Mahasiswa
    </h1>

    {{-- Card Tabel Utama --}}
    <div id="perizinan-table-container"
         data-async-container
         @click="SiBookAsync.handleClick($event, 'perizinan-table-container')"
         class="bg-white rounded-2xl border border-gray-150 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden flex flex-col min-h-[580px] lg:min-h-[calc(100vh-220px)]">
        
        {{-- Toolbar: Filter Status & Search --}}
        <div class="p-4 sm:p-5 flex flex-col sm:flex-row items-center justify-between gap-3 border-b border-gray-100">
            {{-- Status Filter Pills --}}
            <div class="flex items-center gap-1.5 overflow-x-auto w-full sm:w-auto">
                @php
                    $curStatus = request('status');
                @endphp
                <a href="{{ route('admin.perizinan.index', request()->except(['status', 'page'])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ !$curStatus ? 'bg-orange-50 text-orange-600 border border-orange-200' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-50' }}">
                    Semua
                </a>
                <a href="{{ route('admin.perizinan.index', array_merge(request()->except('page'), ['status' => 'submitted'])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $curStatus === 'submitted' ? 'bg-orange-50 text-orange-600 border border-orange-200' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-50' }}">
                    Menunggu
                </a>
                <a href="{{ route('admin.perizinan.index', array_merge(request()->except('page'), ['status' => 'approved'])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $curStatus === 'approved' ? 'bg-orange-50 text-orange-600 border border-orange-200' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-50' }}">
                    Disetujui
                </a>
                <a href="{{ route('admin.perizinan.index', array_merge(request()->except('page'), ['status' => 'rejected'])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $curStatus === 'rejected' ? 'bg-orange-50 text-orange-600 border border-orange-200' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-50' }}">
                    Ditolak
                </a>
            </div>

            {{-- Search Bar --}}
            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                <form method="GET" action="{{ route('admin.perizinan.index') }}" 
                      @submit.prevent="SiBookAsync.handleFormSubmit($event, 'perizinan-table-container')"
                      class="relative w-full sm:w-64">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           @input.debounce.300ms="SiBookAsync.handleSearch($event, 'perizinan-table-container')"
                           placeholder="Cari mahasiswa, alasan..." 
                           class="w-full pl-9 pr-3 py-1.5 text-xs bg-white border border-gray-200 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition shadow-sm">
                </form>
            </div>
        </div>

        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left border-collapse text-[13px]">
                <thead>
                    <tr class="border-b border-gray-100 font-semibold text-gray-700 bg-white">
                        <th class="py-3.5 pl-6 pr-3">Mahasiswa</th>
                        <th class="py-3.5 px-3">Periode Izin</th>
                        <th class="py-3.5 px-3">Alasan</th>
                        <th class="py-3.5 px-3">Status</th>
                        <th class="py-3.5 pl-3 pr-6 text-right">Bukti</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-800">
                    @forelse($perizinans as $izin)
                        @php
                            $status = $izin->status?->value ?? (string) $izin->status;
                            $statusBadge = match($status) {
                                'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                default => 'bg-amber-50 text-amber-700 border-amber-200',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="py-4 pl-6 pr-3 font-semibold text-gray-900">
                                {{ $izin->user->name ?? '-' }}
                            </td>
                            <td class="py-4 px-3 text-gray-600 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d M Y') }} s/d 
                                {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->format('d M Y') }}
                            </td>
                            <td class="py-4 px-3 text-gray-700 max-w-sm truncate" title="{{ $izin->alasan }}">
                                {{ $izin->alasan }}
                            </td>
                            <td class="py-4 px-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusBadge }}">
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </span>
                            </td>
                            <td class="py-4 pl-3 pr-6 text-right whitespace-nowrap">
                                @if($izin->url_bukti)
                                    <a href="{{ $izin->url_bukti }}" target="_blank" class="text-xs font-semibold text-orange-600 hover:text-orange-700 inline-flex items-center gap-1">
                                        <span>Lihat Bukti</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-gray-400">Belum ada pengajuan izin.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Informasi & Pagination --}}
        <div class="mt-auto px-5 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-2 bg-gray-50/40">
            <div class="text-xs text-gray-500">
                @if($perizinans->total() > 0)
                    Menampilkan {{ $perizinans->firstItem() }} - {{ $perizinans->lastItem() }} dari {{ $perizinans->total() }} data
                @else
                    Tidak ada data perizinan
                @endif
            </div>
            @if($perizinans->hasPages())
                <div>
                    {{ $perizinans->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
