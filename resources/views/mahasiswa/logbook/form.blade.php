@extends('layouts.role', ['role' => 'mahasiswa'])

@section('title', $mode === 'create' ? 'Buat Logbook' : ($mode === 'lanjutkan' ? 'Lanjutkan Logbook' : 'Edit Logbook'))

@section('content')
<div class="max-w-3xl mx-auto space-y-4">
    <nav class="flex items-center gap-2 text-xs text-gray-500 font-medium">
        <a href="{{ route('mahasiswa.logbook.index') }}" class="hover:text-gray-800">Logbook</a>
        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-400">
            @if($mode === 'create') Buat Baru
            @elseif($mode === 'lanjutkan') Lanjutkan
            @else Edit @endif
        </span>
    </nav>

    <div class="bg-white rounded-2xl shadow-[0_1px_3px_rgba(0,0,0,0.02)] border border-gray-150 p-6 sm:p-8">
        <div class="pb-5 mb-6 border-b border-gray-100">
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">
                @if($mode === 'create') Buat Entri Logbook Baru
                @elseif($mode === 'lanjutkan') Lanjutkan Aktivitas Proyek
                @else Edit Catatan Logbook @endif
            </h1>
            <p class="text-xs text-gray-500 mt-1">Isi rincian aktivitas dan pekerjaan yang Anda laksanakan hari ini.</p>
        </div>

        @if($mode === 'lanjutkan')
            <div class="mb-6 p-4 bg-orange-50/60 border border-orange-200 text-orange-800 rounded-xl text-xs flex items-start gap-2.5">
                <svg class="w-4 h-4 text-orange-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <span class="font-bold">Mode Lanjutkan:</span> Anda sedang melanjutkan proyek <strong>"{{ $parent->kegiatan }}"</strong>. Nama kegiatan terkunci agar terhubung sebagai satu rangkaian proyek.
                </div>
            </div>
        @endif

        <form action="{{ $mode === 'edit' ? route('mahasiswa.logbook.update', $logbook->id) : route('mahasiswa.logbook.store') }}" method="POST" class="space-y-5">
            @csrf
            @if($mode === 'edit') @method('PUT') @endif
            
            @if($mode === 'lanjutkan')
                <input type="hidden" name="parent_id" value="{{ $parent->id }}">
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Tim --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tugas dari Tim / Divisi</label>
                    <select name="tim" required class="w-full text-xs border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:outline-none focus:ring-1 focus:ring-orange-500">
                        <option value="">-- Pilih Tim --</option>
                        @foreach(['Distribusi', 'ZI', 'PSS', 'POTIK', 'Sosial', 'TU', 'Neraca', 'Humas', 'IPDS', 'Umum', 'UKK'] as $tim)
                            <option value="{{ $tim }}" {{ (old('tim', $parent->tim ?? ($logbook->tim ?? '')) == $tim) ? 'selected' : '' }}>{{ $tim }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Jenis GSBPM --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Jenis GSBPM</label>
                    <select name="jenis_gsbpm" required class="w-full text-xs border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:outline-none focus:ring-1 focus:ring-orange-500">
                        <option value="">-- Pilih GSBPM --</option>
                        @foreach(['Specify Needs', 'Design', 'Build', 'Collect', 'Process', 'Analyse', 'Disseminate', 'Evaluate'] as $gsbpm)
                            <option value="{{ $gsbpm }}" {{ (old('jenis_gsbpm', $parent->jenis_gsbpm ?? ($logbook->jenis_gsbpm ?? '')) == $gsbpm) ? 'selected' : '' }}>{{ $gsbpm }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tanggal --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Pengerjaan</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', isset($logbook) ? $logbook->tanggal->format('Y-m-d') : date('Y-m-d')) }}" required 
                           class="w-full text-xs border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:outline-none focus:ring-1 focus:ring-orange-500">
                </div>

                {{-- Jam Mulai & Selesai --}}
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Jam Mulai</label>
                        <input type="time" name="jam_mulai" value="{{ old('jam_mulai', isset($logbook) ? substr($logbook->jam_mulai, 0, 5) : '') }}" required 
                               class="w-full text-xs border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:outline-none focus:ring-1 focus:ring-orange-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Jam Selesai</label>
                        <input type="time" name="jam_selesai" value="{{ old('jam_selesai', isset($logbook) && $logbook->jam_selesai ? substr($logbook->jam_selesai, 0, 5) : '') }}" 
                               class="w-full text-xs border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:outline-none focus:ring-1 focus:ring-orange-500">
                    </div>
                </div>
            </div>

            {{-- Nama Kegiatan --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama / Judul Kegiatan</label>
                <input type="text" name="kegiatan" value="{{ old('kegiatan', $parent->kegiatan ?? ($logbook->kegiatan ?? '')) }}" required 
                       class="w-full text-xs border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:outline-none focus:ring-1 focus:ring-orange-500 {{ $mode === 'lanjutkan' ? 'bg-gray-50 text-gray-500 cursor-not-allowed' : '' }}" {{ $mode === 'lanjutkan' ? 'readonly' : '' }}>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi Aktivitas</label>
                <textarea name="deskripsi" rows="4" required 
                          class="w-full text-xs border border-gray-200 rounded-xl p-3 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-orange-500" 
                          placeholder="Jelaskan secara detail hasil pengerjaan atau tugas yang Anda selesaikan...">{{ old('deskripsi', $logbook->deskripsi ?? '') }}</textarea>
            </div>

            {{-- Link Bukti --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Tautan Bukti Dokumen (Google Drive, dll)</label>
                <input type="url" name="link_bukti" value="{{ old('link_bukti', $logbook->link_bukti ?? '') }}" required placeholder="https://..." 
                       class="w-full text-xs border border-gray-200 rounded-xl px-3 py-2 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-orange-500">
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex flex-col sm:flex-row justify-end gap-2.5 pt-5 border-t border-gray-100">
                <a href="{{ route('mahasiswa.logbook.index') }}" 
                   class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition text-center">
                    Batal
                </a>
                
                <button type="submit" name="is_selesai" value="0" 
                        class="px-4 py-2 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 text-xs font-bold rounded-xl transition">
                    Simpan sebagai Draf
                </button>
                <button type="submit" name="is_selesai" value="1" 
                        class="px-5 py-2 bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                    Kirim &amp; Selesaikan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnSelesai = document.querySelector('button[name="is_selesai"][value="1"]');
        const inputJamSelesai = document.querySelector('input[name="jam_selesai"]');

        if(btnSelesai && inputJamSelesai) {
            btnSelesai.addEventListener('click', function(e) {
                if(inputJamSelesai.value === '') {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    inputJamSelesai.value = `${hours}:${minutes}`;
                }
            });
        }
    });
</script>
@endsection
