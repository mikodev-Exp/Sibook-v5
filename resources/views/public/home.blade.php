<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIBOOK - Sistem Informasi Buku Magang BPS Kota Surabaya</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-800 bg-gray-50 flex flex-col min-h-screen">

    <!-- Navbar -->
    <header class="bg-white/90 backdrop-blur-md shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <!-- Logo Placeholder -->
                <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">
                    S
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight">SIBOOK-V5</h1>
                    <p class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">BPS Kota Surabaya</p>
                </div>
            </div>
            <nav class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium shadow-sm">
                        Ke Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 font-medium text-sm transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium shadow-sm">
                        Registrasi
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="flex-1">
        <section class="relative bg-white overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute inset-y-0 right-0 w-1/2 bg-indigo-50 rounded-l-full transform translate-x-1/3 -translate-y-1/4 opacity-50"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32 relative z-10">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div class="max-w-2xl">
                        <span class="inline-block py-1 px-3 rounded-full bg-indigo-100 text-indigo-700 text-xs font-semibold tracking-wide mb-4">
                            Program Magang Terpadu
                        </span>
                        <h2 class="text-4xl lg:text-5xl font-extrabold text-gray-900 leading-tight mb-6">
                            Mulai Perjalanan Magang Anda di <span class="text-indigo-600">BPS Kota Surabaya</span>
                        </h2>
                        <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                            Kelola administrasi, presensi harian, logbook pekerjaan, dan perizinan magang Anda dalam satu platform digital yang terintegrasi.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            @guest
                                <a href="{{ route('pendaftar.register') }}" class="px-8 py-3.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all font-semibold text-center shadow-lg hover:shadow-xl">
                                    Ajukan Permohonan Sekarang
                                </a>
                            @endguest
                            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="px-8 py-3.5 bg-white text-indigo-600 border border-indigo-200 rounded-lg hover:bg-indigo-50 transition-all font-semibold text-center">
                                Lihat Status Pendaftaran
                            </a>
                        </div>
                    </div>
                    
                    <!-- Ilustrasi / Gambar -->
                    <div class="hidden lg:block relative">
                        <img src="https://illustrations.popsy.co/indigo/student-going-to-school.svg" alt="Ilustrasi Mahasiswa" class="w-full h-auto drop-shadow-xl">
                    </div>
                </div>
            </div>
        </section>

        <!-- Alur Pendaftaran -->
        <section id="alur" class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">Alur Pengajuan Magang</h3>
                    <p class="text-gray-600">Ikuti langkah-langkah berikut untuk mendaftar sebagai mahasiswa magang di BPS Kota Surabaya.</p>
                </div>

                <div class="grid md:grid-cols-4 gap-8">
                    <!-- Step 1 -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative hover:-translate-y-1 transition-transform">
                        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center text-xl font-bold mb-4">1</div>
                        <h4 class="font-bold text-gray-900 mb-2">Registrasi Akun</h4>
                        <p class="text-sm text-gray-500 leading-relaxed">Buat akun dan verifikasi email Anda melalui tautan yang dikirimkan.</p>
                    </div>
                    <!-- Step 2 -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative hover:-translate-y-1 transition-transform">
                        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center text-xl font-bold mb-4">2</div>
                        <h4 class="font-bold text-gray-900 mb-2">Upload Dokumen</h4>
                        <p class="text-sm text-gray-500 leading-relaxed">Lengkapi berkas seperti surat permohonan kampus, CV, dan transkrip nilai.</p>
                    </div>
                    <!-- Step 3 -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative hover:-translate-y-1 transition-transform">
                        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center text-xl font-bold mb-4">3</div>
                        <h4 class="font-bold text-gray-900 mb-2">Review Admin</h4>
                        <p class="text-sm text-gray-500 leading-relaxed">Admin akan memverifikasi kelengkapan dokumen yang Anda unggah.</p>
                    </div>
                    <!-- Step 4 -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative hover:-translate-y-1 transition-transform">
                        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center text-xl font-bold mb-4">4</div>
                        <h4 class="font-bold text-gray-900 mb-2">Persetujuan</h4>
                        <p class="text-sm text-gray-500 leading-relaxed">Super User memberikan persetujuan akhir dan akun Anda berubah menjadi Mahasiswa Magang.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Fitur Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">Fitur Setelah Diterima</h3>
                    <p class="text-gray-600">Kemudahan administrasi selama masa magang Anda.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Fitur 1 -->
                    <div class="p-8 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="w-14 h-14 bg-indigo-600 text-white rounded-xl flex items-center justify-center mb-6 shadow-md">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Presensi Geolocation</h4>
                        <p class="text-gray-600 leading-relaxed text-sm">Lakukan presensi masuk dan pulang langsung dari ponsel Anda dengan validasi radius kantor (1 km).</p>
                    </div>
                    <!-- Fitur 2 -->
                    <div class="p-8 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="w-14 h-14 bg-indigo-600 text-white rounded-xl flex items-center justify-center mb-6 shadow-md">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Logbook Harian</h4>
                        <p class="text-gray-600 leading-relaxed text-sm">Catat kegiatan harian Anda sebelum melakukan presensi pulang. Terhubung langsung dengan Admin.</p>
                    </div>
                    <!-- Fitur 3 -->
                    <div class="p-8 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="w-14 h-14 bg-indigo-600 text-white rounded-xl flex items-center justify-center mb-6 shadow-md">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Perizinan Online</h4>
                        <p class="text-gray-600 leading-relaxed text-sm">Ajukan izin tidak masuk dengan mudah. Cukup unggah link Google Drive bukti surat izin Anda.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-indigo-500 rounded flex items-center justify-center text-white font-bold text-sm">
                    S
                </div>
                <span class="text-lg font-bold text-white tracking-tight">SIBOOK-V5</span>
            </div>
            <p class="text-sm text-center md:text-left text-gray-400">
                &copy; {{ date('Y') }} Badan Pusat Statistik Kota Surabaya.<br class="md:hidden"> Sistem Informasi Buku Magang.
            </p>
            <div class="flex gap-4 text-sm">
                <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                <a href="#" class="hover:text-white transition-colors">Bantuan</a>
            </div>
        </div>
    </footer>

</body>
</html>