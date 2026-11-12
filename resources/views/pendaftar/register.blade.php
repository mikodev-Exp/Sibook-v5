<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Magang - SIBOOK</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="relative bg-gradient-to-br from-indigo-50 via-gray-50 to-purple-50 min-h-screen py-10 font-sans text-gray-800">

    <!-- Dekorasi latar -->
    <div class="fixed -top-24 -right-24 w-96 h-96 bg-indigo-200/40 rounded-full blur-3xl pointer-events-none"></div>
    <div class="fixed -bottom-24 -left-24 w-96 h-96 bg-purple-200/40 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-3xl mx-auto px-4 space-y-6">

        <!-- ================= HEADER ================= -->
        <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700 rounded-3xl shadow-xl shadow-indigo-200/60 p-8 text-white text-center">
            <div class="absolute -top-16 -right-16 w-56 h-56 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-20 -left-12 w-48 h-48 bg-fuchsia-400/20 rounded-full blur-2xl"></div>

            <div class="relative">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/15 backdrop-blur-sm text-xs font-semibold tracking-wide">
                    📘 SIBOOK • BPS Kota Surabaya
                </span>
                <h1 class="mt-4 text-2xl lg:text-3xl font-extrabold leading-snug">Formulir Pendaftaran Magang</h1>
                <p class="text-indigo-100 text-sm mt-2">Lengkapi keempat bagian di bawah untuk mendaftar program magang.</p>
                <div class="mt-5 flex flex-wrap justify-center gap-2 text-[11px] font-medium">
                    <span class="px-3 py-1.5 rounded-lg bg-white/10">1️⃣ Data Diri</span>
                    <span class="px-3 py-1.5 rounded-lg bg-white/10">2️⃣ Akademik</span>
                    <span class="px-3 py-1.5 rounded-lg bg-white/10">3️⃣ Berkas</span>
                    <span class="px-3 py-1.5 rounded-lg bg-white/10">4️⃣ Akun</span>
                </div>
            </div>
        </div>

        <!-- ================= PROGRES KELANGKAPAN (STICKY) ================= -->
        <div class="sticky top-4 z-40 bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex justify-between items-center mb-1.5">
                    <p class="text-xs font-bold text-gray-700">Kelengkapan Formulir</p>
                    <p class="text-xs font-bold text-indigo-600"><span id="progressText">0%</span></p>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                    <div id="progressBar" class="h-2.5 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 transition-all duration-500" style="width: 0%;"></div>
                </div>
            </div>
        </div>

        <!-- ================= KARTU FORM ================= -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-6 lg:p-8">

                <!-- Ringkasan error validasi -->
                @if(session('error'))
                    <div class="mb-6 flex items-start gap-3 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm font-medium">{{ session('error') }}</p>
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-6 flex items-start gap-3 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <div class="text-sm">
                            <p class="font-bold mb-1">Periksa kembali isian Anda:</p>
                            <ul class="list-disc list-inside space-y-0.5 text-xs">
                                @foreach($errors->all() as $pesanError)
                                    <li>{{ $pesanError }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form id="formPendaftaran" action="{{ route('pendaftar.register.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                    @csrf

                    <!-- ============ BAGIAN A: DATA DIRI ============ -->
                    <section>
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-500 text-white font-extrabold flex items-center justify-center shadow-md shadow-indigo-200 flex-shrink-0">A</div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-800">Data Diri</h2>
                                <p class="text-xs text-gray-500">Identitas pribadi Anda</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Sesuai kartu identitas"
                                       class="w-full border-gray-300 rounded-xl text-sm {{ $errors->has('nama_lengkap') ? 'border-red-300 focus:ring-red-500/20 focus:border-red-500' : 'focus:ring-indigo-500/20 focus:border-indigo-500' }}">
                                @error('nama_lengkap')<p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="jenis_kelamin" value="Laki-laki" required class="peer sr-only" {{ old('jenis_kelamin') == 'Laki-laki' ? 'checked' : '' }}>
                                        <span class="flex items-center justify-center gap-2 px-3 py-3 rounded-xl border-2 border-gray-200 text-sm font-semibold text-gray-600 transition-all hover:border-gray-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700">
                                            👨 Laki-laki
                                        </span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="jenis_kelamin" value="Perempuan" required class="peer sr-only" {{ old('jenis_kelamin') == 'Perempuan' ? 'checked' : '' }}>
                                        <span class="flex items-center justify-center gap-2 px-3 py-3 rounded-xl border-2 border-gray-200 text-sm font-semibold text-gray-600 transition-all hover:border-gray-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700">
                                            👩 Perempuan
                                        </span>
                                    </label>
                                </div>
                                @error('jenis_kelamin')<p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">No. HP (WhatsApp) <span class="text-red-500">*</span></label>
                                <input type="text" name="no_hp" value="{{ old('no_hp') }}" required placeholder="08xxxxxxxxxx" inputmode="numeric"
                                       class="w-full border-gray-300 rounded-xl text-sm {{ $errors->has('no_hp') ? 'border-red-300 focus:ring-red-500/20 focus:border-red-500' : 'focus:ring-indigo-500/20 focus:border-indigo-500' }}">
                                @error('no_hp')<p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Link Pasfoto (Google Drive) <span class="text-red-500">*</span></label>
                                <input type="url" name="link_pasfoto" value="{{ old('link_pasfoto') }}" required placeholder="https://drive.google.com/..."
                                       class="w-full border-gray-300 rounded-xl text-sm {{ $errors->has('link_pasfoto') ? 'border-red-300 focus:ring-red-500/20 focus:border-red-500' : 'focus:ring-indigo-500/20 focus:border-indigo-500' }}">
                                <p class="text-[11px] text-gray-400 mt-1.5">Pastikan tautan dapat diakses publik (Anyone with the link).</p>
                                @error('link_pasfoto')<p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Username Instagram <span class="text-red-500">*</span></label>
                                <input type="text" name="username_ig" value="{{ old('username_ig') }}" required placeholder="@username"
                                       class="w-full border-gray-300 rounded-xl text-sm {{ $errors->has('username_ig') ? 'border-red-300 focus:ring-red-500/20 focus:border-red-500' : 'focus:ring-indigo-500/20 focus:border-indigo-500' }}">
                                @error('username_ig')<p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </section>

                    <div class="border-t border-dashed border-gray-200"></div>

                    <!-- ============ BAGIAN B: AKADEMIK & MAGANG ============ -->
                    <section>
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-500 text-white font-extrabold flex items-center justify-center shadow-md shadow-indigo-200 flex-shrink-0">B</div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-800">Data Akademik &amp; Magang</h2>
                                <p class="text-xs text-gray-500">Kampus dan rencana periode magang</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Universitas <span class="text-red-500">*</span></label>
                                <input type="text" name="universitas" value="{{ old('universitas') }}" required placeholder="Contoh: Universitas Airlangga"
                                       class="w-full border-gray-300 rounded-xl text-sm {{ $errors->has('universitas') ? 'border-red-300 focus:ring-red-500/20 focus:border-red-500' : 'focus:ring-indigo-500/20 focus:border-indigo-500' }}">
                                @error('universitas')<p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Program Studi <span class="text-red-500">*</span></label>
                                <input type="text" name="program_studi" value="{{ old('program_studi') }}" required placeholder="Contoh: Statistika"
                                       class="w-full border-gray-300 rounded-xl text-sm {{ $errors->has('program_studi') ? 'border-red-300 focus:ring-red-500/20 focus:border-red-500' : 'focus:ring-indigo-500/20 focus:border-indigo-500' }}">
                                @error('program_studi')<p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Status Konversi <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="status_konversi" value="0" required class="peer sr-only" {{ old('status_konversi') == '0' ? 'checked' : '' }}>
                                        <span class="flex items-center justify-center gap-1.5 px-3 py-3 rounded-xl border-2 border-gray-200 text-sm font-semibold text-gray-600 transition-all hover:border-gray-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700">✅ Ya</span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="status_konversi" value="1" required class="peer sr-only" {{ old('status_konversi') == '1' ? 'checked' : '' }}>
                                        <span class="flex items-center justify-center gap-1.5 px-3 py-3 rounded-xl border-2 border-gray-200 text-sm font-semibold text-gray-600 transition-all hover:border-gray-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700">❌ Tidak</span>
                                    </label>
                                </div>
                                <p class="text-[11px] text-gray-400 mt-1.5">Apakah Anda mengambil status konversi?</p>
                                @error('status_konversi')<p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Device yang Digunakan <span class="text-red-500">*</span></label>
                                <input type="text" name="device" value="{{ old('device') }}" required placeholder="Contoh: Android, iPhone"
                                       class="w-full border-gray-300 rounded-xl text-sm {{ $errors->has('device') ? 'border-red-300 focus:ring-red-500/20 focus:border-red-500' : 'focus:ring-indigo-500/20 focus:border-indigo-500' }}">
                                @error('device')<p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Periode Mulai Magang <span class="text-red-500">*</span></label>
                                <input type="date" id="tanggalMulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                                       class="w-full border-gray-300 rounded-xl text-sm {{ $errors->has('tanggal_mulai') ? 'border-red-300 focus:ring-red-500/20 focus:border-red-500' : 'focus:ring-indigo-500/20 focus:border-indigo-500' }}">
                                @error('tanggal_mulai')<p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Periode Selesai Magang <span class="text-red-500">*</span></label>
                                <input type="date" id="tanggalSelesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required
                                       class="w-full border-gray-300 rounded-xl text-sm {{ $errors->has('tanggal_selesai') ? 'border-red-300 focus:ring-red-500/20 focus:border-red-500' : 'focus:ring-indigo-500/20 focus:border-indigo-500' }}">
                                @error('tanggal_selesai')<p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">BPJS Ketenagakerjaan <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="punya_bpjs" value="0" required class="peer sr-only" {{ old('punya_bpjs') == '0' ? 'checked' : '' }}>
                                        <span class="flex items-center justify-center gap-1.5 px-3 py-3 rounded-xl border-2 border-gray-200 text-sm font-semibold text-gray-600 transition-all hover:border-gray-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700">✅ Sudah Punya</span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="punya_bpjs" value="1" required class="peer sr-only" {{ old('punya_bpjs') == '1' ? 'checked' : '' }}>
                                        <span class="flex items-center justify-center gap-1.5 px-3 py-3 rounded-xl border-2 border-gray-200 text-sm font-semibold text-gray-600 transition-all hover:border-gray-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700">❌ Belum Punya</span>
                                    </label>
                                </div>
                                <p class="text-[11px] text-gray-400 mt-1.5">Apakah Anda memiliki BPJS Ketenagakerjaan?</p>
                                @error('punya_bpjs')<p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </section>

                    <div class="border-t border-dashed border-gray-200"></div>

                    <!-- ============ BAGIAN C: BERKAS PENDUKUNG ============ -->
                    <section>
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-500 text-white font-extrabold flex items-center justify-center shadow-md shadow-indigo-200 flex-shrink-0">C</div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-800">Berkas Pendukung</h2>
                                <p class="text-xs text-gray-500">Dokumen persyaratan pendaftaran</p>
                            </div>
                        </div>

                        <div class="mb-4 flex items-start gap-3 p-4 bg-amber-50 border border-amber-200 rounded-2xl">
                            <span class="text-xl leading-none">⚠️</span>
                            <div class="text-sm">
                                <p class="text-amber-800 font-bold">Wajib Diunggah!</p>
                                <p class="text-xs text-amber-700 mt-1 leading-relaxed">Apabila dokumen lebih dari satu (Surat Pengantar, CV, Transkrip), mohon digabungkan terlebih dahulu menjadi <b>1 file PDF atau ZIP</b>.</p>
                            </div>
                        </div>

                        <!-- Dropzone unggah file -->
                        <label id="dropzone" for="inputDokumen"
                               class="flex flex-col items-center justify-center gap-2 px-6 py-10 rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50 text-center cursor-pointer transition-all duration-300 hover:border-indigo-400 hover:bg-indigo-50/50">
                            <input type="file" id="inputDokumen" name="dokumen" required accept=".pdf,.zip,.rar" class="sr-only">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            </div>
                            <p class="text-sm font-bold text-gray-700">Klik atau seret &amp; lepas file di sini</p>
                            <p class="text-xs text-gray-400">Format: PDF, ZIP, RAR • Maksimal ukuran sesuai ketentuan server</p>
                        </label>
                        @error('dokumen')<p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>@enderror

                        <!-- Info file terpilih -->
                        <div id="fileInfo" class="hidden mt-3 flex items-center gap-3 p-3 bg-green-50 border border-green-200 rounded-xl">
                            <div class="w-9 h-9 rounded-lg bg-green-100 text-green-700 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p id="fileName" class="text-sm font-bold text-gray-800 truncate">-</p>
                                <p id="fileSize" class="text-xs text-gray-500">-</p>
                            </div>
                            <button type="button" id="btnHapusFile" class="px-3 py-1.5 text-xs font-bold rounded-lg bg-white border border-red-200 text-red-600 hover:bg-red-50 transition-colors">Hapus</button>
                        </div>
                    </section>

                    <div class="border-t border-dashed border-gray-200"></div>

                    <!-- ============ BAGIAN D: AKUN AKSES ============ -->
                    <section>
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-500 text-white font-extrabold flex items-center justify-center shadow-md shadow-indigo-200 flex-shrink-0">D</div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-800">Akun Akses</h2>
                                <p class="text-xs text-gray-500">Untuk masuk ke SIBOOK &amp; memantau status pendaftaran</p>
                            </div>
                        </div>

                        <div class="mb-5 flex items-start gap-3 p-4 bg-blue-50 border border-blue-200 rounded-2xl">
                            <span class="text-xl leading-none">🔐</span>
                            <p class="text-xs text-blue-800 leading-relaxed">Akun ini akan digunakan untuk mengakses website ini dan mendapatkan informasi lebih lanjut terkait status pendaftaran Anda.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Email Aktif <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                                       class="w-full border-gray-300 rounded-xl text-sm {{ $errors->has('email') ? 'border-red-300 focus:ring-red-500/20 focus:border-red-500' : 'focus:ring-indigo-500/20 focus:border-indigo-500' }}">
                                <p class="text-[11px] text-gray-400 mt-1.5">Notifikasi status pendaftaran dikirim ke email ini.</p>
                                @error('email')<p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="password" id="inputPassword" name="password" required minlength="8" placeholder="Minimal 8 karakter"
                                           class="w-full border-gray-300 rounded-xl text-sm pr-11 {{ $errors->has('password') ? 'border-red-300 focus:ring-red-500/20 focus:border-red-500' : 'focus:ring-indigo-500/20 focus:border-indigo-500' }}">
                                    <button type="button" id="btnTogglePassword" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-indigo-600 transition-colors" tabindex="-1">
                                        <!-- Mata terbuka (terlihat saat password tersembunyi) -->
                                        <svg id="ikonMataBuka" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <!-- Mata tertutup (terlihat saat password tampil) -->
                                        <svg id="ikonMataTutup" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m14.82 8.82L21.836 21"></path></svg>
                                    </button>
                                </div>
                                <!-- Meter kekuatan password -->
                                <div class="mt-2 hidden" id="barisKekuatan">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                            <div id="kekuatanBar" class="h-1.5 rounded-full transition-all duration-300" style="width: 0%;"></div>
                                        </div>
                                        <span id="kekuatanLabel" class="text-[11px] font-bold text-gray-400 w-14 text-right">-</span>
                                    </div>
                                </div>
                                @error('password')<p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </section>

                    <!-- ============ TOMBOL AKSI ============ -->
                    <div class="border-t border-gray-200 pt-6">
                        <p class="text-xs text-gray-400 mb-4 text-center">Dengan mengirim formulir ini, Anda menyatakan seluruh data di atas benar dan dapat dipertanggungjawabkan.</p>
                        <div class="flex flex-col-reverse sm:flex-row justify-end gap-3">
                            <a href="{{ route('home') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-bold text-sm rounded-xl hover:bg-gray-200 transition-colors text-center">Batal</a>
                            <button type="submit" id="btnKirim" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold text-sm rounded-xl transition-all duration-300 shadow-md shadow-indigo-200 hover:shadow-lg hover:-translate-y-0.5 disabled:opacity-60 disabled:hover:translate-y-0 flex items-center justify-center gap-2">
                                <svg id="ikonSpinner" class="w-4 h-4 animate-spin hidden" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                <span id="teksKirim">Kirim Pendaftaran</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 pb-4">© {{ date('Y') }} SIBOOK — Badan Pusat Statistik Kota Surabaya</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form      = document.getElementById('formPendaftaran');
            const btnKirim  = document.getElementById('btnKirim');
            const teksKirim = document.getElementById('teksKirim');

            /* ---------- PROGRES KELENGKAPAN FORMULIR ---------- */
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');

            function perbaruiProgres() {
                // Kelompokkan field required berdasarkan name (radio dihitung satu grup)
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

            /* ---------- BATAS TANGGAL MAGANG ---------- */
            const tanggalMulai   = document.getElementById('tanggalMulai');
            const tanggalSelesai = document.getElementById('tanggalSelesai');
            const hariIniISO     = new Date().toISOString().split('T')[0];

            if (tanggalMulai) {
                tanggalMulai.min = hariIniISO;
                tanggalMulai.addEventListener('change', function () {
                    if (this.value) tanggalSelesai.min = this.value;
                });
            }

            /* ---------- DROPZONE DOKUMEN ---------- */
            const dropzone   = document.getElementById('dropzone');
            const inputDok   = document.getElementById('inputDokumen');
            const fileInfo   = document.getElementById('fileInfo');
            const fileNameEl = document.getElementById('fileName');
            const fileSizeEl = document.getElementById('fileSize');

            function tampilkanFile(file) {
                if (!file) return;
                fileNameEl.textContent = file.name;
                fileSizeEl.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                fileInfo.classList.remove('hidden');
                fileInfo.classList.add('flex');
            }

            inputDok.addEventListener('change', function () {
                if (this.files.length > 0) tampilkanFile(this.files[0]);
            });

            ['dragover', 'dragenter'].forEach(ev => dropzone.addEventListener(ev, function (e) {
                e.preventDefault();
                dropzone.classList.add('border-indigo-500', 'bg-indigo-50', 'scale-[1.01]');
            }));

            ['dragleave', 'drop'].forEach(ev => dropzone.addEventListener(ev, function (e) {
                e.preventDefault();
                dropzone.classList.remove('border-indigo-500', 'bg-indigo-50', 'scale-[1.01]');
            }));

            dropzone.addEventListener('drop', function (e) {
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    inputDok.files = files;
                    tampilkanFile(files[0]);
                }
            });

            document.getElementById('btnHapusFile').addEventListener('click', function () {
                inputDok.value = '';
                fileInfo.classList.add('hidden');
                fileInfo.classList.remove('flex');
            });

            /* ---------- TAMPIL/SIMPAN PASSWORD ---------- */
            const inputPassword = document.getElementById('inputPassword');
            const ikonBuka  = document.getElementById('ikonMataBuka');
            const ikonTutup = document.getElementById('ikonMataTutup');

            document.getElementById('btnTogglePassword').addEventListener('click', function () {
                const tampil = inputPassword.type === 'password';
                inputPassword.type = tampil ? 'text' : 'password';
                ikonBuka.classList.toggle('hidden', tampil);
                ikonTutup.classList.toggle('hidden', !tampil);
            });

            /* ---------- METER KEKUATAN PASSWORD ---------- */
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
                    { w: '25%',  c: 'bg-red-500',    t: 'Lemah',   l: 'text-red-500' },
                    { w: '50%',  c: 'bg-orange-500', t: 'Cukup',   l: 'text-orange-500' },
                    { w: '75%',  c: 'bg-yellow-500', t: 'Baik',    l: 'text-yellow-600' },
                    { w: '100%', c: 'bg-green-500',  t: 'Kuat',    l: 'text-green-600' },
                ][Math.max(0, skor - 1)] || { w: '0%', c: 'bg-gray-300', t: '-', l: 'text-gray-400' };

                kekuatanBar.className = 'h-1.5 rounded-full transition-all duration-300 ' + tingkat.c;
                kekuatanBar.style.width = v.length ? tingkat.w : '0%';
                kekuatanLabel.textContent = v.length ? tingkat.t : '-';
                kekuatanLabel.className = 'text-[11px] font-bold w-14 text-right ' + (v.length ? tingkat.l : 'text-gray-400');
            });

            /* ---------- ANTI DOUBLE-SUBMIT ---------- */
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