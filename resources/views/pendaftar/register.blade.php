<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Magang - SiBook</title>

    <!-- Google Fonts Bunny: Figtree untuk tampilan halus & presisi -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>
</head>
<body class="bg-[#FAFAFA] text-[#111827] min-h-screen py-10 px-4">

    <div class="max-w-2xl mx-auto space-y-6">

        {{-- Header Minimalis --}}
        <div class="text-center pt-4 pb-2">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-medium mb-3">
                <span>SiBook</span>
                <span class="text-gray-300">•</span>
                <span>BPS Kota Surabaya</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                Pendaftaran Magang
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Lengkapi formulir di bawah ini untuk mengajukan permohonan magang.
            </p>
        </div>

        {{-- Progres Kelengkapan Formulir (Minimalis) --}}
        <div class="bg-white rounded-xl border border-gray-200/80 p-4 shadow-sm">
            <div class="flex justify-between items-center text-xs font-medium text-gray-600 mb-2">
                <span>Kelengkapan Data</span>
                <span id="progressText" class="font-semibold text-gray-900">0%</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                <div id="progressBar" class="h-1.5 rounded-full bg-gray-900 transition-all duration-300" style="width: 0%;"></div>
            </div>
        </div>

        {{-- Kartu Utama Formulir --}}
        <div class="bg-white rounded-2xl border border-gray-200/80 p-6 sm:p-9 shadow-sm">
            
            {{-- Alert Notifikasi Error --}}
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl flex items-start justify-between">
                    <span>{{ session('error') }}</span>
                    <button type="button" @click="$el.parentElement.remove()" class="text-red-400 hover:text-red-600">&times;</button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 text-xs rounded-xl space-y-1">
                    <p class="font-semibold text-sm mb-1.5">Harap periksa kembali isian Anda:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $pesanError)
                            <li>{{ $pesanError }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="formPendaftaran" action="{{ route('pendaftar.register.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                {{-- ================= BAGIAN 1: DATA DIRI ================= --}}
                <section class="space-y-4">
                    <div class="border-b border-gray-100 pb-2">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-gray-900">1. Data Diri</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Identitas pribadi calon peserta magang</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Nama Lengkap --}}
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                                   placeholder="Masukkan nama lengkap sesuai identitas"
                                   class="w-full text-xs bg-white border border-gray-200 rounded-lg p-2.5 focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900 transition">
                            @error('nama_lengkap')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="jenis_kelamin" value="Laki-laki" required class="peer sr-only" {{ old('jenis_kelamin') == 'Laki-laki' ? 'checked' : '' }}>
                                    <div class="px-4 py-2 text-center rounded-lg border border-gray-200 text-xs font-medium text-gray-600 transition peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white">
                                        Laki-laki
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="jenis_kelamin" value="Perempuan" required class="peer sr-only" {{ old('jenis_kelamin') == 'Perempuan' ? 'checked' : '' }}>
                                    <div class="px-4 py-2 text-center rounded-lg border border-gray-200 text-xs font-medium text-gray-600 transition peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white">
                                        Perempuan
                                    </div>
                                </label>
                            </div>
                            @error('jenis_kelamin')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- No. HP --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                No. WhatsApp / HP <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}" required inputmode="numeric"
                                   placeholder="08xxxxxxxxxx"
                                   class="w-full text-xs bg-white border border-gray-200 rounded-lg p-2.5 focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900 transition">
                            @error('no_hp')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Username Instagram --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                Akun Instagram <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="username_ig" value="{{ old('username_ig') }}" required
                                   placeholder="@username"
                                   class="w-full text-xs bg-white border border-gray-200 rounded-lg p-2.5 focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900 transition">
                            @error('username_ig')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Link Pasfoto --}}
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                Tautan Pasfoto (Google Drive) <span class="text-red-500">*</span>
                            </label>
                            <input type="url" name="link_pasfoto" value="{{ old('link_pasfoto') }}" required
                                   placeholder="https://drive.google.com/..."
                                   class="w-full text-xs bg-white border border-gray-200 rounded-lg p-2.5 focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900 transition">
                            <p class="text-[11px] text-gray-400 mt-1">Pastikan tautan dapat diakses publik (Anyone with the link).</p>
                            @error('link_pasfoto')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </section>

                {{-- ================= BAGIAN 2: DATA AKADEMIK ================= --}}
                <section class="space-y-4 pt-2">
                    <div class="border-b border-gray-100 pb-2">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-gray-900">2. Akademik &amp; Magang</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Asal institusi dan rencana periode pelaksanaan</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Universitas --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                Universitas / Institut <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="universitas" value="{{ old('universitas') }}" required
                                   placeholder="Nama universitas"
                                   class="w-full text-xs bg-white border border-gray-200 rounded-lg p-2.5 focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900 transition">
                            @error('universitas')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Program Studi --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                Program Studi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="program_studi" value="{{ old('program_studi') }}" required
                                   placeholder="Contoh: Statistika / Informatika"
                                   class="w-full text-xs bg-white border border-gray-200 rounded-lg p-2.5 focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900 transition">
                            @error('program_studi')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Status Konversi SKS --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Status Konversi SKS <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="status_konversi" value="0" required class="peer sr-only" {{ old('status_konversi') == '0' ? 'checked' : '' }}>
                                    <div class="px-3 py-2 text-center rounded-lg border border-gray-200 text-xs font-medium text-gray-600 transition peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white">
                                        Ya
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="status_konversi" value="1" required class="peer sr-only" {{ old('status_konversi') == '1' ? 'checked' : '' }}>
                                    <div class="px-3 py-2 text-center rounded-lg border border-gray-200 text-xs font-medium text-gray-600 transition peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white">
                                        Tidak
                                    </div>
                                </label>
                            </div>
                            @error('status_konversi')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Perangkat / Device --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                Perangkat Utama <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="device" value="{{ old('device') }}" required
                                   placeholder="Contoh: Laptop Windows / MacBook"
                                   class="w-full text-xs bg-white border border-gray-200 rounded-lg p-2.5 focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900 transition">
                            @error('device')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Periode Tanggal --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                Tanggal Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="tanggalMulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                                   class="w-full text-xs bg-white border border-gray-200 rounded-lg p-2.5 focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900 transition">
                            @error('tanggal_mulai')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                Tanggal Selesai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="tanggalSelesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required
                                   class="w-full text-xs bg-white border border-gray-200 rounded-lg p-2.5 focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900 transition">
                            @error('tanggal_selesai')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- BPJS --}}
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Kepemilikan BPJS Ketenagakerjaan <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="punya_bpjs" value="0" required class="peer sr-only" {{ old('punya_bpjs') == '0' ? 'checked' : '' }}>
                                    <div class="px-4 py-2 text-center rounded-lg border border-gray-200 text-xs font-medium text-gray-600 transition peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white">
                                        Sudah Punya
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="punya_bpjs" value="1" required class="peer sr-only" {{ old('punya_bpjs') == '1' ? 'checked' : '' }}>
                                    <div class="px-4 py-2 text-center rounded-lg border border-gray-200 text-xs font-medium text-gray-600 transition peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white">
                                        Belum Punya
                                    </div>
                                </label>
                            </div>
                            @error('punya_bpjs')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </section>

                {{-- ================= BAGIAN 3: BERKAS ================= --}}
                <section class="space-y-4 pt-2">
                    <div class="border-b border-gray-100 pb-2">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-gray-900">3. Berkas Pendukung</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Surat pengantar, CV, dan dokumen kelengkapan lainnya</p>
                    </div>

                    <div class="p-3.5 bg-gray-50 border border-gray-200/80 rounded-xl text-xs text-gray-600 leading-relaxed">
                        <span class="font-semibold text-gray-800">Catatan Berkas:</span>
                        Gabungkan dokumen persyaratan (Surat Pengantar Kampus, CV, Portofolio/Transkrip) menjadi <b>1 file PDF, ZIP, atau RAR</b> (maks. 5MB).
                    </div>

                    {{-- Dropzone File --}}
                    <label id="dropzone" for="inputDokumen"
                           class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50/50 hover:bg-gray-50 hover:border-gray-400 cursor-pointer transition">
                        <input type="file" id="inputDokumen" name="dokumen" required accept=".pdf,.zip,.rar" class="sr-only">
                        <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                        </svg>
                        <p class="text-xs font-semibold text-gray-800">Pilih file atau tarik berkas ke sini</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">Format: PDF, ZIP, RAR</p>
                    </label>
                    @error('dokumen')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror

                    {{-- Status File Terpilih --}}
                    <div id="fileInfo" class="hidden items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-xl text-xs">
                        <div class="flex items-center gap-2 truncate">
                            <span class="font-semibold text-gray-800 truncate" id="fileName">-</span>
                            <span class="text-gray-400" id="fileSize">-</span>
                        </div>
                        <button type="button" id="btnHapusFile" class="text-xs text-red-600 hover:text-red-700 font-medium ml-2">
                            Hapus
                        </button>
                    </div>
                </section>

                {{-- ================= BAGIAN 4: AKUN AKSES ================= --}}
                <section class="space-y-4 pt-2">
                    <div class="border-b border-gray-100 pb-2">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-gray-900">4. Akun Akses</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Digunakan untuk login dan memantau status seleksi</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Email --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                Alamat Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   placeholder="nama@email.com"
                                   class="w-full text-xs bg-white border border-gray-200 rounded-lg p-2.5 focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900 transition">
                            @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Password --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                Kata Sandi <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="inputPassword" name="password" required minlength="8"
                                       placeholder="Minimal 8 karakter"
                                       class="w-full text-xs bg-white border border-gray-200 rounded-lg p-2.5 pr-9 focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900 transition">
                                <button type="button" id="btnTogglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-700">
                                    <svg id="ikonMataBuka" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg id="ikonMataTutup" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m14.82 8.82L21.836 21" />
                                    </svg>
                                </button>
                            </div>
                            
                            {{-- Indikator kekuatan password --}}
                            <div class="mt-2 hidden" id="barisKekuatan">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-100 rounded-full h-1 overflow-hidden">
                                        <div id="kekuatanBar" class="h-1 rounded-full transition-all duration-300" style="width: 0%;"></div>
                                    </div>
                                    <span id="kekuatanLabel" class="text-[10px] font-semibold text-gray-400">-</span>
                                </div>
                            </div>
                            @error('password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </section>

                {{-- ================= TOMBOL AKSI ================= --}}
                <div class="border-t border-gray-100 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <a href="{{ route('home') }}" class="w-full sm:w-auto px-5 py-2.5 text-center text-xs font-semibold text-gray-600 hover:text-gray-900 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" id="btnKirim" class="w-full sm:w-auto px-6 py-2.5 text-center text-xs font-semibold text-white bg-gray-900 hover:bg-black rounded-lg transition shadow-sm flex items-center justify-center gap-2 disabled:opacity-60">
                        <svg id="ikonSpinner" class="w-3.5 h-3.5 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span id="teksKirim">Kirim Formulir Pendaftaran</span>
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} SiBook • Badan Pusat Statistik Kota Surabaya
        </p>

    </div>

    {{-- Script Validasi & Interaksi --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form      = document.getElementById('formPendaftaran');
            const btnKirim  = document.getElementById('btnKirim');
            const teksKirim = document.getElementById('teksKirim');

            /* Progres Kelengkapan */
            const progressBar  = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');

            function perbaruiProgres() {
                const names = [...new Set([...form.querySelectorAll('[required]')].map(el => el.name))];
                let terisi = 0;

                names.forEach(name => {
                    const elemen = form.querySelectorAll(`[name="${name}"]`)[0];
                    if (elemen.type === 'radio') {
                        if (form.querySelector(`[name="${name}"]:checked`)) terisi++;
                    } else if (elemen.type === 'file') {
                        if (elemen.files.length > 0) terisi++;
                    } else if (elemen.value.trim() !== '') {
                        terisi++;
                    }
                });

                const persen = names.length ? Math.round((terisi / names.length) * 100) : 0;
                progressBar.style.width = persen + '%';
                progressText.textContent = persen + '%';
            }
            form.addEventListener('input', perbaruiProgres);
            form.addEventListener('change', perbaruiProgres);
            perbaruiProgres();

            /* Batas Tanggal */
            const tanggalMulai   = document.getElementById('tanggalMulai');
            const tanggalSelesai = document.getElementById('tanggalSelesai');
            const hariIniISO     = new Date().toISOString().split('T')[0];

            if (tanggalMulai) {
                tanggalMulai.min = hariIniISO;
                tanggalMulai.addEventListener('change', function () {
                    if (this.value) tanggalSelesai.min = this.value;
                });
            }

            /* Dropzone File */
            const dropzone   = document.getElementById('dropzone');
            const inputDok   = document.getElementById('inputDokumen');
            const fileInfo   = document.getElementById('fileInfo');
            const fileNameEl = document.getElementById('fileName');
            const fileSizeEl = document.getElementById('fileSize');

            function tampilkanFile(file) {
                if (!file) return;
                fileNameEl.textContent = file.name;
                fileSizeEl.textContent = '(' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
                fileInfo.classList.remove('hidden');
                fileInfo.classList.add('flex');
            }

            inputDok.addEventListener('change', function () {
                if (this.files.length > 0) tampilkanFile(this.files[0]);
            });

            ['dragover', 'dragenter'].forEach(ev => dropzone.addEventListener(ev, function (e) {
                e.preventDefault();
                dropzone.classList.add('border-gray-900', 'bg-gray-100');
            }));

            ['dragleave', 'drop'].forEach(ev => dropzone.addEventListener(ev, function (e) {
                e.preventDefault();
                dropzone.classList.remove('border-gray-900', 'bg-gray-100');
            }));

            dropzone.addEventListener('drop', function (e) {
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    inputDok.files = files;
                    tampilkanFile(files[0]);
                    perbaruiProgres();
                }
            });

            document.getElementById('btnHapusFile').addEventListener('click', function () {
                inputDok.value = '';
                fileInfo.classList.add('hidden');
                fileInfo.classList.remove('flex');
                perbaruiProgres();
            });

            /* Toggle Password */
            const inputPassword = document.getElementById('inputPassword');
            const ikonBuka  = document.getElementById('ikonMataBuka');
            const ikonTutup = document.getElementById('ikonMataTutup');

            document.getElementById('btnTogglePassword').addEventListener('click', function () {
                const tampil = inputPassword.type === 'password';
                inputPassword.type = tampil ? 'text' : 'password';
                ikonBuka.classList.toggle('hidden', tampil);
                ikonTutup.classList.toggle('hidden', !tampil);
            });

            /* Kekuatan Password */
            const barisKekuatan = document.getElementById('barisKekuatan');
            const kekuatanBar   = document.getElementById('kekuatanBar');
            const kekuatanLabel = document.getElementById('kekuatanLabel');

            inputPassword.addEventListener('input', function () {
                const v = inputPassword.value;
                barisKekuatan.classList.toggle('hidden', v.length === 0);

                let skor = 0;
                if (v.length >= 8) skor++;
                if (/[A-Z]/.test(v)) skor++;
                if (/[0-9]/.test(v)) skor++;
                if (/[^A-Za-z0-9]/.test(v)) skor++;

                const tingkat = [
                    { w: '25%',  c: 'bg-red-500',    t: 'Lemah' },
                    { w: '50%',  c: 'bg-amber-500',  t: 'Cukup' },
                    { w: '75%',  c: 'bg-blue-500',   t: 'Baik' },
                    { w: '100%', c: 'bg-emerald-500', t: 'Kuat' },
                ][Math.max(0, skor - 1)] || { w: '0%', c: 'bg-gray-300', t: '-' };

                kekuatanBar.className = 'h-1 rounded-full transition-all duration-300 ' + tingkat.c;
                kekuatanBar.style.width = v.length ? tingkat.w : '0%';
                kekuatanLabel.textContent = v.length ? tingkat.t : '-';
            });

            /* Anti Double Submit */
            form.addEventListener('submit', function () {
                if (btnKirim.disabled) return;
                btnKirim.disabled = true;
                teksKirim.textContent = 'Mengirim...';
                document.getElementById('ikonSpinner').classList.remove('hidden');
            });
        });
    </script>
</body>
</html>
