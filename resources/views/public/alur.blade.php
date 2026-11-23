<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alur Pengajuan & Kurasi Magang - SIBOOK BPS Kota Surabaya</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800|space-mono:400,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Figtree', sans-serif; }
        .font-mono-tag { font-family: 'Space Mono', monospace; }
    </style>
</head>
<body class="bg-[#FAFAFA] text-[#09090B] flex flex-col min-h-screen antialiased">
    <!-- Header -->
    <header class="bg-white/90 backdrop-blur-md border-b border-zinc-200/80 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-zinc-950 text-white flex items-center justify-center font-bold text-xs">
                    SB
                </div>
                <span class="text-base font-extrabold tracking-tight text-zinc-950">SIBOOK-V5</span>
            </a>
            <a href="{{ route('home') }}" class="text-xs font-mono-tag text-zinc-600 hover:text-zinc-950 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
    </header>

    <main class="flex-1 py-16 px-6 max-w-4xl mx-auto w-full">
        <div class="mb-12 pb-6 border-b border-zinc-200">
            <span class="text-xs font-mono-tag text-zinc-400 uppercase tracking-widest block mb-2">// CURATORIAL PROTOCOL</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-zinc-950 tracking-tight">Panduan Alur Pengajuan Magang</h1>
            <p class="text-sm text-zinc-500 mt-2">Badan Pusat Statistik Kota Surabaya • Penerimaan Terpadu Mahasiswa Magang</p>
        </div>

        <div class="space-y-6">
            <!-- Stage 01 -->
            <div class="p-8 bg-white rounded-3xl border border-zinc-200/80 shadow-sm flex flex-col sm:flex-row gap-6">
                <div class="w-12 h-12 rounded-2xl bg-zinc-950 text-white flex items-center justify-center font-mono-tag text-lg font-bold shrink-0">
                    01
                </div>
                <div>
                    <span class="text-[10px] font-mono-tag uppercase tracking-wider text-zinc-400">Tahap Pertama</span>
                    <h3 class="text-lg font-bold text-zinc-950 mt-0.5 mb-2">Registrasi Akun Calon Pendaftar</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed">
                        Daftarkan diri menggunakan alamat email aktif. Pastikan data identitas awal sesuai dengan data diri mahasiswa resmi di perguruan tinggi asal.
                    </p>
                </div>
            </div>

            <!-- Stage 02 -->
            <div class="p-8 bg-white rounded-3xl border border-zinc-200/80 shadow-sm flex flex-col sm:flex-row gap-6">
                <div class="w-12 h-12 rounded-2xl bg-zinc-950 text-white flex items-center justify-center font-mono-tag text-lg font-bold shrink-0">
                    02
                </div>
                <div>
                    <span class="text-[10px] font-mono-tag uppercase tracking-wider text-zinc-400">Tahap Kedua</span>
                    <h3 class="text-lg font-bold text-zinc-950 mt-0.5 mb-2">Unggah Berkas Persyaratan Terkurasi</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed">
                        Lengkapi formulir permohonan magang, tentukan rencana periode minimal (3 bulan untuk konversi / 2,5 bulan non-konversi), serta unggah berkas wajib format PDF (Surat Pengantar Kampus, CV, Transkrip Nilai, Pasfoto).
                    </p>
                </div>
            </div>

            <!-- Stage 03 -->
            <div class="p-8 bg-white rounded-3xl border border-zinc-200/80 shadow-sm flex flex-col sm:flex-row gap-6">
                <div class="w-12 h-12 rounded-2xl bg-zinc-950 text-white flex items-center justify-center font-mono-tag text-lg font-bold shrink-0">
                    03
                </div>
                <div>
                    <span class="text-[10px] font-mono-tag uppercase tracking-wider text-zinc-400">Tahap Ketiga</span>
                    <h3 class="text-lg font-bold text-zinc-950 mt-0.5 mb-2">Kurasi Berkas oleh Admin BPS</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed">
                        Admin melakukan verifikasi berkas dan ketersediaan kuota jurusan. Calon peserta dapat memantau status telaah secara langsung melalui dashboard akun pendaftar.
                    </p>
                </div>
            </div>

            <!-- Stage 04 -->
            <div class="p-8 bg-white rounded-3xl border border-zinc-200/80 shadow-sm flex flex-col sm:flex-row gap-6">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-mono-tag text-lg font-bold shrink-0 shadow-md">
                    04
                </div>
                <div>
                    <span class="text-[10px] font-mono-tag uppercase tracking-wider text-emerald-700 font-bold">Tahap Akhir</span>
                    <h3 class="text-lg font-bold text-zinc-950 mt-0.5 mb-2">Persetujuan Akhir & Hak Akses Studio Mahasiswa</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed">
                        Setelah disetujui, role akun pendaftar otomatis ditingkatkan menjadi Mahasiswa Magang. Anda mendapatkan akses penuh ke modul Presensi Geolocation dan Logbook Karya Harian.
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('pendaftar.register') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-2xl bg-zinc-950 text-white hover:bg-zinc-800 font-bold text-xs shadow-lg transition">
                <span>Mulai Pengajuan Magang Sekarang</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-8 border-t border-zinc-200 text-center text-xs font-mono-tag text-zinc-400">
        &copy; {{ date('Y') }} BPS Kota Surabaya. Sistem Informasi Buku Magang SIBOOK-V5.
    </footer>
</body>
</html>
