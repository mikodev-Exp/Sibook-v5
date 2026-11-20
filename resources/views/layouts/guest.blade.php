<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SIBOOK') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-800 bg-gray-50">
    <div class="min-h-screen flex">
        
        <!-- KIRI: Branding / Ilustrasi (Hanya muncul di layar besar) -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white p-12 flex-col justify-between relative overflow-hidden">
            <!-- Dekorasi Latar -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-white opacity-10 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-white opacity-10 rounded-full blur-2xl"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-12 h-12 bg-white text-indigo-700 rounded-xl flex items-center justify-center text-2xl font-bold shadow-lg">
                        S
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight">SiBook</h1>
                        <p class="text-[10px] uppercase tracking-widest text-indigo-200 font-bold">BPS Kota Surabaya</p>
                    </div>
                </div>
                <h2 class="text-4xl font-extrabold leading-tight mb-4">
                    Sistem Informasi <br> Buku Magang Terpadu.
                </h2>
                <p class="text-indigo-100 text-lg leading-relaxed">
                    Kelola administrasi, presensi harian, logbook pekerjaan, dan perizinan magang Anda dalam satu platform digital yang terintegrasi.
                </p>
            </div>
            
            <div class="relative z-10">
                <p class="text-sm text-indigo-200">&copy; {{ date('Y') }} Badan Pusat Statistik Kota Surabaya.</p>
            </div>
        </div>

        <!-- KANAN: Area Form Login/Register -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 relative">
            <!-- Tombol Kembali ke Beranda -->
            <div class="absolute top-6 right-6 sm:top-8 sm:right-8">
                <a href="{{ route('home') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Beranda
                </a>
            </div>

            <div class="w-full max-w-md">
                <!-- Ini tempat form login/register muncul -->
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>