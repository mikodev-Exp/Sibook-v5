@extends('layouts.role', ['role' => 'admin'])

@section('title', 'Mahasiswa Magang')

@section('content')
<div class="max-w-[1400px] mx-auto space-y-4" x-data="mahasiswaAdmin()">

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
         class="bg-white rounded-2xl border border-gray-150 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden flex flex-col min-h-[580px] lg:min-h-[calc(100vh-220px)]">
        
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
        <div class="overflow-x-auto flex-1">
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

                            {{-- Periode & Status --}}
                            <td class="py-4 px-3 whitespace-nowrap">
                                @if($magang)
                                    @php
                                        $mulai = $magang->tanggal_mulai;
                                        $selesai = $magang->tanggal_selesai;
                                        $isExpired = today()->gt($selesai) || $magang->status === \App\Enums\StatusMagang::Completed;
                                        $isNotStarted = today()->lt($mulai);
                                        $isActive = !$isExpired && !$isNotStarted && $magang->status === \App\Enums\StatusMagang::Active;
                                    @endphp
                                    <div class="text-xs font-semibold text-gray-800">
                                        {{ $mulai->format('d/m/Y') }} – {{ $selesai->format('d/m/Y') }}
                                    </div>
                                    <div class="mt-1">
                                        @if($isActive)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Aktif
                                            </span>
                                        @elseif($isNotStarted)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Belum Mulai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Selesai (Nonaktif)
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Belum diset</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="py-4 pl-3 pr-6 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5 justify-end">
                                    {{-- Tombol Edit / Aktifkan Periode --}}
                                    @php
                                        $btnText = ($magang && (today()->gt($magang->tanggal_selesai) || $magang->status === \App\Enums\StatusMagang::Completed)) ? 'Aktifkan' : 'Periode';
                                    @endphp
                                    <button type="button"
                                            @click="openPeriodeModal({
                                                id: {{ $mhs->id }},
                                                name: '{{ addslashes($mhs->name) }}',
                                                mulai: '{{ $magang?->tanggal_mulai?->format('Y-m-d') ?? '' }}',
                                                selesai: '{{ $magang?->tanggal_selesai?->format('Y-m-d') ?? '' }}',
                                                status: '{{ $magang?->status?->value ?? 'active' }}',
                                                no_surat: '{{ addslashes($magang?->no_surat ?? '') }}'
                                            })"
                                            class="no-async inline-flex items-center gap-1 px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 rounded-lg text-xs font-semibold transition shadow-sm"
                                            title="Edit Periode / Aktifkan Kembali">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>{{ $btnText }}</span>
                                    </button>

                                    {{-- Tombol Laporan --}}
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

        {{-- Footer Informasi & Pagination --}}
        <div class="mt-auto px-5 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-2 bg-gray-50/40">
            <div class="text-xs text-gray-500">
                @if($mahasiswas->total() > 0)
                    Menampilkan {{ $mahasiswas->firstItem() }} - {{ $mahasiswas->lastItem() }} dari {{ $mahasiswas->total() }} mahasiswa
                @else
                    Tidak ada data mahasiswa
                @endif
            </div>
            @if($mahasiswas->hasPages())
                <div>
                    {{ $mahasiswas->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════════
         MODAL – Edit & Aktifkan Periode Magang
    ════════════════════════════════════════════════════════════════════════════ --}}
    <div x-show="modalOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="modalOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
         style="display:none;">

        <div @click.stop
             x-show="modalOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
             class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-md overflow-hidden">

            {{-- Modal Header --}}
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-orange-50/60 to-amber-50/40">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-orange-500 text-white flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-gray-900">Atur Periode Magang</h3>
                        <p class="text-[11px] text-gray-500" x-text="form.name"></p>
                    </div>
                </div>
                <button type="button" @click="modalOpen = false" class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Modal Body Form --}}
            <form @submit.prevent="submitPeriode($event)">
                @csrf
                <div class="p-6 space-y-4">
                    {{-- Info Box --}}
                    <div class="flex items-start gap-2.5 p-3 rounded-xl bg-blue-50 border border-blue-100 text-blue-800 text-[11.5px] leading-relaxed">
                        <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Mahasiswa hanya dapat presensi pada rentang tanggal masuk &amp; keluar ini. Jika ingin magang kembali, perbarui tanggal masuk &amp; keluar lalu simpan.</span>
                    </div>

                    {{-- Tanggal Masuk & Keluar --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Masuk (Mulai) <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal_mulai" x-model="form.tanggal_mulai" required
                                   class="w-full px-3 py-2 text-xs border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 shadow-sm transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Keluar (Selesai) <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal_selesai" x-model="form.tanggal_selesai" required
                                   class="w-full px-3 py-2 text-xs border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 shadow-sm transition">
                        </div>
                    </div>

                    {{-- Status Magang --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Status Keaktifan Magang <span class="text-rose-500">*</span></label>
                        <select name="status" x-model="form.status" required
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 shadow-sm transition bg-white">
                            <option value="active">🟢 Aktif (Bisa Presensi &amp; Akses Fitur Mahasiswa)</option>
                            <option value="completed">⚪ Selesai (Nonaktif)</option>
                            <option value="terminated">🔴 Dihentikan / Nonaktif</option>
                        </select>
                    </div>

                    {{-- No Surat --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor Surat Tugas / Penerimaan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="no_surat" x-model="form.no_surat" placeholder="Contoh: B-120/02340/KP.010/2026"
                               class="w-full px-3 py-2 text-xs border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 shadow-sm transition">
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-2.5">
                    <button type="button" @click="modalOpen = false"
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-200/70 transition">
                        Batal
                    </button>
                    <button type="submit" :disabled="loading"
                            class="px-5 py-2 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold transition shadow-sm shadow-orange-500/20 disabled:opacity-60 flex items-center gap-1.5">
                        <span x-show="!loading">Simpan &amp; Terapkan</span>
                        <span x-show="loading" style="display:none;">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
function mahasiswaAdmin() {
    return {
        modalOpen: false,
        loading: false,
        form: {
            user_id: null,
            name: '',
            tanggal_mulai: '',
            tanggal_selesai: '',
            status: 'active',
            no_surat: ''
        },
        openPeriodeModal(data) {
            this.form.user_id = data.id;
            this.form.name = data.name;
            this.form.tanggal_mulai = data.mulai;
            this.form.tanggal_selesai = data.selesai;
            this.form.status = data.status || 'active';
            this.form.no_surat = data.no_surat || '';
            this.modalOpen = true;
        },
        async submitPeriode(event) {
            if (this.loading) return;
            this.loading = true;
            const formEl = event.target;
            const formData = new FormData(formEl);
            formData.set('_method', 'PATCH');

            const url = '{{ route("admin.mahasiswa.update-periode", 999999) }}'.replace('999999', this.form.user_id);

            try {
                const resp = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-HTTP-Method-Override': 'PATCH',
                    },
                    body: formData,
                });
                const res = await resp.json().catch(() => ({}));
                if (resp.ok) {
                    this.modalOpen = false;
                    if (window.SiBookAsync) {
                        SiBookAsync.load(window.location.href, 'mahasiswa-table-container');
                    } else {
                        window.location.reload();
                    }
                } else {
                    alert(res.message || 'Gagal memperbarui periode magang.');
                }
            } catch (e) {
                alert('Terjadi kesalahan jaringan.');
            } finally {
                this.loading = false;
            }
        }
    };
}
</script>
@endpush
@endsection
