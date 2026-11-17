@extends('layouts.role', ['role' => 'admin'])

@section('title', 'Logbook Mahasiswa Magang')

@section('content')
<div x-data="logbookTable()" class="max-w-[1400px] mx-auto space-y-4">

    {{-- Breadcrumbs persis di mockup --}}
    <div class="flex items-center gap-2 text-[13px] text-[#6B7280]">
        <span>Logbook Mahasiswa Magang</span>
        <span class="text-gray-400 font-normal">&gt;</span>
        <span class="text-[#6B7280]">Daftar</span>
    </div>

    {{-- Judul Halaman --}}
    <h1 class="text-[26px] font-bold text-[#111827] tracking-tight">
        Logbook Mahasiswa Magang
    </h1>

    {{-- Card Tabel Putih Halus --}}
    <div id="logbook-table-container"
         data-async-container
         @click="SiBookAsync.handleClick($event, 'logbook-table-container')"
         class="bg-white rounded-2xl border border-[#E5E7EB] shadow-[0_1px_2px_rgba(0,0,0,0.02)] overflow-hidden">
        
        {{-- Toolbar Atas (Search & Filter di Kanan) --}}
        <div class="p-5 flex flex-col sm:flex-row items-center justify-between gap-3 border-b border-[#F0F2F5]">
            {{-- Info item terpilih jika ada --}}
            <div class="flex items-center gap-3">
                <template x-if="selectedIds.length > 0">
                    <div class="flex items-center gap-2 text-xs font-semibold text-gray-700 bg-orange-50 px-3 py-1.5 rounded-lg border border-orange-200">
                        <span x-text="selectedIds.length + ' item terpilih'"></span>
                        <button type="button" @click="selectedIds = []" class="text-orange-600 hover:text-orange-800 ml-1 underline">
                            Batal
                        </button>
                    </div>
                </template>
            </div>

            {{-- Bagian Kanan: Export, Form Search & Tombol Filter --}}
            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                <a href="{{ route('admin.logbook.export', request()->all()) }}"
                   class="no-async inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition shadow-sm flex-shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Export Rekap (.xlsx)</span>
                </a>

                {{-- Input Cari --}}
                <form method="GET" action="{{ route('admin.logbook.index') }}" 
                      @submit.prevent="SiBookAsync.handleFormSubmit($event, 'logbook-table-container')"
                      class="relative w-64 sm:w-72">
                    @foreach(request()->except(['search', 'page']) as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach

                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           @input.debounce.300ms="SiBookAsync.handleSearch($event, 'logbook-table-container')"
                           placeholder="Cari" 
                           class="w-full pl-9 pr-3 py-1.5 text-xs text-gray-800 placeholder-gray-400 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition shadow-none">
                </form>

                {{-- Tombol Filter dengan Badge '0' (Persis di Gambar) --}}
                <div class="relative" @click.outside="filterOpen = false">
                    <button type="button" 
                            @click="filterOpen = !filterOpen"
                            class="relative p-2 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-50 focus:outline-none transition">
                        {{-- Funnel Filter Icon --}}
                        <svg class="w-5 h-5 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        
                        {{-- Badge Angka 0 di Sudut Kanan Atas Ikon --}}
                        <span class="absolute -top-0.5 -right-0.5 text-[10px] font-bold text-[#F59E0B]">
                            {{ $activeFiltersCount ?? 0 }}
                        </span>
                    </button>

                    {{-- Popover Dropdown Filter --}}
                    <div x-show="filterOpen" 
                         x-cloak
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-gray-150 p-4 z-50">
                        <form method="GET" action="{{ route('admin.logbook.index') }}" 
                              @submit.prevent="SiBookAsync.handleFormSubmit($event, 'logbook-table-container', () => filterOpen = false)"
                              class="space-y-3">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Divisi / Tim</label>
                                <select name="tim" class="w-full text-xs border border-gray-200 rounded-lg p-2 focus:ring-1 focus:ring-orange-500 focus:outline-none">
                                    <option value="">Semua Divisi</option>
                                    @foreach($availableTims as $tim)
                                        <option value="{{ $tim }}" {{ request('tim') === $tim ? 'selected' : '' }}>
                                            {{ $tim }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Pengerjaan</label>
                                <input type="date" name="tanggal" value="{{ request('tanggal') }}" 
                                       class="w-full text-xs border border-gray-200 rounded-lg p-2 focus:ring-1 focus:ring-orange-500 focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Status Verifikasi</label>
                                <select name="status" class="w-full text-xs border border-gray-200 rounded-lg p-2 focus:ring-1 focus:ring-orange-500 focus:outline-none">
                                    <option value="">Semua Status</option>
                                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>

                            <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                <a href="{{ route('admin.logbook.index') }}" class="text-xs text-gray-500 hover:text-gray-800">
                                    Reset
                                </a>
                                <button type="submit" class="px-3 py-1.5 bg-[#EA580C] hover:bg-orange-700 text-white font-medium text-xs rounded-lg transition shadow-sm">
                                    Terapkan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Logbook --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#F0F2F5] text-[13px] font-bold text-[#111827] bg-white select-none">
                        {{-- Checkbox Select All --}}
                        <th class="py-4 pl-6 pr-3 w-10">
                            <input type="checkbox" 
                                   @change="toggleSelectAll($event)"
                                   :checked="isAllSelected()"
                                   class="rounded border-gray-300 text-orange-600 focus:ring-0 w-4 h-4 cursor-pointer">
                        </th>

                        {{-- Kolom: Nama Mahasiswa --}}
                        <th class="py-4 px-3 min-w-[220px]">
                            <a href="{{ route('admin.logbook.index', array_merge(request()->all(), ['sort' => 'nama', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                               class="inline-flex items-center gap-1 hover:text-gray-600 transition">
                                <span>Nama Mahasiswa</span>
                                <svg class="w-3 h-3 text-[#9CA3AF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                </svg>
                            </a>
                        </th>

                        {{-- Kolom: Divisi / Kegiatan --}}
                        <th class="py-4 px-3 min-w-[160px]">
                            <a href="{{ route('admin.logbook.index', array_merge(request()->all(), ['sort' => 'tim', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                               class="inline-flex items-center gap-1 hover:text-gray-600 transition">
                                <span>Divisi / Kegiatan</span>
                                <svg class="w-3 h-3 text-[#9CA3AF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                </svg>
                            </a>
                        </th>

                        {{-- Kolom: Tanggal Pengerjaan --}}
                        <th class="py-4 px-3 min-w-[160px]">
                            <a href="{{ route('admin.logbook.index', array_merge(request()->all(), ['sort' => 'tanggal', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                               class="inline-flex items-center gap-1 hover:text-gray-600 transition">
                                <span>Tanggal Pengerjaan</span>
                                <svg class="w-3 h-3 text-[#9CA3AF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                </svg>
                            </a>
                        </th>

                        {{-- Kolom: Deskripsi Kegiatan --}}
                        <th class="py-4 pl-3 pr-6 min-w-[340px]">
                            Deskripsi Kegiatan
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#F0F2F5] text-[13px] text-[#374151]">
                    @forelse($logbooks as $logbook)
                        @php
                            $nama = $logbook->user->name ?? 'Mahasiswa Magang';
                            $tim = $logbook->tim ?: 'Kegiatan Lainnya';

                            // Badges persis dengan warna di screenshot
                            $badgeStyle = match($tim) {
                                'IPDS'             => 'bg-[#EAFBF3] border border-[#BFF0D7] text-[#0FAF62] font-medium',
                                'Humas'            => 'bg-[#EEF4FF] border border-[#D5E3FF] text-[#3B82F6] font-medium',
                                'Sosial'           => 'bg-[#FEF2F2] border border-[#FEE2E2] text-[#EF4444] font-medium',
                                'PSS'              => 'bg-[#F5F3FF] border border-[#DDD6FE] text-[#7C3AED] font-medium',
                                'Neraca', 'Umum'   => 'bg-white border border-[#E5E7EB] text-[#4B5563] font-normal',
                                default            => 'bg-white border border-[#E5E7EB] text-[#4B5563] font-normal',
                            };
                        @endphp

                        <tr class="hover:bg-[#F9FAFB] transition-colors cursor-pointer"
                            @click="openModal({{ json_encode([
                                'id' => $logbook->id,
                                'nama' => $nama,
                                'universitas' => $logbook->user->mahasiswaProfile->universitas ?? '-',
                                'prodi' => $logbook->user->mahasiswaProfile->program_studi ?? '-',
                                'tanggal' => \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('d M Y'),
                                'tim' => $tim,
                                'kegiatan' => $logbook->kegiatan,
                                'deskripsi' => $logbook->deskripsi ?? '-',
                                'jam_mulai' => $logbook->jam_mulai ? substr($logbook->jam_mulai, 0, 5) : null,
                                'jam_selesai' => $logbook->jam_selesai ? substr($logbook->jam_selesai, 0, 5) : null,
                                'link_bukti' => $logbook->link_bukti,
                                'status' => $logbook->status?->value ?? (string) $logbook->status,
                                'catatan_verifikator' => $logbook->catatan_verifikator,
                                'verify_url' => route('admin.logbook.verify', $logbook->id),
                            ]) }})">
                            
                            {{-- Checkbox Baris --}}
                            <td class="py-4 pl-6 pr-3" @click.stop>
                                <input type="checkbox" 
                                       :value="{{ $logbook->id }}"
                                       x-model="selectedIds"
                                       class="rounded border-gray-300 text-orange-600 focus:ring-0 w-4 h-4 cursor-pointer">
                            </td>

                            {{-- Nama Mahasiswa --}}
                            <td class="py-4 px-3 font-semibold text-[#111827] whitespace-nowrap">
                                {{ $nama }}
                            </td>

                            {{-- Divisi / Kegiatan Badge --}}
                            <td class="py-4 px-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-md text-xs {{ $badgeStyle }}">
                                    {{ $tim }}
                                </span>
                            </td>

                            {{-- Tanggal Pengerjaan --}}
                            <td class="py-4 px-3 whitespace-nowrap text-[#374151]">
                                {{ \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('d M Y') }}
                            </td>

                            {{-- Deskripsi Kegiatan --}}
                            <td class="py-4 pl-3 pr-6 text-[#4B5563]">
                                <div class="line-clamp-1 max-w-lg" title="{{ $logbook->deskripsi }}">
                                    {{ $logbook->deskripsi ?: ($logbook->kegiatan ?: '-') }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500">Belum ada data logbook</p>
                                    <p class="text-xs text-gray-400">Data logbook kegiatan mahasiswa akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($logbooks->hasPages())
            <div class="px-6 py-4 border-t border-[#F0F2F5] flex items-center justify-between">
                <div class="text-xs text-gray-500">
                    Menampilkan <span class="font-semibold text-gray-700">{{ $logbooks->firstItem() }}</span> - 
                    <span class="font-semibold text-gray-700">{{ $logbooks->lastItem() }}</span> dari 
                    <span class="font-semibold text-gray-700">{{ $logbooks->total() }}</span> logbook
                </div>
                <div>
                    {{ $logbooks->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- Detail Modal --}}
    <div x-show="modalOpen" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" 
             @click="modalOpen = false"></div>

        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-150 overflow-hidden"
                 @click.outside="modalOpen = false">
                
                {{-- Header Modal --}}
                <div class="flex items-start justify-between pb-4 border-b border-gray-100">
                    <div>
                        <span class="text-[11px] font-semibold text-[#E06D28] uppercase tracking-wider">Detail Aktivitas</span>
                        <h3 class="text-lg font-bold text-[#111827] mt-0.5" x-text="activeData.nama"></h3>
                        <p class="text-xs text-gray-500" x-text="(activeData.universitas || '') + ' • ' + (activeData.prodi || '')"></p>
                    </div>
                    <button type="button" @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Isi Detail --}}
                <div class="py-4 space-y-3.5 text-xs text-gray-700">
                    <div class="grid grid-cols-2 gap-3 bg-[#F9FAFB] p-3 rounded-xl border border-gray-150">
                        <div>
                            <span class="text-gray-400 block text-[11px]">Tanggal:</span>
                            <span class="font-semibold text-[#111827]" x-text="activeData.tanggal"></span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[11px]">Jam Kerja:</span>
                            <span class="font-semibold text-[#111827]" x-text="(activeData.jam_mulai || '-') + ' s/d ' + (activeData.jam_selesai || '-')"></span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[11px]">Divisi / Tim:</span>
                            <span class="font-semibold text-[#111827]" x-text="activeData.tim"></span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[11px]">Status:</span>
                            <span class="font-semibold uppercase tracking-wider" 
                                  :class="{
                                      'text-emerald-600': activeData.status === 'approved',
                                      'text-amber-600': activeData.status === 'submitted' || activeData.status === 'draft',
                                      'text-rose-600': activeData.status === 'rejected'
                                  }"
                                  x-text="activeData.status"></span>
                        </div>
                    </div>

                    <div>
                        <span class="text-gray-400 block text-[11px] font-semibold mb-1">Judul Kegiatan:</span>
                        <p class="font-semibold text-[#111827]" x-text="activeData.kegiatan"></p>
                    </div>

                    <div>
                        <span class="text-gray-400 block text-[11px] font-semibold mb-1">Deskripsi Lengkap:</span>
                        <div class="bg-[#F9FAFB] p-3 rounded-xl border border-gray-150 whitespace-pre-line text-[#374151] leading-relaxed max-h-40 overflow-y-auto"
                             x-text="activeData.deskripsi"></div>
                    </div>

                    <template x-if="activeData.link_bukti">
                        <div>
                            <span class="text-gray-400 block text-[11px] font-semibold mb-1">Tautan Bukti / Dokumen:</span>
                            <a :href="activeData.link_bukti" target="_blank" class="inline-flex items-center gap-1.5 text-[#E06D28] hover:underline font-semibold break-all">
                                <span>Buka Bukti di Tab Baru</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </div>
                    </template>

                    {{-- Form Verifikasi Admin --}}
                    <form :action="activeData.verify_url" method="POST" class="pt-3 border-t border-gray-100 space-y-3">
                        @csrf
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 mb-1">Catatan Pembimbing / Verifikator:</label>
                            <textarea name="catatan_verifikator" rows="2" 
                                      placeholder="Tuliskan catatan atau feedback..." 
                                      x-model="activeData.catatan_verifikator"
                                      class="w-full text-xs border border-gray-200 rounded-lg p-2 focus:ring-1 focus:ring-orange-500 focus:outline-none"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2">
                            <button type="submit" name="status" value="rejected"
                                    class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold rounded-lg text-xs border border-rose-200 transition">
                                Tolak
                            </button>
                            <button type="submit" name="status" value="approved"
                                    class="px-3.5 py-1.5 bg-[#0FAF62] hover:bg-emerald-700 text-white font-semibold rounded-lg text-xs transition shadow-sm">
                                Setujui Logbook
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function logbookTable() {
        return {
            filterOpen: false,
            modalOpen: false,
            activeData: {},
            selectedIds: [],
            allIds: @json($logbooks->pluck('id')),

            openModal(data) {
                this.activeData = data;
                this.modalOpen = true;
            },

            isAllSelected() {
                const checkboxes = document.querySelectorAll('#logbook-table-container tbody input[type="checkbox"]');
                if (checkboxes.length === 0) return false;
                return Array.from(checkboxes).every(cb => this.selectedIds.includes(parseInt(cb.value)));
            },

            toggleSelectAll(e) {
                const checkboxes = document.querySelectorAll('#logbook-table-container tbody input[type="checkbox"]');
                const ids = Array.from(checkboxes).map(cb => parseInt(cb.value));
                if (e.target.checked) {
                    this.selectedIds = Array.from(new Set([...this.selectedIds, ...ids]));
                } else {
                    this.selectedIds = this.selectedIds.filter(id => !ids.includes(id));
                }
            }
        };
    }
</script>
@endsection
