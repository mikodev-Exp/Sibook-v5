<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Magang - SiBook BPS Kota Surabaya</title>

    <!-- Google Fonts Bunny: Figtree & Space Mono untuk estetika Gallery NFT -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800|space-mono:400,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .font-mono-tag {
            font-family: 'Space Mono', monospace, ui-monospace;
        }
    </style>
</head>
<body class="bg-[#FAFAFA] text-zinc-900 min-h-screen py-12 px-4 selection:bg-zinc-950 selection:text-white">

    <div class="max-w-2xl mx-auto space-y-6">

        {{-- Top Navigation & Home Link --}}
        <div class="flex items-center justify-between pb-2">
            <a href="{{ route('home') }}" 
               class="inline-flex items-center gap-2 text-xs font-mono-tag text-zinc-500 hover:text-zinc-950 transition-colors py-1.5 px-3 rounded-lg hover:bg-zinc-100">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>BERANDA UTAMA</span>
            </a>

            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-zinc-100 border border-zinc-200/80 text-[11px] font-mono-tag text-zinc-600">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>MINTING TERMINAL // OPEN</span>
            </div>
        </div>

        {{-- Header Minimalis Placard --}}
        <div class="bg-white rounded-3xl border border-zinc-200/80 p-8 shadow-sm text-center relative overflow-hidden">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-zinc-100 text-zinc-700 text-xs font-mono-tag mb-4">
                <span>SIBOOK [V5.0]</span>
                <span class="text-zinc-300">•</span>
                <span>BPS KOTA SURABAYA</span>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight text-zinc-950">
                Formulir Pendaftaran Magang
            </h1>
            <p class="text-xs text-zinc-500 mt-2 max-w-md mx-auto leading-relaxed">
                Lengkapi seluruh parameter data di bawah ini untuk menerbitkan <span class="font-mono-tag font-semibold text-zinc-800">Applicant Pass</span> dan mengajukan berkas seleksi kurasi.
            </p>
        </div>

        {{-- Progres Kelengkapan Formulir (Minimalist Meter) --}}
        <div class="bg-white rounded-2xl border border-zinc-200/80 p-5 shadow-xs">
            <div class="flex justify-between items-center text-xs font-mono-tag text-zinc-500 mb-2.5">
                <span class="uppercase tracking-wider">COMPLETION STATUS</span>
                <span id="progressText" class="font-bold text-zinc-950">0%</span>
            </div>
            <div class="w-full bg-zinc-100 rounded-full h-2 overflow-hidden p-0.5 border border-zinc-200/50">
                <div id="progressBar" class="h-full rounded-full bg-zinc-950 transition-all duration-300" style="width: 0%;"></div>
            </div>
        </div>

        {{-- Kartu Utama Formulir --}}
        <div class="bg-white rounded-3xl border border-zinc-200/80 p-7 sm:p-10 shadow-sm">
            
            {{-- Alert Notifikasi Error --}}
            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-2xl flex items-start justify-between font-mono-tag">
                    <span>{{ session('error') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-600">&times;</button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-5 bg-rose-50 border border-rose-200 text-rose-800 text-xs rounded-2xl space-y-2">
                    <p class="font-bold font-mono-tag text-xs tracking-wider uppercase">// PERIKSA KEMBALI ISIAN ANDA:</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        @foreach($errors->all() as $pesanError)
                            <li>{{ $pesanError }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="formPendaftaran" action="{{ route('pendaftar.register.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                @csrf

                {{-- ================= BAGIAN 1: DATA DIRI ================= --}}
                <section class="space-y-5">
                    <div class="border-b border-zinc-100 pb-3 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-widest block mb-0.5">SECTION 01</span>
                            <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-950">Data Pribadi Calon Peserta</h2>
                        </div>
                        <span class="text-[11px] font-mono-tag text-zinc-400">IDENTITY</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Nama Lengkap --}}
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                                   placeholder="Masukkan nama lengkap sesuai KTP / KTM"
                                   class="w-full text-xs bg-white border border-zinc-200 rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 transition">
                            @error('nama_lengkap')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">
                                Jenis Kelamin <span class="text-rose-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="jenis_kelamin" value="Laki-laki" required class="peer sr-only" {{ old('jenis_kelamin') == 'Laki-laki' ? 'checked' : '' }}>
                                    <div class="px-4 py-2.5 text-center rounded-xl border border-zinc-200 text-xs font-medium text-zinc-600 transition peer-checked:border-zinc-950 peer-checked:bg-zinc-950 peer-checked:text-white">
                                        Laki-laki
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="jenis_kelamin" value="Perempuan" required class="peer sr-only" {{ old('jenis_kelamin') == 'Perempuan' ? 'checked' : '' }}>
                                    <div class="px-4 py-2.5 text-center rounded-xl border border-zinc-200 text-xs font-medium text-zinc-600 transition peer-checked:border-zinc-950 peer-checked:bg-zinc-950 peer-checked:text-white">
                                        Perempuan
                                    </div>
                                </label>
                            </div>
                            @error('jenis_kelamin')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- No. HP --}}
                        <div>
                            <label class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">
                                No. WhatsApp / HP <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}" required inputmode="numeric"
                                   placeholder="08xxxxxxxxxx"
                                   class="w-full text-xs bg-white border border-zinc-200 rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 transition font-mono-tag">
                            @error('no_hp')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Username Instagram --}}
                        <div>
                            <label class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">
                                Akun Instagram <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="username_ig" value="{{ old('username_ig') }}" required
                                   placeholder="@username"
                                   class="w-full text-xs bg-white border border-zinc-200 rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 transition">
                            @error('username_ig')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Link Pasfoto --}}
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">
                                Tautan Pasfoto Resmi (Google Drive) <span class="text-rose-500">*</span>
                            </label>
                            <input type="url" name="link_pasfoto" value="{{ old('link_pasfoto') }}" required
                                   placeholder="https://drive.google.com/..."
                                   class="w-full text-xs bg-white border border-zinc-200 rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 transition font-mono-tag">
                            <p class="text-[11px] text-zinc-400 mt-1 font-mono-tag">Pastikan akses link diatur ke 'Anyone with the link' (Publik).</p>
                            @error('link_pasfoto')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </section>

                {{-- ================= BAGIAN 2: DATA AKADEMIK ================= --}}
                <section class="space-y-5 pt-2">
                    <div class="border-b border-zinc-100 pb-3 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-widest block mb-0.5">SECTION 02</span>
                            <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-950">Profil Akademik &amp; Rencana Magang</h2>
                        </div>
                        <span class="text-[11px] font-mono-tag text-zinc-400">ACADEMICS</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Universitas --}}
                        <div>
                            <label class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">
                                Universitas / Institut <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="universitas" value="{{ old('universitas') }}" required
                                   placeholder="Nama universitas"
                                   class="w-full text-xs bg-white border border-zinc-200 rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 transition">
                            @error('universitas')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Program Studi --}}
                        <div>
                            <label class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">
                                Program Studi / Jurusan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="program_studi" value="{{ old('program_studi') }}" required
                                   placeholder="Contoh: Statistika / Informatika"
                                   class="w-full text-xs bg-white border border-zinc-200 rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 transition">
                            @error('program_studi')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Dosen Pembimbing --}}
                        <div>
                            <label class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">
                                Nama Dosen Pembimbing <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="nama_dosen_pembimbing" value="{{ old('nama_dosen_pembimbing') }}" required
                                   placeholder="Nama lengkap & gelar dosen"
                                   class="w-full text-xs bg-white border border-zinc-200 rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 transition">
                            @error('nama_dosen_pembimbing')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- No. HP Dosen Pembimbing --}}
                        <div>
                            <label class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">
                                Kontak Dosen Pembimbing <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="no_hp_dosen_pembimbing" value="{{ old('no_hp_dosen_pembimbing') }}" required inputmode="numeric"
                                   placeholder="08xxxxxxxxxx"
                                   class="w-full text-xs bg-white border border-zinc-200 rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 transition font-mono-tag">
                            @error('no_hp_dosen_pembimbing')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Status Konversi SKS --}}
                        <div>
                            <label class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">
                                Status Konversi SKS <span class="text-rose-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="status_konversi" value="1" required class="peer sr-only" {{ old('status_konversi', '1') == '1' ? 'checked' : '' }}>
                                    <div class="px-3 py-2.5 text-center rounded-xl border border-zinc-200 text-xs font-medium text-zinc-600 transition peer-checked:border-zinc-950 peer-checked:bg-zinc-950 peer-checked:text-white">
                                        Ya (Konversi SKS)
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="status_konversi" value="0" required class="peer sr-only" {{ old('status_konversi') === '0' ? 'checked' : '' }}>
                                    <div class="px-3 py-2.5 text-center rounded-xl border border-zinc-200 text-xs font-medium text-zinc-600 transition peer-checked:border-zinc-950 peer-checked:bg-zinc-950 peer-checked:text-white">
                                        Tidak (Reguler)
                                    </div>
                                </label>
                            </div>
                            @error('status_konversi')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Perangkat / Device --}}
                        <div>
                            <label class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">
                                Perangkat Kerja Utama <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="device" value="{{ old('device') }}" required
                                   placeholder="Contoh: Laptop Windows / MacBook M2"
                                   class="w-full text-xs bg-white border border-zinc-200 rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 transition">
                            @error('device')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Periode Tanggal --}}
                        <div>
                            <label class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">
                                Tanggal Mulai <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" id="tanggalMulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                                   class="w-full text-xs bg-white border border-zinc-200 rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 transition font-mono-tag">
                            @error('tanggal_mulai')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">
                                Tanggal Selesai <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" id="tanggalSelesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required
                                   class="w-full text-xs bg-white border border-zinc-200 rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 transition font-mono-tag">
                            @error('tanggal_selesai')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Kotak Info Durasi Minimal --}}
                        <div id="durasiInfoBox" class="sm:col-span-2 text-xs p-4 rounded-2xl border bg-zinc-50 border-zinc-200/80 text-zinc-700 flex items-start gap-3 font-mono-tag">
                            <svg class="w-4 h-4 text-zinc-900 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="space-y-1">
                                <p class="font-bold text-xs text-zinc-900">// KETENTUAN DURASI MAGANG:</p>
                                <p class="text-[11px] text-zinc-600 leading-relaxed" id="durasiInfoDesc">
                                    • Berlaku untuk <b>semua status konversi</b> (konversi maupun non-konversi).<br>
                                    • Minimal <b>65 hari kerja efektif</b> (≈ 3 bulan), tidak termasuk Sabtu, Minggu, dan hari libur nasional.
                                </p>
                                <p id="durasiKalkulasi" class="text-[11px] font-semibold text-emerald-700 mt-1 hidden"></p>
                            </div>
                        </div>

                        {{-- BPJS --}}
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">
                                Kepemilikan BPJS Ketenagakerjaan <span class="text-rose-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="punya_bpjs" value="1" required class="peer sr-only" {{ old('punya_bpjs') === '1' ? 'checked' : '' }}>
                                    <div class="px-4 py-2.5 text-center rounded-xl border border-zinc-200 text-xs font-medium text-zinc-600 transition peer-checked:border-zinc-950 peer-checked:bg-zinc-950 peer-checked:text-white">
                                        Sudah Punya
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="punya_bpjs" value="0" required class="peer sr-only" {{ old('punya_bpjs', '0') === '0' ? 'checked' : '' }}>
                                    <div class="px-4 py-2.5 text-center rounded-xl border border-zinc-200 text-xs font-medium text-zinc-600 transition peer-checked:border-zinc-950 peer-checked:bg-zinc-950 peer-checked:text-white">
                                        Belum Punya
                                    </div>
                                </label>
                            </div>
                            @error('punya_bpjs')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </section>

                {{-- ================= BAGIAN 3: BERKAS ================= --}}
                <section class="space-y-5 pt-2">
                    <div class="border-b border-zinc-100 pb-3 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-widest block mb-0.5">SECTION 03</span>
                            <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-950">Berkas Verifikasi Pendukung</h2>
                        </div>
                        <span class="text-[11px] font-mono-tag text-zinc-400">DOCUMENTS</span>
                    </div>

                    <div class="p-4 bg-zinc-50 border border-zinc-200/80 rounded-2xl text-xs text-zinc-600 leading-relaxed font-mono-tag">
                        <span class="font-bold text-zinc-950 block mb-1">// INSTRUKSI DOKUMEN:</span>
                        Gabungkan dokumen persyaratan (Surat Pengantar Kampus, CV, Portofolio/Transkrip Nilai) menjadi <b>1 file PDF, ZIP, atau RAR</b> (maksimal ukuran 5MB).
                    </div>

                    {{-- Dropzone File --}}
                    <label id="dropzone" for="inputDokumen"
                           class="flex flex-col items-center justify-center p-8 border-2 border-dashed border-zinc-200 rounded-2xl bg-zinc-50/50 hover:bg-zinc-50 hover:border-zinc-950 cursor-pointer transition-all duration-300 group">
                        <input type="file" id="inputDokumen" name="dokumen" required accept=".pdf,.zip,.rar" class="sr-only">
                        <div class="w-12 h-12 rounded-2xl bg-white border border-zinc-200 flex items-center justify-center mb-3 shadow-xs group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                            </svg>
                        </div>
                        <p class="text-xs font-semibold text-zinc-950">Pilih berkas arsip atau drag &amp; drop ke sini</p>
                        <p class="text-[11px] font-mono-tag text-zinc-400 mt-1">FORMAT DITERIMA: PDF, ZIP, RAR (MAKS. 5MB)</p>
                    </label>
                    @error('dokumen')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror

                    {{-- Status File Terpilih --}}
                    <div id="fileInfo" class="hidden items-center justify-between p-3.5 bg-zinc-900 text-white rounded-2xl text-xs font-mono-tag">
                        <div class="flex items-center gap-3 truncate">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span class="font-bold truncate" id="fileName">-</span>
                            <span class="text-zinc-400" id="fileSize">-</span>
                        </div>
                        <button type="button" id="btnHapusFile" class="text-xs text-rose-300 hover:text-white font-medium ml-3 px-2 py-1 rounded bg-zinc-800">
                            HAPUS
                        </button>
                    </div>
                </section>

                {{-- ================= BAGIAN 4: AKUN AKSES ================= --}}
                <section class="space-y-5 pt-2">
                    <div class="border-b border-zinc-100 pb-3 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-widest block mb-0.5">SECTION 04</span>
                            <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-950">Kredensial Akses Portal</h2>
                        </div>
                        <span class="text-[11px] font-mono-tag text-zinc-400">CREDENTIALS</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Email --}}
                        <div>
                            <label class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">
                                Alamat Email Login <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   placeholder="nama@email.com"
                                   class="w-full text-xs bg-white border border-zinc-200 rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 transition font-mono-tag">
                            @error('email')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Password --}}
                        <div>
                            <label class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">
                                Kata Sandi Baru <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="inputPassword" name="password" required minlength="8"
                                       placeholder="Minimal 8 karakter"
                                       class="w-full text-xs bg-white border border-zinc-200 rounded-xl px-3.5 py-2.5 pr-10 focus:outline-none focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 transition">
                                <button type="button" id="btnTogglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-400 hover:text-zinc-700">
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
                            <div class="mt-2.5 hidden" id="barisKekuatan">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-zinc-100 rounded-full h-1 overflow-hidden">
                                        <div id="kekuatanBar" class="h-1 rounded-full transition-all duration-300" style="width: 0%;"></div>
                                    </div>
                                    <span id="kekuatanLabel" class="text-[10px] font-mono-tag font-semibold text-zinc-400">-</span>
                                </div>
                            </div>
                            @error('password')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </section>

                {{-- ================= TOMBOL AKSI ================= --}}
                <div class="border-t border-zinc-100 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <a href="{{ route('home') }}" 
                       class="w-full sm:w-auto px-6 py-3 text-center text-xs font-mono-tag font-semibold text-zinc-600 hover:text-zinc-950 border border-zinc-200 rounded-xl hover:bg-zinc-50 transition">
                        &larr; BATALKAN
                    </a>
                    <button type="submit" id="btnKirim" 
                            class="w-full sm:w-auto px-8 py-3.5 text-center text-xs font-mono-tag font-semibold text-white bg-zinc-950 hover:bg-zinc-800 rounded-xl transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 disabled:opacity-60">
                        <svg id="ikonSpinner" class="w-4 h-4 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span id="teksKirim">KIRIM FORMULIR PENDAFTARAN</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Colophon Footer --}}
        <p class="text-center text-xs font-mono-tag text-zinc-400 pt-4">
            &copy; {{ date('Y') }} SIBOOK [V5.0] • BADAN PUSAT STATISTIK KOTA SURABAYA
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

            /* Batas Tanggal & Validasi Durasi (Minimal 65 hari kerja efektif ≈ 3 bulan, berlaku semua status konversi) */
            const tanggalMulai   = document.getElementById('tanggalMulai');
            const tanggalSelesai = document.getElementById('tanggalSelesai');
            const durasiKalkulasi = document.getElementById('durasiKalkulasi');
            const hariIniISO     = new Date().toISOString().split('T')[0];

            /* Daftar hari libur nasional (sinkron dengan WorkdayService.php di backend) */
            const LIBUR_NASIONAL = new Set([
                // 2025
                '2025-01-01','2025-01-27','2025-01-28','2025-01-29',
                '2025-03-29','2025-03-30','2025-03-31','2025-04-01','2025-04-02',
                '2025-04-18','2025-04-19','2025-04-20',
                '2025-05-01','2025-05-12','2025-05-29',
                '2025-06-01','2025-06-06','2025-06-27',
                '2025-08-17','2025-09-05',
                '2025-12-25','2025-12-26',
                // 2026
                '2026-01-01','2026-01-16','2026-01-17','2026-02-17',
                '2026-03-19','2026-03-20','2026-03-21','2026-03-22','2026-03-23',
                '2026-04-02','2026-04-05',
                '2026-05-01','2026-05-02','2026-05-03','2026-05-14','2026-05-27',
                '2026-06-01','2026-06-17',
                '2026-08-17','2026-08-26',
                '2026-12-25','2026-12-26','2026-12-31',
                // 2027
                '2027-01-01','2027-01-05','2027-02-06',
                '2027-03-09','2027-03-10','2027-03-11','2027-03-26',
                '2027-04-25','2027-05-01','2027-05-03','2027-05-17',
                '2027-06-01','2027-06-07',
                '2027-08-16','2027-08-17',
                '2027-12-25',
            ]);

            const MIN_HARI_KERJA = 65;

            /** Cek apakah Date adalah hari kerja (bukan Sabtu/Minggu/libur) */
            function isHariKerja(d) {
                const dow = d.getDay(); // 0=Minggu, 6=Sabtu
                if (dow === 0 || dow === 6) return false;
                const iso = d.toISOString().split('T')[0];
                return !LIBUR_NASIONAL.has(iso);
            }

            /** Hitung hari kerja antara startDateStr dan endDateStr (inklusif) */
            function hitungHariKerja(startDateStr, endDateStr) {
                if (!startDateStr || !endDateStr) return 0;
                const start = new Date(startDateStr + 'T00:00:00');
                const end   = new Date(endDateStr   + 'T00:00:00');
                let count = 0, cur = new Date(start);
                while (cur <= end) {
                    if (isHariKerja(cur)) count++;
                    cur.setDate(cur.getDate() + 1);
                }
                return count;
            }

            /** Hitung tanggal selesai minimal dari startDateStr dengan target MIN_HARI_KERJA */
            function getMinSelesaiDate(startDateStr) {
                if (!startDateStr) return null;
                const cur   = new Date(startDateStr + 'T00:00:00');
                let count   = 0;
                while (count < MIN_HARI_KERJA) {
                    if (isHariKerja(cur)) count++;
                    if (count < MIN_HARI_KERJA) cur.setDate(cur.getDate() + 1);
                }
                return cur;
            }

            function formatTanggalID(d) {
                return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            }

            function perbaruiBatasTanggalSelesai() {
                if (!tanggalMulai || !tanggalSelesai) return;

                if (tanggalMulai.value) {
                    const minDateObj = getMinSelesaiDate(tanggalMulai.value);
                    const minISO = minDateObj.toISOString().split('T')[0];
                    tanggalSelesai.min = minISO;

                    if (durasiKalkulasi) {
                        durasiKalkulasi.classList.remove('hidden');
                        const hariKerjaSkrg = tanggalSelesai.value
                            ? hitungHariKerja(tanggalMulai.value, tanggalSelesai.value)
                            : 0;
                        const cukup = hariKerjaSkrg >= MIN_HARI_KERJA;
                        durasiKalkulasi.className = `text-[11px] font-semibold mt-1 ${cukup ? 'text-emerald-700' : 'text-rose-600'}`;
                        durasiKalkulasi.innerHTML = `Selesai paling awal: <b>${formatTanggalID(minDateObj)}</b> (min. <b>${MIN_HARI_KERJA} hari kerja efektif</b>).`
                            + (tanggalSelesai.value ? ` Saat ini: <b>${hariKerjaSkrg} hari kerja</b>.` : '');
                    }

                    if (tanggalSelesai.value && tanggalSelesai.value < minISO) {
                        tanggalSelesai.setCustomValidity(`Periode magang minimal ${MIN_HARI_KERJA} hari kerja efektif (tidak termasuk Sabtu, Minggu & hari libur). Paling awal: ${formatTanggalID(minDateObj)}.`);
                    } else {
                        tanggalSelesai.setCustomValidity('');
                    }
                } else {
                    tanggalSelesai.min = hariIniISO;
                    if (durasiKalkulasi) durasiKalkulasi.classList.add('hidden');
                    tanggalSelesai.setCustomValidity('');
                }
            }

            if (tanggalMulai) {
                tanggalMulai.min = hariIniISO;
                tanggalMulai.addEventListener('change', perbaruiBatasTanggalSelesai);
            }
            if (tanggalSelesai) {
                tanggalSelesai.addEventListener('change', perbaruiBatasTanggalSelesai);
            }

            const radiosKonversi = form.querySelectorAll('input[name="status_konversi"]');
            radiosKonversi.forEach(r => r.addEventListener('change', perbaruiBatasTanggalSelesai));
            perbaruiBatasTanggalSelesai();

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
                dropzone.classList.add('border-zinc-950', 'bg-zinc-100');
            }));

            ['dragleave', 'drop'].forEach(ev => dropzone.addEventListener(ev, function (e) {
                e.preventDefault();
                dropzone.classList.remove('border-zinc-950', 'bg-zinc-100');
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
                    { w: '25%',  c: 'bg-rose-500',    t: 'LEMAH' },
                    { w: '50%',  c: 'bg-amber-500',   t: 'CUKUP' },
                    { w: '75%',  c: 'bg-blue-500',    t: 'BAIK' },
                    { w: '100%', c: 'bg-emerald-500', t: 'KUAT' },
                ][Math.max(0, skor - 1)] || { w: '0%', c: 'bg-zinc-300', t: '-' };

                kekuatanBar.className = 'h-1 rounded-full transition-all duration-300 ' + tingkat.c;
                kekuatanBar.style.width = v.length ? tingkat.w : '0%';
                kekuatanLabel.textContent = v.length ? tingkat.t : '-';
            });

            /* Anti Double Submit */
            form.addEventListener('submit', function () {
                if (btnKirim.disabled) return;
                btnKirim.disabled = true;
                teksKirim.textContent = 'MEMPROSES...';
                document.getElementById('ikonSpinner').classList.remove('hidden');
            });
        });
    </script>
</body>
</html>
