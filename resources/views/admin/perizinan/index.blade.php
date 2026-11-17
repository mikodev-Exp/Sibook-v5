@extends('layouts.role', ['role' => 'admin'])

@section('title', 'Perizinan')

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
<div x-data="perizinanAdmin()" class="max-w-[1400px] mx-auto space-y-4">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-xs text-gray-500 font-medium mb-1">
                <span>Perizinan</span>
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-400">Daftar Pengajuan Izin</span>
            </nav>
            <h1 class="text-2xl lg:text-3xl font-extrabold text-gray-900 tracking-tight">
                Perizinan Mahasiswa
            </h1>
        </div>

        {{-- Stat Chips --}}
        <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                <span class="font-bold">{{ $totalCount ?? $perizinans->total() }}</span> Total
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                <span class="font-bold">{{ $submittedCount ?? 0 }}</span> Menunggu
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="font-bold">{{ $approvedCount ?? 0 }}</span> Disetujui
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                <span class="font-bold">{{ $rejectedCount ?? 0 }}</span> Ditolak
            </span>
        </div>
    </div>

    {{-- Card Tabel Utama --}}
    <div id="perizinan-table-container"
         data-async-container
         @click="SiBookAsync.handleClick($event, 'perizinan-table-container')"
         class="bg-white rounded-2xl border border-gray-150 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden flex flex-col min-h-[580px] lg:min-h-[calc(100vh-220px)]">
        
        {{-- Toolbar: Filter Status & Search --}}
        <div class="p-4 sm:p-5 flex flex-col sm:flex-row items-center justify-between gap-3 border-b border-gray-100">
            {{-- Status Filter Tabs --}}
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
                           placeholder="Cari nama, alasan..." 
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
                        <th class="py-3.5 px-3">Bukti</th>
                        <th class="py-3.5 px-3">Status</th>
                        <th class="py-3.5 pl-3 pr-6 text-right">Aksi</th>
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
                            $statusLabel = match($status) {
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                default => 'Menunggu Review',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition-colors row-appear">
                            <td class="py-4 pl-6 pr-3">
                                <div class="font-semibold text-gray-900 leading-tight">{{ $izin->user->name ?? '-' }}</div>
                                <div class="text-[11px] text-gray-400">{{ $izin->user->email ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-3 text-gray-600 whitespace-nowrap">
                                <div class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($izin->tanggal_mulai)->isoFormat('D MMM Y') }}</div>
                                <div class="text-[11px] text-gray-400">s/d {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->isoFormat('D MMM Y') }}</div>
                            </td>
                            <td class="py-4 px-3 text-gray-700 max-w-xs truncate" title="{{ $izin->alasan }}">
                                <div class="truncate font-medium">{{ $izin->alasan }}</div>
                                @if($izin->catatan)
                                    <div class="text-[11px] text-gray-400 mt-0.5 truncate">Catatan: {{ $izin->catatan }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-3 whitespace-nowrap">
                                @if($izin->url_bukti)
                                    <a href="{{ $izin->url_bukti }}" target="_blank" class="no-async text-xs font-semibold text-orange-600 hover:text-orange-700 inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        <span>Lihat Bukti</span>
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusBadge }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="py-4 pl-3 pr-6 text-right whitespace-nowrap">
                                @if($status === 'submitted')
                                    <button type="button" 
                                            @click="openReviewModal({{ json_encode([
                                                'id' => $izin->id,
                                                'nama' => $izin->user->name ?? '-',
                                                'periode' => \Carbon\Carbon::parse($izin->tanggal_mulai)->isoFormat('D MMM Y') . ' s/d ' . \Carbon\Carbon::parse($izin->tanggal_selesai)->isoFormat('D MMM Y'),
                                                'alasan' => $izin->alasan,
                                                'bukti' => $izin->url_bukti,
                                            ]) }})"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold bg-orange-600 hover:bg-orange-700 text-white transition shadow-sm">
                                        Proses
                                    </button>
                                @else
                                    <button type="button" 
                                            @click="openReviewModal({{ json_encode([
                                                'id' => $izin->id,
                                                'nama' => $izin->user->name ?? '-',
                                                'periode' => \Carbon\Carbon::parse($izin->tanggal_mulai)->isoFormat('D MMM Y') . ' s/d ' . \Carbon\Carbon::parse($izin->tanggal_selesai)->isoFormat('D MMM Y'),
                                                'alasan' => $izin->alasan,
                                                'bukti' => $izin->url_bukti,
                                                'current_status' => $status,
                                                'catatan' => $izin->catatan,
                                            ]) }})"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition">
                                        Ubah
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-10 h-10 mb-2 text-gray-300 stroke-[1.2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-xs font-semibold text-gray-600">Belum ada pengajuan perizinan</p>
                                    <p class="text-[11px] text-gray-400 mt-0.5">Pengajuan izin dari mahasiswa magang akan muncul di sini.</p>
                                </div>
                            </td>
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

    {{-- Modal Review Perizinan --}}
    <div x-show="showModal"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/40 modal-backdrop"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-150"
             @click.outside="showModal = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Review Pengajuan Izin</h3>
                    <p class="text-xs text-gray-500 mt-0.5" x-text="activeData.nama"></p>
                </div>
                <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 p-1">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="py-4 space-y-3 text-xs">
                <div class="bg-gray-50 p-3 rounded-xl space-y-1.5 border border-gray-100">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Periode:</span>
                        <span class="font-bold text-gray-800" x-text="activeData.periode"></span>
                    </div>
                    <div>
                        <span class="text-gray-500 block mb-0.5">Alasan:</span>
                        <p class="font-medium text-gray-800 text-xs" x-text="activeData.alasan"></p>
                    </div>
                    <template x-if="activeData.bukti">
                        <div class="pt-1">
                            <a :href="activeData.bukti" target="_blank" class="text-orange-600 font-bold hover:underline inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                <span>Buka Bukti Dokumen</span>
                            </a>
                        </div>
                    </template>
                </div>

                <form :action="`/admin/perizinan/${activeData.id}/status`" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Keputusan</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-emerald-50/50 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50">
                                <input type="radio" name="status" value="approved" required class="text-emerald-600 focus:ring-emerald-500">
                                <span class="font-bold text-emerald-800 text-xs">Setujui Izin</span>
                            </label>
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-rose-50/50 has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50">
                                <input type="radio" name="status" value="rejected" required class="text-rose-600 focus:ring-rose-500">
                                <span class="font-bold text-rose-800 text-xs">Tolak Izin</span>
                            </label>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1.5">* Menyetujui izin akan otomatis mencatat presensi sebagai 'Izin' pada tanggal terkait.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Catatan Admin (Opsional)</label>
                        <textarea name="catatan" rows="2" placeholder="Tuliskan catatan atau alasan persetujuan/penolakan..."
                                  class="w-full text-xs border border-gray-200 rounded-xl p-2.5 focus:outline-none focus:ring-1 focus:ring-orange-500"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-medium text-gray-600 hover:bg-gray-100 rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-orange-600 hover:bg-orange-700 rounded-xl transition shadow-sm">
                            Simpan Keputusan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
function perizinanAdmin() {
    return {
        showModal: false,
        activeData: {
            id: null,
            nama: '',
            periode: '',
            alasan: '',
            bukti: null,
        },
        openReviewModal(data) {
            this.activeData = data;
            this.showModal = true;
        }
    };
}
</script>
@endsection
