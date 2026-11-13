@extends('layouts.role', ['role' => 'mahasiswa'])

@section('title', $mode === 'create' ? 'Buat Logbook' : ($mode === 'lanjutkan' ? 'Lanjutkan Logbook' : 'Edit Logbook'))

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            @if($mode === 'create') Buat Logbook Baru
            @elseif($mode === 'lanjutkan') Lanjutkan Logbook
            @else Edit Logbook @endif
        </h2>

        @if($mode === 'lanjutkan')
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-xl text-sm">
                Anda sedang melanjutkan project <strong>"{{ $parent->kegiatan }}"</strong>. Judul Kegiatan dikunci, namun Anda dapat mengisi tanggal dan jam untuk aktivitas hari ini.
            </div>
        @endif

        {{-- Arahkan action ke store jika create/lanjutkan, ke update jika edit --}}
        <form action="{{ $mode === 'edit' ? route('mahasiswa.logbook.update', $logbook->id) : route('mahasiswa.logbook.store') }}" method="POST" class="space-y-6">
            @csrf
            @if($mode === 'edit') @method('PUT') @endif
            
            {{-- Hidden parent_id HANYA untuk mode lanjutkan --}}
            @if($mode === 'lanjutkan')
                <input type="hidden" name="parent_id" value="{{ $parent->id }}">
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tugas dari Tim (Bisa diubah, tapi otomatis terisi dari parent) -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tugas dari Tim</label>
                    <select name="tim" required class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Pilih Tim --</option>
                        @foreach(['Distribusi', 'ZI', 'PSS', 'POTIK', 'Sosial', 'TU', 'Neraca', 'Humas', 'IPDS', 'Umum', 'UKK'] as $tim)
                            <option value="{{ $tim }}" {{ (old('tim', $parent->tim ?? ($logbook->tim ?? '')) == $tim) ? 'selected' : '' }}>{{ $tim }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Jenis GSBPM (Bisa diubah, otomatis terisi) -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jenis GSBPM</label>
                    <select name="jenis_gsbpm" required class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Pilih GSBPM --</option>
                        @foreach(['Specify Needs', 'Design', 'Build', 'Collect', 'Process', 'Analyse', 'Disseminate', 'Evaluate'] as $gsbpm)
                            <option value="{{ $gsbpm }}" {{ (old('jenis_gsbpm', $parent->jenis_gsbpm ?? ($logbook->jenis_gsbpm ?? '')) == $gsbpm) ? 'selected' : '' }}>{{ $gsbpm }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal (Bisa diubah, default ke hari ini) -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Pengerjaan</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', isset($logbook) ? $logbook->tanggal->format('Y-m-d') : date('Y-m-d')) }}" required class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Jam Mulai (Bisa diubah, kosongkan untuk diisi hari ini) -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jam Mulai</label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai', isset($logbook) ? $logbook->jam_mulai : '') }}" required class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Jam Selesai (Bisa diubah) -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jam Selesai</label>
                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai', $logbook->jam_selesai ?? '') }}" class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="text-[11px] text-gray-400 mt-1">Wajib diisi jika Anda menekan tombol Selesai.</p>
                </div>
            </div>

            <!-- Nama Kegiatan (DIKUNCI HANYA SAAT MODE LANJUTKAN) -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Kegiatan</label>
                <input type="text" name="kegiatan" value="{{ old('kegiatan', $parent->kegiatan ?? ($logbook->kegiatan ?? '')) }}" required class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 {{ $mode === 'lanjutkan' ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' }}" {{ $mode === 'lanjutkan' ? 'readonly' : '' }}>
                @if($mode === 'lanjutkan')
                    <p class="text-[11px] text-red-500 mt-1">*Nama kegiatan tidak dapat diubah karena melanjutkan proyek sebelumnya.</p>
                @endif
            </div>

            <!-- Deskripsi (Bisa diubah) -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan / Deskripsi Pekerjaan</label>
                <textarea name="deskripsi" rows="4" required class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500" placeholder="Tuliskan aktivitas yang Anda kerjakan hari ini...">{{ old('deskripsi', $logbook->deskripsi ?? '') }}</textarea>
            </div>

            <!-- Link Bukti (Bisa diubah) -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Link Bukti (Google Drive)</label>
                <input type="url" name="link_bukti" value="{{ old('link_bukti', $logbook->link_bukti ?? '') }}" required placeholder="https://drive.google.com/..." class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Tombol Aksi -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t mt-8">
                <a href="{{ route('mahasiswa.logbook.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 text-center">Batal</a>
                
                <button type="submit" name="is_selesai" value="0" class="px-6 py-3 bg-orange-100 text-orange-700 font-bold rounded-xl hover:bg-orange-200 transition-colors">
                    Simpan Draf
                </button>
                <button type="submit" name="is_selesai" value="1" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-md">
                    Tugas Selesai
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